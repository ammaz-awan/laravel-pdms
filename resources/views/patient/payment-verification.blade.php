@extends('layouts.layout')

@section('title', 'Payment Verification')

@section('content')

<div class="content">

    <div class="container">

        <!-- Page Header -->
        <div class="mb-4">
            <h4 class="fw-bold">Payment Verification</h4>
            <p class="text-muted">Verify your account to unlock booking and doctor access.</p>
        </div>

        <!-- Alert -->
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

                <div class="row g-4">

                    <!-- Left Card Preview -->
                    <div class="col-lg-5">
                        <div class="p-4 rounded-4 text-white" style="background: linear-gradient(135deg, #4e73df, #224abe); min-height:200px;">
                            <p class="mb-4">Secure Payment</p>
                            <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                            <p class="mb-0">**** **** **** ****</p>

                            <div class="d-flex justify-content-between mt-4">
                                <small>Powered by Stripe</small>
                                <i class="ti ti-credit-card"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Right Form -->
                    <div class="col-lg-7">

                        <form id="payment-form">

                            @csrf

                            <!-- Cardholder Name -->
                            <div class="mb-3">
                                <label class="form-label">Cardholder Name</label>
                                <input type="text" id="cardholder-name" class="form-control" placeholder="Full Name">
                            </div>

                            <!-- Stripe Card Element -->
                            <div class="mb-3">
                                <label class="form-label">Card Details</label>
                                <div id="card-element" class="form-control p-3"></div>
                                <div id="card-errors" class="text-danger mt-2"></div>
                            </div>

                            <!-- Billing Address -->
                            <div class="mb-3">
                                <label class="form-label">Billing Address (optional)</label>
                                <input type="text" id="billing-address" class="form-control" placeholder="Billing Address">
                            </div>

                            <!-- Info -->
                            <p class="text-muted small">
                                🔒 Secure payment handled by Stripe. Your card details are never stored on our server.
                            </p>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between mt-4">

                                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                                    Back
                                </a>

                                <button type="button" id="pay-btn" class="btn btn-primary">
                                    Verify & Pay
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

@endsection


@section('scripts')

<script src="https://js.stripe.com/v3/"></script>

<script>
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements();

    const card = elements.create('card');
    card.mount('#card-element');

    // Show errors
    card.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        displayError.textContent = event.error ? event.error.message : '';
    });

    // Handle Payment Button
    document.getElementById('pay-btn').addEventListener('click', async () => {

        const response = await fetch("{{ route('stripe.checkout') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                type: "verification"
            })
        });

        const data = await response.json();

        if (data.url) {
            window.location.href = data.url;
        } else {
            alert("Something went wrong.");
        }
    });
</script>

@endsection