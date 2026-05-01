<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Appointment;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Requests\UpdatePrescriptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    // ---------------------------------------------------------------
    // Standard CRUD (admin / staff use)
    // ---------------------------------------------------------------

    public function index()
    {
        $prescriptions = Prescription::with('appointment.patient.user', 'doctor.user')->paginate(10);
        return view('prescription.index', compact('prescriptions'));
    }

    public function create()
    {
        return view('prescription.create');
    }

    public function store(StorePrescriptionRequest $request)
    {
        Prescription::create($request->validated());
        return redirect()->route('prescriptions.index')->with('success', 'Prescription created successfully.');
    }

    public function show(Prescription $prescription)
    {
        $prescription->load('appointment.patient.user', 'doctor.user');
        return view('prescription.show', compact('prescription'));
    }

    public function edit(Prescription $prescription)
    {
        return view('prescription.edit', compact('prescription'));
    }

    public function update(UpdatePrescriptionRequest $request, Prescription $prescription)
    {
        $prescription->update($request->validated());
        return redirect()->route('prescriptions.index')->with('success', 'Prescription updated successfully.');
    }

    public function destroy(Prescription $prescription)
    {
        $prescription->delete();
        return redirect()->route('prescriptions.index')->with('success', 'Prescription deleted successfully.');
    }

    // ---------------------------------------------------------------
    // Live call prescription: POST /appointments/{id}/prescription
    // Doctor only – create or update prescription for an appointment
    // ---------------------------------------------------------------
    public function liveStore(Request $request, int $id)
    {
        $user        = Auth::user();
        $appointment = Appointment::findOrFail($id);

        // Only the doctor of this appointment may write
        if ($user->role !== 'doctor' || $appointment->doctor->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized. Only the appointment doctor can write a prescription.'], 403);
        }

        $validated = $request->validate([
            'diagnosis' => 'nullable|string|max:2000',
            'medicines' => 'nullable|array',
            'medicines.*.name'     => 'required_with:medicines|string|max:255',
            'medicines.*.dosage'   => 'nullable|string|max:255',
            'medicines.*.duration' => 'nullable|string|max:255',
            'notes'     => 'nullable|string|max:5000',
        ]);

        $prescription = Prescription::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'doctor_id'  => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
                'diagnosis'  => $validated['diagnosis'] ?? null,
                'medicines'  => array_values(array_filter(
                    $validated['medicines'] ?? [],
                    fn($m) => !empty(trim($m['name'] ?? ''))
                )),
                'notes'      => $validated['notes'] ?? null,
            ]
        );

        return response()->json([
            'message'      => 'Prescription saved.',
            'prescription' => $prescription,
        ]);
    }

    // ---------------------------------------------------------------
    // Live call prescription: GET /appointments/{id}/prescription
    // Doctor can view/edit; patient can only view (read-only JSON)
    // ---------------------------------------------------------------
    public function liveShow(int $id)
    {
        $user        = Auth::user();
        $appointment = Appointment::findOrFail($id);

        $isDoctor  = ($user->role === 'doctor'  && $appointment->doctor->user_id  === $user->id);
        $isPatient = ($user->role === 'patient' && $appointment->patient->user_id === $user->id);

        if (! $isDoctor && ! $isPatient) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        $prescription = $appointment->prescription;

        return response()->json([
            'prescription' => $prescription,
            'can_edit'     => $isDoctor,
        ]);
    }
}
