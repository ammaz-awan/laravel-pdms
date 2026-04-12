<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Doctor Registration | PDMS</title>
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
        .step-pill {
            border-radius: 1rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
            transition: border-color .2s ease, background .2s ease;
        }
        .step-pill.active {
            background: rgba(25, 135, 84, .08);
            border-color: #198754;
        }
        .step-number {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #e6f4ea;
            color: #198754;
            font-weight: 700;
            margin-right: .75rem;
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        .file-drop {
            border: 1px dashed #ced4da;
            border-radius: 1rem;
            padding: 1.25rem;
            transition: border-color .2s ease, background .2s ease;
        }
        .file-drop:hover {
            border-color: #198754;
            background: rgba(25, 135, 84, .03);
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
                                <h5 class="mb-1 fs-20 fw-bold">Doctor Registration</h5>
                                <p class="text-muted mb-0">Submit your details and verification documents for review.</p>
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">Back to Login</a>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="step-pill active">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="step-number">1</span>
                                        <div>
                                            <h6 class="mb-0">Doctor Info</h6>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">Professional details for your account setup.</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="step-pill" id="doctor-step-2-pill">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="step-number">2</span>
                                        <div>
                                            <h6 class="mb-0">Certificate Verification</h6>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">Upload documentation for admin approval.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card border-1 p-lg-4 shadow-md rounded-3 position-relative mb-4">
                            <div id="doctor-loader" class="loader-overlay d-none">
                                <div class="spinner-border text-success" role="status"></div>
                            </div>
                            <div id="doctor-step-1" class="form-step active">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Full Name</label>
                                        <input id="doctor-name" type="text" class="form-control" placeholder="Full Name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input id="doctor-email" type="email" class="form-control" placeholder="Email Address" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Password</label>
                                        <input id="doctor-password" type="password" class="form-control" placeholder="Password" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Confirm Password</label>
                                        <input id="doctor-password-confirm" type="password" class="form-control" placeholder="Confirm Password" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input id="doctor-phone" type="tel" class="form-control" placeholder="Phone Number" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Specialization</label>
                                        <input id="doctor-specialization" type="text" class="form-control" placeholder="Specialization" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Experience</label>
                                        <input id="doctor-experience" type="text" class="form-control" placeholder="Years of experience" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Consultation Fees</label>
                                        <input id="doctor-fees" type="number" step="0.01" class="form-control" placeholder="Fees in USD" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Clinic / Hospital Name</label>
                                        <input id="doctor-clinic" type="text" class="form-control" placeholder="Clinic or Hospital" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Address</label>
                                        <input id="doctor-address" type="text" class="form-control" placeholder="Clinic address" required>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button id="doctor-next" type="button" class="btn bg-success text-white">Next</button>
                                </div>
                            </div>

                            <div id="doctor-step-2" class="form-step">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Upload Certificate</label>
                                        <div class="file-drop d-flex align-items-center justify-content-between">
                                            <span class="text-muted">Drag & drop or click to upload certificate</span>
                                            <span class="badge bg-success">Accepted</span>
                                        </div>
                                        <input id="doctor-certificate" type="file" class="form-control mt-3">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">License Number</label>
                                        <input id="doctor-license" type="text" class="form-control" placeholder="License Number" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Additional Documents <small class="text-muted">(optional)</small></label>
                                        <input id="doctor-documents" type="text" class="form-control" placeholder="Document link or notes">
                                    </div>
                                </div>
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mt-4">
                                    <button id="doctor-back" type="button" class="btn btn-outline-secondary">Back</button>
                                    <div class="d-flex gap-2">
                                        <button id="doctor-skip" type="button" class="btn btn-outline-success">Skip for Now</button>
                                        <button id="doctor-submit" type="button" class="btn bg-success text-white">Submit</button>
                                    </div>
                                </div>
                            </div>

                            <div id="doctor-pending" class="form-feedback text-center p-5 d-none">
                                <div class="mb-4">
                                    <span class="step-number bg-success text-white"><i class="ti ti-clock"></i></span>
                                </div>
                                <h5 class="fw-bold mb-2">Pending Approval</h5>
                                <p class="text-muted mb-4">Your account is under review. Please wait for admin approval before logging in.</p>
                                <a href="{{ route('login') }}" class="btn bg-success text-white">Back to Login</a>
                            </div>
                        </div>

                        <p class="text-center text-muted">Responsive doctor onboarding UI with review status and optional certificate skip.</p>
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
            const step1 = document.getElementById('doctor-step-1');
            const step2 = document.getElementById('doctor-step-2');
            const pending = document.getElementById('doctor-pending');
            const loader = document.getElementById('doctor-loader');
            const step2Pill = document.getElementById('doctor-step-2-pill');
            const nextButton = document.getElementById('doctor-next');
            const backButton = document.getElementById('doctor-back');
            const skipButton = document.getElementById('doctor-skip');
            const submitButton = document.getElementById('doctor-submit');

            function showLoader(callback) {
                loader.classList.remove('d-none');
                setTimeout(() => {
                    loader.classList.add('d-none');
                    callback();
                }, 260);
            }

            function validateStep1() {
                const requiredIds = ['doctor-name', 'doctor-email', 'doctor-password', 'doctor-password-confirm', 'doctor-phone', 'doctor-specialization', 'doctor-experience', 'doctor-fees', 'doctor-clinic', 'doctor-address'];
                for (const id of requiredIds) {
                    const field = document.getElementById(id);
                    if (!field.value.trim()) {
                        field.focus();
                        return false;
                    }
                }
                const password = document.getElementById('doctor-password').value;
                const confirm = document.getElementById('doctor-password-confirm').value;
                if (password !== confirm) {
                    alert('Passwords do not match.');
                    return false;
                }
                return true;
            }

            function validateStep2() {
                const requiredIds = ['doctor-license'];
                for (const id of requiredIds) {
                    const field = document.getElementById(id);
                    if (!field.value.trim()) {
                        field.focus();
                        return false;
                    }
                }
                return true;
            }

            function showStep(step) {
                step1.classList.toggle('active', step === 1);
                step2.classList.toggle('active', step === 2);
                pending.classList.toggle('d-none', step !== 3);
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
                    name: document.getElementById('doctor-name').value,
                    email: document.getElementById('doctor-email').value,
                    password: document.getElementById('doctor-password').value,
                    password_confirmation: document.getElementById('doctor-password-confirm').value,
                    phone: document.getElementById('doctor-phone').value,
                    specialization: document.getElementById('doctor-specialization').value,
                    experience: document.getElementById('doctor-experience').value,
                    fees: parseFloat(document.getElementById('doctor-fees').value) || 0,
                    clinic_name: document.getElementById('doctor-clinic').value,
                    address: document.getElementById('doctor-address').value,
                    is_verified: 0
                };


               fetch('{{ url("/register/doctor") }}', {
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
                if (!validateStep2()) return;
                // Collect step 1 data
                const data = {
                    name: document.getElementById('doctor-name').value,
                    email: document.getElementById('doctor-email').value,
                    password: document.getElementById('doctor-password').value,
                    password_confirmation: document.getElementById('doctor-password-confirm').value,
                    phone: document.getElementById('doctor-phone').value,
                    specialization: document.getElementById('doctor-specialization').value,
                    experience: document.getElementById('doctor-experience').value,
                    fees: parseFloat(document.getElementById('doctor-fees').value) || 0,
                    clinic_name: document.getElementById('doctor-clinic').value,
                    address: document.getElementById('doctor-address').value,
                    is_verified: 1
                };
                // Send AJAX POST
                fetch('{{ url("/register/doctor") }}', {
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
        });
    </script>

</body>
</html>
