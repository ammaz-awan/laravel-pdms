<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    // ─── Role-aware index ───────────────────────────────────────────────────

    public function index()
    {
        $user = Auth::user();
        $this->authorize('viewAny', Prescription::class);

        if ($user->role === 'admin') {
            $prescriptions = Prescription::with([
                'appointment.doctor.user',
                'appointment.patient.user',
                'doctor.user',
                'patient.user',
            ])->latest()->paginate(15);
        } elseif ($user->role === 'doctor') {
            $prescriptions = Prescription::with([
                'appointment',
                'patient.user',
            ])->where('doctor_id', $user->doctor->id)
              ->latest()
              ->paginate(15);
        } else {
            // patient
            $prescriptions = Prescription::with([
                'appointment',
                'doctor.user',
            ])->where('patient_id', $user->patient->id)
              ->latest()
              ->paginate(15);
        }

        return view('prescription.index', compact('prescriptions'));
    }

    // ─── Role-aware show ────────────────────────────────────────────────────

    public function show(Prescription $prescription)
    {
        $this->authorize('view', $prescription);

        $prescription->load(
            'appointment.patient.user',
            'appointment.doctor.user',
            'doctor.user',
            'patient.user'
        );

        return view('prescription.show', compact('prescription'));
    }

    // ─── Live call: doctor writes prescription during session ───────────────
    // POST /appointments/{id}/prescription
    public function liveStore(Request $request, int $id)
    {
        $user        = Auth::user();
        $appointment = Appointment::findOrFail($id);

        if ($user->role !== 'doctor' || $appointment->doctor->user_id !== $user->id) {
            return response()->json([
                'error' => 'Unauthorized. Only the appointment doctor can write a prescription.',
            ], 403);
        }

        $validated = $request->validate([
            'diagnosis'            => 'nullable|string|max:2000',
            'medicines'            => 'nullable|array',
            'medicines.*.name'     => 'required_with:medicines|string|max:255',
            'medicines.*.dosage'   => 'nullable|string|max:255',
            'medicines.*.duration' => 'nullable|string|max:255',
            'notes'                => 'nullable|string|max:5000',
        ]);

        $prescription = Prescription::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'doctor_id'  => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
                'diagnosis'  => $validated['diagnosis'] ?? null,
                'medicines'  => array_values(array_filter(
                    $validated['medicines'] ?? [],
                    fn ($m) => ! empty(trim($m['name'] ?? ''))
                )),
                'notes'      => $validated['notes'] ?? null,
            ]
        );

        return response()->json([
            'message'      => 'Prescription saved.',
            'prescription' => $prescription,
        ]);
    }

    // ─── Live call: read current prescription (GET) ─────────────────────────
    // GET /appointments/{id}/prescription
    public function liveShow(int $id)
    {
        $user        = Auth::user();
        $appointment = Appointment::with('prescription')->findOrFail($id);

        // Doctor or patient of this appointment may read
        $isDoctor  = $user->role === 'doctor'  && $appointment->doctor->user_id  === $user->id;
        $isPatient = $user->role === 'patient' && $appointment->patient->user_id === $user->id;
        $isAdmin   = $user->role === 'admin';

        if (! $isDoctor && ! $isPatient && ! $isAdmin) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        return response()->json($appointment->prescription);
    }
}

