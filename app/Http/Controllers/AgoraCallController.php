<?php

namespace App\Http\Controllers;

use App\Helpers\AgoraTokenBuilder;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class AgoraCallController extends Controller
{
    // Token validity: 30 minutes
    private const CALL_DURATION_SECONDS = 1800;

    // ---------------------------------------------------------------
    // POST /doctor/appointments/{id}/start-call
    // ---------------------------------------------------------------
    public function startCall(Request $request, int $id)
    {
        $user = Auth::user();

        // Only doctors
        if ($user->role !== 'doctor') {
            abort(403, 'Only doctors can start a call.');
        }

        $appointment = Appointment::findOrFail($id);

        // Ownership check
        if ($appointment->doctor->user_id !== $user->id) {
            abort(403, 'This appointment does not belong to you.');
        }

        // Business-rule guards
        if ($appointment->payment_status !== 'paid') {
            return back()->with('error', 'Call cannot be started: appointment fee is unpaid.');
        }

        if ($appointment->status !== 'approved') {
            return back()->with('error', 'Call cannot be started: appointment is not approved.');
        }

        // Parse appointment scheduled time
        $scheduledAt = Carbon::parse(
            $appointment->appointment_date->format('Y-m-d') . ' ' .
            Carbon::parse($appointment->appointment_time)->format('H:i:s')
        );

        $now = Carbon::now();
        $earliestStart = $scheduledAt->copy()->subMinutes(5);
        $sessionEndTime = $scheduledAt->copy()->addMinutes(30);

        // Check 1: Prevent starting before scheduled time (allow 5-min grace for the appointment start)
        if ($now->lt($earliestStart)) {
            return back()->with('error', 'Call cannot be started before the scheduled appointment time. Please wait until ' . $earliestStart->format('h:i A'));
        }

        // Check 2: Prevent starting after the 30-minute session window has closed
        if ($now->gt($sessionEndTime)) {
            return back()->with('error', 'This appointment session has expired. The 30-minute window ended at ' . $sessionEndTime->format('h:i A'));
        }

        // Generate channel name if not already set
        $channel = $appointment->agora_channel ?? ('appt_' . $id);

        $expiredTs = time() + self::CALL_DURATION_SECONDS;

        $token = $this->generateToken($channel, $user->id, AgoraTokenBuilder::ROLE_PUBLISHER, $expiredTs);

        $appointment->update([
            'agora_channel'   => $channel,
            'agora_uid'       => (string) $user->id,
            'call_started_at' => Carbon::now(),
            'status'          => 'approved', // keep as approved while active
        ]);

        if ($request->expectsJson()) {
            $appId = config('agora.app_id');
            return response()->json([
                'channel_name' => $channel,
                'token'        => $token,
                'app_id'       => $appId,
                'uid'          => $user->id,
                'expires_at'   => $expiredTs,
            ]);
        }

        return redirect()->route('appointments.call', ['id' => $id]);
    }

    // ---------------------------------------------------------------
    // GET /appointments/{id}/join-call
    // ---------------------------------------------------------------
    public function joinCall(int $id)
    {
        $user        = Auth::user();
        $appointment = Appointment::with(['doctor.user', 'patient.user', 'prescription'])->findOrFail($id);

        // Must be the patient or doctor of this appointment
        $isDoctor  = ($user->role === 'doctor'  && $appointment->doctor->user_id  === $user->id);
        $isPatient = ($user->role === 'patient' && $appointment->patient->user_id === $user->id);

        if (! $isDoctor && ! $isPatient) {
            abort(403, 'You are not part of this appointment.');
        }

        if ($appointment->payment_status !== 'paid') {
            return back()->with('error', 'This appointment has not been paid.');
        }

        if ($appointment->status !== 'approved') {
            return back()->with('error', 'This appointment is not active.');
        }

        if (! $appointment->call_started_at) {
            return back()->with('error', 'The call has not been started by the doctor yet.');
        }

        // Check call hasn't expired (30 min)
        if (Carbon::now()->gt($appointment->call_started_at->addSeconds(self::CALL_DURATION_SECONDS))) {
            return back()->with('error', 'This call session has expired.');
        }

        $channel   = $appointment->agora_channel;
        $expiredTs = $appointment->call_started_at->timestamp + self::CALL_DURATION_SECONDS;
        $role      = $isDoctor ? AgoraTokenBuilder::ROLE_PUBLISHER : AgoraTokenBuilder::ROLE_SUBSCRIBER;
        $token     = $this->generateToken($channel, $user->id, $role, $expiredTs);

        $appId = config('agora.app_id');
        
        // Debug logging
        Log::info('Join call data:', [
            'app_id' => $appId,
            'channel' => $channel,
            'uid' => $user->id,
            'is_doctor' => $isDoctor,
        ]);

        $callData = [
            'channel_name' => $channel,
            'token'        => $token,
            'app_id'       => $appId,
            'uid'          => $user->id,
            'expires_at'   => $expiredTs,
            'is_doctor'    => $isDoctor,
        ];

        return view('appointment.video-call', compact('appointment', 'callData'));
    }

    // ---------------------------------------------------------------
    // POST /appointments/{id}/end-call
    // ---------------------------------------------------------------
    public function endCall(Request $request, int $id)
    {
        $user = Auth::user();

        if ($user->role !== 'doctor') {
            abort(403, 'Only doctors can end a call.');
        }

        $appointment = Appointment::findOrFail($id);

        if ($appointment->doctor->user_id !== $user->id) {
            abort(403, 'This appointment does not belong to you.');
        }

        $appointment->update([
            'status'       => 'completed',
            'completed_at' => Carbon::now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Call ended. Appointment marked as completed.']);
        }

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Video call ended. Appointment marked as completed.');
    }

    // ---------------------------------------------------------------
    // GET /appointments/{id}/call-status   (JSON, polling endpoint)
    // Returns whether the call is still active so the frontend can
    // auto-end when the session window closes.
    // ---------------------------------------------------------------
    public function callStatus(int $id)
    {
        $user        = Auth::user();
        $appointment = Appointment::findOrFail($id);

        $isDoctor  = ($user->role === 'doctor'  && $appointment->doctor->user_id  === $user->id);
        $isPatient = ($user->role === 'patient' && $appointment->patient->user_id === $user->id);

        if (! $isDoctor && ! $isPatient) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        $expiresAt = $appointment->call_started_at
            ? $appointment->call_started_at->timestamp + self::CALL_DURATION_SECONDS
            : null;

        $isActive = $appointment->status === 'approved'
            && $appointment->call_started_at
            && Carbon::now()->lte($appointment->call_started_at->addSeconds(self::CALL_DURATION_SECONDS));

        return response()->json([
            'active'     => $isActive,
            'status'     => $appointment->status,
            'expires_at' => $expiresAt,
            'server_time'=> time(),
        ]);
    }

    // ---------------------------------------------------------------
    // GET /agora/debug-token  (dev only — remove before production)
    // Returns the raw config values and a freshly-minted test token
    // so you can compare the embedded App ID against your console.
    // ---------------------------------------------------------------
    public function debugToken()
    {
        if (app()->isProduction()) {
            abort(404);
        }

        $appId  = config('agora.app_id');
        $cert   = config('agora.app_certificate');
        $expiry = time() + 3600;

        $result = [
            'env_app_id'       => $appId,
            'env_app_id_len'   => strlen($appId),
            'env_cert_set'     => !empty($cert),
            'env_cert_len'     => strlen($cert),
        ];

        try {
            $token = AgoraTokenBuilder::buildTokenWithUid(
                $appId, $cert, 'debug_test', 0,
                AgoraTokenBuilder::ROLE_PUBLISHER, $expiry
            );
            $embeddedAppId = substr($token, 3, 32);
            $b64payload    = substr($token, 35);
            $decoded       = base64_decode($b64payload, true);

            $result['token_prefix']        = substr($token, 0, 3);
            $result['token_embedded_appid']= $embeddedAppId;
            $result['appid_matches_config']= ($embeddedAppId === $appId);
            $result['token_length']        = strlen($token);
            $result['payload_bytes']       = $decoded !== false ? strlen($decoded) : 'INVALID_BASE64';
            $result['token_expires_at']    = date('Y-m-d H:i:s', $expiry);
            // First 20 chars of token (safe to show; never shows the cert)
            $result['token_preview']       = substr($token, 0, 40) . '...';
        } catch (\Exception $e) {
            $result['token_error'] = $e->getMessage();
        }

        return response()->json($result, 200, [], JSON_PRETTY_PRINT);
    }

    private function generateToken(string $channel, int $uid, int $role, int $expiredTs): string
    {
        $appId          = config('agora.app_id');
        $appCertificate = config('agora.app_certificate');

        if (empty($appId) || empty($appCertificate)) {
            Log::error('Agora credentials missing', [
                'app_id'          => $appId,
                'app_certificate' => !empty($appCertificate) ? 'SET' : 'EMPTY',
            ]);
            throw new \RuntimeException('Agora credentials are not configured in .env file. Please set AGORA_APP_ID and AGORA_APP_CERTIFICATE.');
        }

        $token = AgoraTokenBuilder::buildTokenWithUid(
            $appId,
            $appCertificate,
            $channel,
            $uid,
            $role,
            $expiredTs
        );

        Log::debug('Agora token generated', [
            'channel'       => $channel,
            'uid'           => $uid,
            'expires_at'    => date('Y-m-d H:i:s', $expiredTs),
            'token_prefix'  => substr($token, 0, 3),
            'appid_in_token'=> substr($token, 3, 32),
        ]);

        return $token;
    }
}
