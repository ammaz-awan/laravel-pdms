<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// Authentication Routes
Auth::routes();

Route::get('/register/patient', function () {
    return view('auth.patient-register');
})->name('register.patient');

Route::post('/register/patient', [PatientController::class, 'store'])->name('register.patient.store');

Route::get('/register/doctor', function () {
    return view('auth.doctor-register');
})->name('register.doctor');

Route::post('/register/doctor', [DoctorController::class, 'store'])->name('register.doctor.store');

// Protected Routes - Require Authentication
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::redirect('/', '/dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Profile Routes
    Route::get('/profile/admin/{admin}', function (Admin $admin) {
        return redirect()->route('profile.show', $admin->user->ensureUuid());
    })->name('admin.profile');

    Route::get('/profile/doctor/{doctor}', function (Doctor $doctor) {
        return redirect()->route('profile.show', $doctor->user->ensureUuid());
    })->name('doctor.profile');

    Route::get('/profile/patient/{patient}', function (Patient $patient) {
        return redirect()->route('profile.show', $patient->user->ensureUuid());
    })->name('patient.profile');

    Route::get('/profile/{uuid}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/{uuid}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{uuid}', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('admins', AdminController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('prescriptions', PrescriptionController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('ratings', RatingController::class);
});
