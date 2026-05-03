<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    // ─── Role-aware index ───────────────────────────────────────────────────

    public function index()
    {
        $user = Auth::user();
        $this->authorize('viewAny', Invoice::class);

        if ($user->role === 'admin') {
            $invoices = Invoice::with([
                'patient.user',
                'appointment.doctor.user',
                'payment',
            ])->latest()->paginate(15);
        } elseif ($user->role === 'doctor') {
            // Invoices for appointments handled by this doctor
            $invoices = Invoice::with(['patient.user', 'appointment', 'payment'])
                ->whereHas('appointment', function ($q) use ($user) {
                    $q->where('doctor_id', $user->doctor->id);
                })
                ->latest()
                ->paginate(15);
        } else {
            // patient
            $invoices = Invoice::with(['appointment.doctor.user', 'payment'])
                ->where('patient_id', $user->patient->id)
                ->latest()
                ->paginate(15);
        }

        return view('invoice.index', compact('invoices'));
    }

    // ─── Role-aware show ────────────────────────────────────────────────────

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load(
            'patient.user',
            'appointment.doctor.user',
            'appointment.patient.user',
            'payment'
        );

        return view('invoice.show', compact('invoice'));
    }

    // ─── Static factory: create invoice after payment succeeds ─────────────
    // Called from AppointmentController::confirmPayment()

    public static function createFromPayment(Payment $payment): Invoice
    {
        $appointment = $payment->appointment()->with('patient')->firstOrFail();

        // Idempotent: never duplicate for the same payment
        $existing = Invoice::where('payment_id', $payment->id)->first();
        if ($existing) {
            return $existing;
        }

        return Invoice::create([
            'payment_id'     => $payment->id,
            'appointment_id' => $appointment->id,
            'patient_id'     => $appointment->patient_id,
            'invoice_number' => Invoice::generateNumber(),
            'total_amount'   => $payment->amount,
            'issued_date'    => now()->toDateString(),
            'status'         => 'paid',
        ]);
    }
}

