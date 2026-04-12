<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patient Registration | PDMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Dreams Technologies">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-icon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">

    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/simplebar/simplebar.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="app-style">

    <style>
        .step-indicator {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        .step-pill {
            border-radius: 1rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
            transition: border-color .2s ease, background .2s ease;
        }
        .step-pill.active {
            background: rgba(13, 110, 253, .08);
            border-color: #0d6efd;
        }
        .step-pill .step-number {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #e7f1ff;
            color: #0d6efd;
            font-weight: 700;
            margin-right: .75rem;
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        .step-card-icon {
            width: 48px;
            height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, .1);
            border-radius: .85rem;
            color: #0d6efd;
        }
        .payment-card {
            background: linear-gradient(135deg, #0d6efd 0%, #5b8cff 100%);
            color: white;
            border-radius: 1.25rem;
            padding: 1.5rem;
            min-height: 180px;
            position: relative;
        }
        .payment-card .card-chip {
            width: 44px;
            height: 34px;
            border-radius: .6rem;
            background: rgba(255,255,255,.65);
            margin-bottom: 1rem;
        }
        .payment-card-icons i {
            font-size: 1.2rem;
            margin-right: .75rem;
            opacity: .85;
        }
        .form-feedback {
            display: none;
        }
        .form-feedback.active {
            display: block;
        }
        .loader-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9;
        }
        .loader-overlay .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>

</head>

<body>

    <!-- Begin Wrapper -->
    <div class="main-wrapper auth-bg position-relative overflow-hidden">

        <!-- Start Content -->
        <div class="container-fuild position-relative z-1">
            <div class="w-100 overflow-hidden position-relative flex-wrap d-block vh-100">

                <!-- start row -->
                <div class="row justify-content-center align-items-center vh-100 overflow-auto flex-wrap py-3">
                    <div class="col-xl-8 col-lg-9 col-md-10 mx-auto">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="mb-1 fs-20 fw-bold">Patient Registration</h5>
                                <p class="text-muted mb-0">Complete your patient profile in two easy steps.</p>
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">Back to Login</a>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="step-pill active">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="step-number">1</span>
                                        <div>
                                            <h6 class="mb-0">General Info</h6>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">Personal details required for your patient account.</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="step-pill" id="patient-step-2-pill">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="step-number">2</span>
                                        <div>
                                            <h6 class="mb-0">Payment</h6>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">Stripe payment information.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card border-1 p-lg-4 shadow-md rounded-3 position-relative mb-4">
                            <div id="patient-loader" class="loader-overlay d-none">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                            <div id="patient-step-1" class="form-step active">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-user fs-14 text-dark"></i>
                                            </span>
                                            <input id="patient-name" type="text" class="form-control border-start-0 ps-0" placeholder="Full Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-mail fs-14 text-dark"></i>
                                            </span>
                                            <input id="patient-email" type="email" class="form-control border-start-0 ps-0" placeholder="Email Address" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-lock fs-14 text-dark"></i>
                                            </span>
                                            <input id="patient-password" type="password" class="form-control border-start-0 ps-0" placeholder="Password" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Confirm Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-lock fs-14 text-dark"></i>
                                            </span>
                                            <input id="patient-password-confirm" type="password" class="form-control border-start-0 ps-0" placeholder="Confirm Password" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input id="patient-phone" type="tel" class="form-control" placeholder="Phone Number" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Date of Birth / Age</label>
                                        <input id="patient-dob" type="text" class="form-control" placeholder="MM/DD/YYYY or Age" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Gender</label>
                                        <select id="patient-gender" class="form-select" required>
                                            <option value="">Choose...</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Blood Group</label>
                                        <select id="patient-blood-group" class="form-select" required>
                                            <option value="">Choose...</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Address <small class="text-muted">(optional)</small></label>
                                        <input id="patient-address" type="text" class="form-control" placeholder="Street, City, Country">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button id="patient-next" type="button" class="btn bg-primary text-white">Next</button>
                                </div>
                            </div>

                            <div id="patient-step-2" class="form-step">
                                <div class="row g-3">
                                    <div class="col-lg-5">
                                        <div class="payment-card text-white">
                                            <div class="card-chip"></div>
                                            <p class="mb-4">Secure payment preview</p>
                                            <h5 class="mb-1">John Patient</h5>
                                            <p class="mb-0">**** **** **** 4242</p>
                                            <div class="d-flex align-items-center justify-content-between mt-4">
                                                <small>Expiry 09/28</small>
                                                <div class="payment-card-icons">
                                                    <i class="ti ti-credit-card"></i>
                                                    <i class="ti ti-credit-card"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Cardholder Name</label>
                                                <input id="cardholder-name" type="text" class="form-control" placeholder="Full Name" required>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Card Number</label>
                                                <input id="card-number" type="text" class="form-control" placeholder="4242 4242 4242 4242" maxlength="19" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Expiry Date (MM/YY)</label>
                                                <input id="expiry-date" type="text" class="form-control" placeholder="MM/YY" maxlength="5" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">CVV</label>
                                                <input id="cvv" type="text" class="form-control" placeholder="123" maxlength="4" required>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Billing Address <small class="text-muted">(optional)</small></label>
                                                <input id="billing-address" type="text" class="form-control" placeholder="Billing Address">
                                            </div>
                                        </div>
                                        <p class="text-muted small mt-3"><i class="ti ti-lock me-2"></i>Secure payment details only used for demonstration and will not be processed.</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mt-4">
                                    <button id="patient-back" type="button" class="btn btn-outline-secondary">Back</button>
                                    <div class="d-flex gap-2">
                                        <button id="patient-skip" type="button" class="btn btn-outline-primary">Skip for Now</button>
                                        <button id="patient-submit" type="button" class="btn bg-primary text-white">Submit</button>
                                    </div>
                                </div>
                            </div>

                            <div id="patient-success" class="form-feedback text-center p-5 d-none">
                                <div class="mb-4">
                                    <span class="step-card-icon bg-success text-white"><i class="ti ti-check"></i></span>
                                </div>
                                <h5 class="fw-bold mb-2">Registration ready</h5>
                                <p class="text-muted mb-4">Your patient registration flow is complete. You can return to login and proceed.</p>
                                <a href="{{ route('login') }}" class="btn bg-primary text-white">Back to Login</a>
                            </div>
                        </div>

                    </div><!-- end col -->
                </div>
                <!-- end row -->
            </div>
        </div>
        <!-- End Content -->

        <img src="{{ asset('assets/img/auth/auth-bg-top.png') }}" alt="" class="img-fluid element-01">
        <img src="{{ asset('assets/img/auth/auth-bg-bot.png') }}" alt="" class="img-fluid element-02">

    </div>
    <!-- End Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/script.js') }}" type="text/javascript"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const step1 = document.getElementById('patient-step-1');
            const step2 = document.getElementById('patient-step-2');
            const success = document.getElementById('patient-success');
            const loader = document.getElementById('patient-loader');
            const step2Pill = document.getElementById('patient-step-2-pill');
            const nextButton = document.getElementById('patient-next');
            const backButton = document.getElementById('patient-back');
            const skipButton = document.getElementById('patient-skip');
            const submitButton = document.getElementById('patient-submit');

            function showLoader(callback) {
                loader.classList.remove('d-none');
                setTimeout(() => {
                    loader.classList.add('d-none');
                    callback();
                }, 260);
            }

            function validateStep1() {
                const requiredIds = ['patient-name', 'patient-email', 'patient-password', 'patient-password-confirm', 'patient-phone', 'patient-dob', 'patient-gender', 'patient-blood-group'];
                for (const id of requiredIds) {
                    const field = document.getElementById(id);
                    if (!field.value.trim()) {
                        field.focus();
                        return false;
                    }
                }
                // Special check for age
                const ageValue = document.getElementById('patient-dob').value;
                if (isNaN(ageValue) || parseInt(ageValue) <= 0) {
                    document.getElementById('patient-dob').focus();
                    return false;
                }
                const password = document.getElementById('patient-password').value;
                const confirm = document.getElementById('patient-password-confirm').value;
                if (password !== confirm) {
                    alert('Passwords do not match.');
                    return false;
                }
                return true;
            }

            function showStep(step) {
                step1.classList.toggle('active', step === 1);
                step2.classList.toggle('active', step === 2);
                success.classList.toggle('d-none', step !== 3);
                step2Pill.classList.toggle('active', step === 2);
            }

            nextButton.addEventListener('click', function () {
                if (!validateStep1()) return;
                showLoader(() => showStep(2));
            });

            backButton.addEventListener('click', function () {
                showLoader(() => showStep(1));
            });

            skipButton.addEventListener('click', function () {
                // Collect step 1 data
                const data = {
                    name: document.getElementById('patient-name').value,
                    email: document.getElementById('patient-email').value,
                    password: document.getElementById('patient-password').value,
                    password_confirmation: document.getElementById('patient-password-confirm').value,
                    phone: document.getElementById('patient-phone').value,
                    age: parseInt(document.getElementById('patient-dob').value) || 0,
                    gender: document.getElementById('patient-gender').value,
                    blood_group: document.getElementById('patient-blood-group').value,
                    address: document.getElementById('patient-address').value,
                    is_verified: 0,
                    is_payment_method_verified: 0
                };
                // Send AJAX POST
                fetch('{{ url("/register/patient") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        window.location.href = '/login';
                    } else {
                        alert('Registration failed: ' + (result.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during registration.');
                });
            });

            submitButton.addEventListener('click', function () {
                const requiredIds = ['cardholder-name', 'card-number', 'expiry-date', 'cvv'];
                for (const id of requiredIds) {
                    const field = document.getElementById(id);
                    if (!field.value.trim()) {
                        field.focus();
                        return;
                    }
                }
                // Collect step 1 data
                const data = {
                    name: document.getElementById('patient-name').value,
                    email: document.getElementById('patient-email').value,
                    password: document.getElementById('patient-password').value,
                    password_confirmation: document.getElementById('patient-password-confirm').value,
                    phone: document.getElementById('patient-phone').value,
                    age: parseInt(document.getElementById('patient-dob').value) || 0,
                    gender: document.getElementById('patient-gender').value,
                    blood_group: document.getElementById('patient-blood-group').value,
                    address: document.getElementById('patient-address').value,
                    is_verified: 0,
                    is_payment_method_verified: 1
                };
                // Send AJAX POST
                fetch('{{ url("/register/patient") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        window.location.href = '/login';
                    } else {
                        alert('Registration failed: ' + (result.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during registration.');
                });
            });

            document.getElementById('card-number').addEventListener('input', function (e) {
                e.target.value = e.target.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
            });
            document.getElementById('expiry-date').addEventListener('input', function (e) {
                e.target.value = e.target.value.replace(/\D/g, '').replace(/^(\d{2})(\d{1,2})$/, '$1/$2');
            });
            document.getElementById('cvv').addEventListener('input', function (e) {
                e.target.value = e.target.value.replace(/\D/g, '');
            });
        });
    </script>

</body>
</html>
