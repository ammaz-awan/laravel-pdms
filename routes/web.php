<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\DashboardController;

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
    Route::get('/profile/admin/{admin}', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/profile/doctor/{doctor}', [DoctorController::class, 'profile'])->name('doctor.profile');
    Route::get('/profile/patient/{patient}', [PatientController::class, 'profile'])->name('patient.profile');

    Route::resource('admins', AdminController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('prescriptions', PrescriptionController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('ratings', RatingController::class);
});