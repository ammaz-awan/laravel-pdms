<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with('patient.user', 'doctor.user');

        if ($request->has('date') && $request->date) {
            $query->where('date', $request->date);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(10);
        return view('appointment.index', compact('appointments'));
    }

    public function create()
    {
        return view('appointment.create');
    }

    public function store(StoreAppointmentRequest $request)
    {
        Appointment::create($request->validated());
        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('patient.user', 'doctor.user');
        return view('appointment.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        return view('appointment.edit', compact('appointment'));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());
        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    }
}
