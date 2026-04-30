@extends('layouts.layout')

@section('title', 'View Appointment')

@section('content')

  

<div class="card">
    <div class="card-header">
        <i class="ti ti-eye"></i> Appointment Details
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>ID:</strong> {{ $appointment->id }}</p>
                <p><strong>Patient:</strong> {{ $appointment->patient->user->name }}</p>
                <p><strong>Patient Email:</strong> {{ $appointment->patient->user->email }}</p>
                <p><strong>Doctor:</strong> {{ $appointment->doctor->user->name }}</p>
            </div>

            <div class="col-md-6">
                <p><strong>Date:</strong> {{ $appointment->appointment_date->format('M d, Y') }}</p>
                <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                <p><strong>Fee:</strong> ${{ number_format($appointment->fee_snapshot ?? $appointment->doctor->fees, 2) }}
                  @if($appointment->payment_status == 'paid')
                        <span class="badge bg-success">Paid</span>
                    @else
                        <span class="badge bg-warning">Unpaid</span>
                    @endif
                </p>

                <p>
                    <strong>Status:</strong>
                    @if($appointment->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($appointment->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-danger">Cancelled</span>
                    @endif
                </p>
            </div>
        </div>

        @if($appointment->notes)
            <div class="alert alert-info mt-3">
                <strong>Notes:</strong> {{ $appointment->notes }}
            </div>
        @endif

        <hr>

        <div class="d-flex gap-2 flex-wrap">

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-primary">
                    <i class="ti ti-pencil"></i> Edit
                </a>
            @endif

            @if(in_array(auth()->user()->role, ['admin', 'doctor'], true) && $appointment->status === 'pending')
                <form action="{{ route('doctor.appointments.approve', $appointment) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-check"></i> Approve
                    </button>
                </form>

                <form action="{{ route('doctor.appointments.reject', $appointment) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-x"></i> Reject
                    </button>
                </form>
            @endif

            <a href="{{ route('appointments.index') }}" class="btn btn-primary">
                <i class="ti ti-arrow-left"></i> Back
            </a>
        </div>

        {{-- ✅ PATIENT PAYMENT SECTION (ONLY ONE PLACE) --}}
                @if(
                    $appointment->status === 'approved' &&
                    $appointment->payment_status !== 'paid' &&
                    auth()->user()->role === 'patient'
                )

            <hr>

            <div class="mt-3">
                <label><strong>Card Details</strong></label>
                <div id="card-element" class="form-control p-3"></div>
            </div>

            <button class="btn btn-primary mt-3" onclick="payNow({{ $appointment->id }})">
                <i class="ti ti-credit-card"></i> Pay Now
            </button>

        @endif

    </div>
</div>

@endsection

@section('scripts')

<script>
let stripe = Stripe("{{ config('services.stripe.key') }}");
let elements = stripe.elements();
let card = null;

// mount ONCE safely
document.addEventListener("DOMContentLoaded", function () {
    const el = document.getElementById("card-element");

    if (el) {
        card = elements.create("card");
        card.mount("#card-element");
    }
});

async function payNow(appointmentId) {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    if (!card) {
        Swal.fire({
            icon: 'error',
            title: 'Stripe Not Ready',
            text: 'Card form not loaded. Refresh page.'
        });
        return;
    }

    try {
        const res = await fetch(`/appointments/${appointmentId}/payment-intent`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            }
        });

        const data = await res.json();

        if (!res.ok) {
            Swal.fire({
                icon: 'error',
                title: 'Payment Failed',
                text: data.error || "Payment intent failed"
            });
            return;
        }

        const result = await stripe.confirmCardPayment(data.clientSecret, {
            payment_method: {
                card: card
            }
        });

        if (result.error) {
            Swal.fire({
                icon: 'error',
                title: 'Stripe Error',
                text: result.error.message
            });
            return;
        }

        if (result.paymentIntent.status === "succeeded") {

            const confirmRes = await fetch(`/appointments/payment/confirm`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    payment_intent_id: result.paymentIntent.id
                })
            });

            const confirmData = await confirmRes.json();

            if (confirmData.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Confirmation Failed',
                    text: confirmData.error
                });
                return;
            }

            Swal.fire({
                icon: 'success',
                title: 'Payment Successful',
                text: 'Your payment has been completed successfully!'
            }).then(() => {
                location.reload();
            });
        }

    } catch (err) {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Payment failed. Check console.'
        });
    }
}
</script>

@endsection