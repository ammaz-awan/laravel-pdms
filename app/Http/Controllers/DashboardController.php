<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get statistics based on user role
        $stats = [];

        if ($user->role === 'admin') {
            $stats = [
                'total_doctors' => Doctor::count(),
                'total_patients' => Patient::count(),
                'total_appointments' => Appointment::count(),
                'total_payments' => Payment::sum('amount'),
                'recent_appointments' => Appointment::with(['doctor', 'patient'])->latest()->take(5)->get(),
                'recent_payments' => Payment::with(['appointment.patient'])->latest()->take(5)->get(),
            ];
            return view('admin.dashboard', compact('stats', 'user'));
        } elseif ($user->role === 'doctor') {
            $doctorId = $user->doctor?->id;
            $stats = [
                'total_appointments' => $doctorId ? Appointment::where('doctor_id', $doctorId)->count() : 0,
                'online_consultations' => 0,
                'cancelled_appointments' => $doctorId ? Appointment::where('doctor_id', $doctorId)->where('status', 'cancelled')->count() : 0,
                'total_patients' => $doctorId ? Patient::whereHas('appointments', function($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })->count() : 0,
                'video_consultations' => 0,
                'rescheduled' => 0,
                'pre_visit_bookings' => 0,
                'walkin_bookings' => 0,
                'follow_ups' => 0,
                'completed_appointments' => $doctorId ? Appointment::where('doctor_id', $doctorId)->where('status', 'completed')->count() : 0,
                'pending_appointments' => $doctorId ? Appointment::where('doctor_id', $doctorId)->where('status', 'pending')->count() : 0,
                'upcoming_appointments' => $doctorId ? Appointment::with(['patient'])->where('doctor_id', $doctorId)->where('date', '>=', today())->orderBy('date')->take(3)->get() : collect(),
                'recent_appointments' => $doctorId ? Appointment::with(['patient'])->where('doctor_id', $doctorId)->latest()->take(5)->get() : collect(),
                'top_patients' => $doctorId ? Patient::withCount(['appointments' => function($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                }])->having('appointments_count', '>', 0)->orderBy('appointments_count', 'desc')->take(5)->get() : collect(),
            ];
            return view('doctor.dashboard', compact('stats', 'user'));
        } elseif ($user->role === 'patient') {
            $patientId = $user->patient?->id;
            $stats = [
                'total_appointments' => $patientId ? Appointment::where('patient_id', $patientId)->count() : 0,
                'online_consultations' => 0,
                'blood_pressure' => '120/80', // This would come from medical records
                'heart_rate' => 72, // This would come from medical records
                'weight' => 70, // This would come from medical records
                'height' => 170, // This would come from medical records
                'bmi' => '22.5', // This would come from medical records
                'pulse' => 72, // This would come from medical records
                'spo2' => 98, // This would come from medical records
                'temperature' => 98.6, // This would come from medical records
                'my_doctors' => $patientId ? Doctor::whereHas('appointments', function($q) use ($patientId) {
                    $q->where('patient_id', $patientId);
                })->withCount(['appointments' => function($q) use ($patientId) {
                    $q->where('patient_id', $patientId);
                }])->get() : collect(),
                'prescriptions' => collect(), // This would come from prescriptions table
                'recent_activities' => collect(), // This would come from activities/medical records
                'recent_transactions' => $patientId ? Payment::whereHas('appointment', function($q) use ($patientId) {
                    $q->where('patient_id', $patientId);
                })->with(['appointment.doctor'])->latest()->take(5)->get()->map(function($payment) {
                    return (object) [
                        'doctor_name' => $payment->appointment->doctor->name ?? 'Doctor',
                        'specialization' => $payment->appointment->doctor->specialization ?? 'Specialist',
                        'amount' => $payment->amount,
                        'status' => $payment->status ?? 'success'
                    ];
                }) : collect(),
            ];
            return view('patient.dashboard', compact('stats', 'user'));
        }

        // Default fallback
        return view('dashboard', compact('stats', 'user'));
    }
}