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
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\AI\DoctorVerificationAIController;


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


Route::get('/auth/google', [SocialController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialController::class, 'handleGoogleCallback']);


Route::get('/auth/facebook', [SocialController::class, 'redirectToFacebook']);
Route::get('/auth/facebook/callback', [SocialController::class, 'handleFacebookCallback']);

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
});

Route::get('/data-deletion', function () {
return response("To delete your account, email support@pdms.developers.ink. We will remove your data within 7 days.");
});

Route::get('/terms', function () {
    return view('terms');
});

// Protected Routes - Require Authentication
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::redirect('/', '/dashboard');

    Route::get('/doctor/dashboard', function () {
        return view('doctor.dashboard');
    });
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

    Route::get('/patient/payment-verification', [PatientController::class, 'paymentPage'])
    ->name('patient.payment.page')
    ->middleware(['auth']);
    
    Route::post('/mark-verified', [PatientController::class, 'markVerified'])->middleware('auth');

    Route::get('/ai/doctor/{id}/analyze', [
    DoctorVerificationAIController::class,
    'analyzeCertificate'
     ])->name('ai.doctor.analyze')->middleware('auth');
    

    Route::resource('admins', AdminController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('prescriptions', PrescriptionController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('ratings', RatingController::class);
    });
    
    Route::post('/stripe/create-intent', [PatientController::class, 'createIntent'])
    ->middleware('throttle:10,1');
Route::post('/stripe/register-intent', [PatientController::class, 'registerIntent'])
    ->middleware('throttle:10,1');    
Route::post('/register-mark-verified', [PatientController::class, 'markVerifiedAfterRegister'])
    ->middleware('throttle:10,1');

//admin doctor verification routes
Route::get('/admin/doctor-verifications', [AdminController::class, 'doctorVerifications'])
    ->name('doctor-verifications');

Route::post('/admin/doctor-verifications/{doctor}/approve', [AdminController::class, 'approveDoctor'])
    ->name('doctor.approve');

Route::post('/admin/doctor-verifications/{doctor}/reject', [AdminController::class, 'rejectDoctor'])
    ->name('doctor.reject');

Route::post('/doctor/update-verification', [DoctorController::class, 'updateVerification'])
    ->middleware('auth')
    ->name('doctor.updateVerification');


