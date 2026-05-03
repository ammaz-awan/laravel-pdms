<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Http\Controllers\AgoraCallController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorScheduleController;
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

    // Prescriptions – immutable records (view-only after creation)
    Route::get('prescriptions',          [PrescriptionController::class, 'index'])->name('prescriptions.index');
    Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');

    // Invoices – immutable records (view-only after auto-creation)
    Route::get('invoices',               [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}',     [InvoiceController::class, 'show'])->name('invoices.show');

    // ── Panel-scoped aliases (redirect to same controllers) ──────────────────
    Route::get('patient/prescriptions',           [PrescriptionController::class, 'index'])->name('patient.prescriptions.index');
    Route::get('patient/prescriptions/{id}',      [PrescriptionController::class, 'show'])->name('patient.prescriptions.show');
    Route::get('patient/invoices',                [InvoiceController::class, 'index'])->name('patient.invoices.index');
    Route::get('patient/invoices/{id}',           [InvoiceController::class, 'show'])->name('patient.invoices.show');

    Route::get('admin/prescriptions',             [PrescriptionController::class, 'index'])->name('admin.prescriptions.index');
    Route::get('admin/prescriptions/{id}',        [PrescriptionController::class, 'show'])->name('admin.prescriptions.show');
    Route::get('admin/invoices',                  [InvoiceController::class, 'index'])->name('admin.invoices.index');
    Route::get('admin/invoices/{id}',             [InvoiceController::class, 'show'])->name('admin.invoices.show');

    Route::get('doctor/prescriptions',            [PrescriptionController::class, 'index'])->name('doctor.prescriptions.index');
    Route::get('doctor/prescriptions/{id}',       [PrescriptionController::class, 'show'])->name('doctor.prescriptions.show');
    Route::get('doctor/invoices',                 [InvoiceController::class, 'index'])->name('doctor.invoices.index');
    Route::get('doctor/invoices/{id}',            [InvoiceController::class, 'show'])->name('doctor.invoices.show');

    Route::resource('payments', PaymentController::class);
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

Route::middleware(['auth'])->group(function () {
    Route::get('/doctor/appointments', [AppointmentController::class, 'doctorAppointments'])->name('doctor.appointments');
    Route::get('/doctor/my-patients', [DoctorController::class, 'myPatients'])->name('doctor.my-patients');
    Route::post('/doctor/schedule', [DoctorScheduleController::class, 'store'])->name('doctor.schedule.store');
    Route::get('/doctor/{doctor}/schedule', [DoctorScheduleController::class, 'getScheduleByDoctor'])->name('doctor.schedule.show');
    Route::get('/admin/appointments', [AppointmentController::class, 'adminAppointments'])->name('admin.appointments');
    Route::post('/appointments/book', [AppointmentController::class, 'store'])->name('appointments.book');
    Route::post('/doctor/appointments/{appointment}/approve', [AppointmentController::class, 'approve'])->name('doctor.appointments.approve');
    Route::post('/doctor/appointments/{appointment}/reject', [AppointmentController::class, 'reject'])->name('doctor.appointments.reject');
});




Route::middleware(['auth'])->group(function () {

    Route::post('/appointments/{appointment}/payment-intent',
        [AppointmentController::class, 'createPaymentIntent']
    )->name('appointments.payment.intent');

    Route::post('/appointments/payment/confirm',
        [AppointmentController::class, 'confirmPayment']
    )->name('appointments.payment.confirm');

    Route::post('/appointments/{appointment}/refund',
        [AppointmentController::class, 'refundPayment']
    )->name('appointments.payment.refund');

});

// -----------------------------------------------------------------------
// Video Call & Live Prescription Routes
// -----------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // Doctor starts call
    Route::post('/doctor/appointments/{id}/start-call', [AgoraCallController::class, 'startCall'])
        ->name('doctor.appointments.start-call');

    // Both doctor and patient join (renders video-call view)
    Route::get('/appointments/{id}/join-call', [AgoraCallController::class, 'joinCall'])
        ->name('appointments.call');

    // Doctor ends call
    Route::post('/appointments/{id}/end-call', [AgoraCallController::class, 'endCall'])
        ->name('appointments.end-call');

    // Call status polling (JSON) — used by the video call page to detect auto-end
    Route::get('/appointments/{id}/call-status', [AgoraCallController::class, 'callStatus'])
        ->name('appointments.call-status');

    // Live prescription – doctor writes (POST), both read (GET)
    Route::post('/appointments/{id}/prescription', [PrescriptionController::class, 'liveStore'])
        ->name('appointments.prescription.store');

    Route::get('/appointments/{id}/prescription', [PrescriptionController::class, 'liveShow'])
        ->name('appointments.prescription.show');

    // ── Dev-only debug routes (disabled automatically in production) ──
    Route::get('/agora/debug-token', [AgoraCallController::class, 'debugToken'])
        ->name('agora.debug-token');
});