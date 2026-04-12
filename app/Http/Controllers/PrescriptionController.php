<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Requests\UpdatePrescriptionRequest;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
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
}
