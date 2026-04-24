<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use Illuminate\Http\Request;
use App\Models\Doctor;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::with('user')->paginate(10);
        return view('admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(StoreAdminRequest $request)
    {
        Admin::create($request->validated());
        return redirect()->route('admins.index')->with('success', 'Admin created successfully.');
    }

    public function show(Admin $admin)
    {
        $admin->load('user');
        return view('admin.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        return view('admin.edit', compact('admin'));
    }

    // public function profile(Admin $admin)
    // {
    //     $admin->load('user');
    //     dd($admin);
    //     return view('admin.profile', compact('admin'));
    // }

    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $admin->update($request->validated());
        return redirect()->route('admins.index')->with('success', 'Admin updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admins.index')->with('success', 'Admin deleted successfully.');
    }




    public function doctorVerifications()
{
    $doctors = Doctor::with('user')
        ->where('verification_status', 'pending')
        ->get();
       
    return view('admin.doctor-verifications', compact('doctors'));
}
public function approveDoctor($doctorId)
{
    
    $doctor = Doctor::findOrFail($doctorId);

    $doctor->update([
        'is_verified' => true,
        'verification_status' => 'approved'
    ]);

    return back()->with('success', 'Doctor approved successfully');
}

public function rejectDoctor($doctorId)
{
    $doctor = Doctor::findOrFail($doctorId);

    $doctor->update([
        'is_verified' => false,
        'verification_status' => 'rejected'
    ]);

    return back()->with('success', 'Doctor rejected successfully');
}
}
