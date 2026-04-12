<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with('user');

        if ($request->has('search') && $request->search) {
            $query->where('specialization', 'like', '%' . $request->search . '%');
        }

        $doctors = $query->paginate(10);
        return view('doctor.index', compact('doctors'));
    }

    public function create()
    {
        return view('doctor.create');
    }

    public function store(Request $request)
    {
        if ($request->has('skip')) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'phone' => 'required|string|max:50',
            'specialization' => 'required|string|max:255',
            'experience' => 'required|integer|min:0',
            'fees' => 'required|numeric|min:0',
            'clinic_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'is_verified' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'doctor',
            'is_active' => true,
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'phone' => $validated['phone'],
            'specialization' => $validated['specialization'],
            'experience' => $validated['experience'],
            'fees' => $validated['fees'],
            'clinic_name' => $validated['clinic_name'],
            'address' => $validated['address'] ?? null,
            'is_verified' => $validated['is_verified'] ?? false,
            'rating_avg' => 0,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Registration submitted.']);
        }

        return redirect()->route('login')->with('success', 'Registration submitted. Please login after approval.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load('user');
        return view('doctor.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        return view('doctor.edit', compact('doctor'));
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->validated());
        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
    }
}
