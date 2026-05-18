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
$doctor = $user->doctor; // must exist

if (! $doctor) {
    abort(403, 'Doctor profile not found.');
}

        $appointment = Appointment::findOrFail($id);
        // Ownership check
       if ((int) $appointment->doctor_id !== (int) $doctor->id) {
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

       $now = now(); // uses app timezone        

        $earliestStart = $scheduledAt->copy()->subMinutes(5);
        $sessionEndTime = $this->getScheduledSessionEnd($appointment);

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

        $expiredTs = $sessionEndTime->timestamp;

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
       $isDoctor = $user->role === 'doctor'
       && (int) optional($user->doctor)->id === (int) $appointment->doctor_id;

       $isPatient = $user->role === 'patient'
       && (int) optional($user->patient)->id === (int) $appointment->patient_id;

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

        if ($this->completeExpiredCall($appointment)) {
            return back()->with('error', 'This call session has expired.');
        }

        $channel   = $appointment->agora_channel;
        $expiredTs = $this->getScheduledSessionEnd($appointment)->timestamp;
        $token     = $this->generateToken($channel, $user->id, AgoraTokenBuilder::ROLE_PUBLISHER, $expiredTs);

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

        if ((int) $appointment->doctor_id !== (int) optional($user->doctor)->id) {
                abort(403, 'This appointment does not belong to you.');
            }

        $appointment->update([
            'status'       => 'completed',
            'completed_at' => Carbon::now(),
        ]);

        if ($request->ajax() || $request->expectsJson()) {
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

         $isDoctor = $user->role === 'doctor'
         && (int) optional($user->doctor)->id === (int) $appointment->doctor_id;

         $isPatient = $user->role === 'patient'
         && (int) optional($user->patient)->id === (int)
         $appointment->patient_id;

        if (! $isDoctor && ! $isPatient) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        $expiresAt = $this->getScheduledSessionEnd($appointment)->timestamp;

        $expired = $this->completeExpiredCall($appointment);

        $isActive = ! $expired
            && $appointment->status === 'approved'
            && $appointment->call_started_at
            && Carbon::now()->lte($this->getScheduledSessionEnd($appointment));

        return response()->json([
            'active'     => $isActive,
            'status'     => $appointment->status,
            'expires_at' => $expiresAt,
            'server_time'=> time(),
        ]);
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

    private function completeExpiredCall(Appointment $appointment): bool
    {
        if (! $appointment->call_started_at) {
            return false;
        }

        $expired = Carbon::now()->gt(
            $this->getScheduledSessionEnd($appointment)
        );

        if ($expired && $appointment->status === 'approved') {
            $appointment->update([
                'status'       => 'completed',
                'completed_at' => Carbon::now(),
            ]);
        }

        return $expired;
    }

    private function getScheduledSessionEnd(Appointment $appointment)
    {
        return Carbon::parse(
            $appointment->appointment_date->format('Y-m-d') . ' ' .
            Carbon::parse($appointment->appointment_time)->format('H:i:s')
        )->addMinutes(30);
    }
}
