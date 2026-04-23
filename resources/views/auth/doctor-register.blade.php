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
        .password-toggle {
            color: #6c757d;
            border-left: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .password-toggle:focus {
            box-shadow: none;
        }
        .input-group.is-invalid-group > .input-group-text,
        .input-group.is-invalid-group > .form-control,
        .input-group.is-invalid-group > .form-select,
        .input-group.is-invalid-group > .password-toggle {
            border-color: var(--bs-form-invalid-border-color);
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
                            <a href="{{ route('login') }}" class="btn btn-success text-white"><--- Back to Login</a>
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

           
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">

                            <!-- Facebook Button -->
                            <a href="{{ url('/auth/facebook?role=doctor') }}"
                            class="btn social-btn fb-btn d-flex align-items-center justify-content-center px-5 py-3 rounded-pill"
                            style="min-width: 260px;">
                                <img src="/assets/img/icons/facebook-logo.svg" class="me-2 bg-white rounded-circle p-1" width="24" alt="Facebook">
                                <span class="fw-medium">Continue with Facebook</span>
                            </a>
                            
                            <!-- Google Button -->
                            <a href="{{ url('/auth/google?role=doctor') }}"
                            class="btn social-btn google-btn d-flex align-items-center justify-content-center px-5 py-3 rounded-pill"
                            style="min-width: 260px;">
                                <img src="/assets/img/icons/google-logo.svg" class="me-2 bg-white rounded-circle p-1" width="24" alt="Google">
                                <span class="fw-medium">Continue with Google</span>
                            </a>

                        </div>

                        <!-- Divider -->
                        <div class="d-flex align-items-center my-3">
                            <hr class="flex-grow-1">
                            <span class="mx-2 text-muted">or</span>
                            <hr class="flex-grow-1">
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
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-user fs-14 text-dark"></i>
                                            </span>
                                            <input id="doctor-name" type="text" class="form-control border-start-0 ps-0" placeholder="Full Name" required>
                                            <div class="invalid-feedback">This field is required</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-mail fs-14 text-dark"></i>
                                            </span>
                                            <input id="doctor-email" type="email" class="form-control border-start-0 ps-0" placeholder="Email Address" required>
                                            <div class="invalid-feedback">This field is required</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Password</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-lock fs-14 text-dark"></i>
                                            </span>
                                            <input id="doctor-password" type="password" class="form-control border-start-0 border-end-0 ps-0" placeholder="Password" required>
                                            <button class="input-group-text bg-white password-toggle" type="button" data-password-toggle="doctor-password" aria-label="Toggle password visibility">
                                                <i class="ti ti-eye-off"></i>
                                            </button>
                                            <div class="invalid-feedback">This field is required</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Confirm Password</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-lock fs-14 text-dark"></i>
                                            </span>
                                            <input id="doctor-password-confirm" type="password" class="form-control border-start-0 border-end-0 ps-0" placeholder="Confirm Password" required>
                                            <button class="input-group-text bg-white password-toggle" type="button" data-password-toggle="doctor-password-confirm" aria-label="Toggle confirm password visibility">
                                                <i class="ti ti-eye-off"></i>
                                            </button>
                                            <div class="invalid-feedback">This field is required</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-phone fs-14 text-dark"></i>
                                            </span>
                                            <input id="doctor-phone" type="tel" class="form-control border-start-0 ps-0" placeholder="Phone Number" required>
                                            <div class="invalid-feedback">This field is required</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Specialization</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-stethoscope fs-14 text-dark"></i>
                                            </span>
                                            <input id="doctor-specialization" type="text" class="form-control border-start-0 ps-0" placeholder="Specialization" required>
                                            <div class="invalid-feedback">This field is required</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Experience</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-briefcase fs-14 text-dark"></i>
                                            </span>
                                            <input id="doctor-experience" type="text" class="form-control border-start-0 ps-0" placeholder="Years of experience" required>
                                            <div class="invalid-feedback">This field is required</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Consultation Fees</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-currency-dollar fs-14 text-dark"></i>
                                            </span>
                                            <input id="doctor-fees" type="number" step="0.01" class="form-control border-start-0 ps-0" placeholder="Fees in USD" required>
                                            <div class="invalid-feedback">This field is required</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Clinic / Hospital Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-building-hospital fs-14 text-dark"></i>
                                            </span>
                                            <input id="doctor-clinic" type="text" class="form-control border-start-0 ps-0" placeholder="Clinic or Hospital" required>
                                            <div class="invalid-feedback">This field is required</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-map-pin fs-14 text-dark"></i>
                                            </span>
                                            <input id="doctor-address" type="text" class="form-control border-start-0 ps-0" placeholder="Clinic address" required>
                                            <div class="invalid-feedback">This field is required</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button id="doctor-next" type="button" class="btn bg-success text-white">Next</button>
                                </div>
                            </div>

                            <div id="doctor-step-2" class="form-step">
                                <div class="row g-3">
                                   <div class="col-12">
                                            <label class="form-label">Upload Certificate (PDF / Image)</label>

                                            <div class="file-drop d-flex align-items-center justify-content-between">
                                                <span class="text-muted">Upload MBBS / License / Registration Certificate</span>
                                                <span class="badge bg-success">Required</span>
                                            </div>

                                            <div class="input-group mt-3">
                                                <span class="input-group-text border-end-0 bg-white">
                                                    <i class="ti ti-file-text fs-14 text-dark"></i>
                                                </span>

                                                <input 
                                                    id="doctor-certificate"
                                                    name="certificate"
                                                    type="file"
                                                    class="form-control border-start-0 ps-0"
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    required
                                                >
                                            </div>

                                            <small class="text-muted">
                                                Allowed formats: PDF, JPG, PNG (Max 5MB)
                                            </small>
                                        </div>
                                    <div class="col-md-12">
                                        <label class="form-label">License Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-end-0 bg-white">
                                                <i class="ti ti-id-badge fs-14 text-dark"></i>
                                            </span>
                                                <input 
                                                    id="doctor-license"
                                                    name="license_number"
                                                    type="text"
                                                    class="form-control border-start-0 ps-0"
                                                    placeholder="License Number"
                                                    required>
                                                                                              
                                    <div class="invalid-feedback">This field is required</div>
                                        </div>
                                    </div>
                                
                                </div>
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mt-4">
                                    <button id="doctor-back" type="button" class="btn btn-outline-success">Back</button>
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

             document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
                button.addEventListener('click', function () {
                    togglePassword(button.dataset.passwordToggle, button.querySelector('i'));
                });
            });

            ['doctor-name', 'doctor-email', 'doctor-password', 'doctor-password-confirm', 'doctor-phone', 'doctor-specialization', 'doctor-experience', 'doctor-fees', 'doctor-clinic', 'doctor-address', 'doctor-license'].forEach(function (id) {
                const field = document.getElementById(id);
                const eventName = field.tagName === 'SELECT' ? 'change' : 'input';

                field.addEventListener(eventName, function () {
                    clearInvalid(field);
                });
            });

            function showLoader(callback) {
                loader.classList.remove('d-none');
                setTimeout(() => {
                    loader.classList.add('d-none');
                    callback();
                }, 260);
            }

            function setInvalid(field, message) {
                field.classList.add('is-invalid');
                const inputGroup = field.closest('.input-group');
                if (inputGroup) {
                    inputGroup.classList.add('is-invalid-group');
                }
                const feedback = field.parentElement.querySelector('.invalid-feedback') || field.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = message;
                }
            }

            function clearInvalid(field) {
                field.classList.remove('is-invalid');
                const inputGroup = field.closest('.input-group');
                if (inputGroup) {
                    inputGroup.classList.remove('is-invalid-group');
                }
                const feedback = field.parentElement.querySelector('.invalid-feedback') || field.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = 'This field is required';
                }
            }

            function togglePassword(fieldId, iconElement) {
                const field = document.getElementById(fieldId);
                const showPassword = field.type === 'password';

                field.type = showPassword ? 'text' : 'password';
                iconElement.classList.toggle('ti-eye', showPassword);
                iconElement.classList.toggle('ti-eye-off', !showPassword);
            }

            function validateRequiredFields(fieldIds) {
                let isValid = true;

                fieldIds.forEach(function (id) {
                    const field = document.getElementById(id);

                    if (!field.value.trim()) {
                        setInvalid(field, 'This field is required');
                        if (isValid) {
                            field.focus();
                        }
                        isValid = false;
                    } else {
                        clearInvalid(field);
                    }
                });

                return isValid;
            }

            function validateStep1() {
                const requiredIds = ['doctor-name', 'doctor-email', 'doctor-password', 'doctor-password-confirm', 'doctor-phone', 'doctor-specialization', 'doctor-experience', 'doctor-fees', 'doctor-clinic', 'doctor-address'];
                if (!validateRequiredFields(requiredIds)) {
                    return false;
                }

                const password = document.getElementById('doctor-password').value;
                const confirmField = document.getElementById('doctor-password-confirm');
                const confirm = confirmField.value;

                if (password !== confirm) {
                    setInvalid(confirmField, 'Passwords do not match');
                    confirmField.focus();
                    return false;
                }

                clearInvalid(confirmField);
                return true;
            }

            function validateStep2() {
                const requiredIds = ['doctor-license'];
                return validateRequiredFields(requiredIds);
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

                const formData = new FormData();

                formData.append('name', document.getElementById('doctor-name').value);
                formData.append('email', document.getElementById('doctor-email').value);
                formData.append('password', document.getElementById('doctor-password').value);
                formData.append('password_confirmation', document.getElementById('doctor-password-confirm').value);
                formData.append('phone', document.getElementById('doctor-phone').value);
                formData.append('specialization', document.getElementById('doctor-specialization').value);
                formData.append('experience', document.getElementById('doctor-experience').value);
                formData.append('fees', document.getElementById('doctor-fees').value);
                formData.append('clinic_name', document.getElementById('doctor-clinic').value);
                formData.append('address', document.getElementById('doctor-address').value);

                formData.append('skip', 1);

              fetch('{{ url("/register/doctor") }}', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
    },
    body: formData
})
.then(async res => {
    const data = await res.json();

    if (!res.ok) {
        console.log('Validation Error:', data);
        alert(JSON.stringify(data.errors || data.message));
        return;
    }

    if (data.success) {
        window.location.href = '/login';
    }
})
.catch(err => {
    console.error(err);
});
            });;

           submitButton.addEventListener('click', function () {
                 if (!validateStep2()) return;

                    const formData = new FormData();

                    // Step 1
                    formData.append('name', document.getElementById('doctor-name').value);
                    formData.append('email', document.getElementById('doctor-email').value);
                    formData.append('password', document.getElementById('doctor-password').value);
                    formData.append('password_confirmation', document.getElementById('doctor-password-confirm').value);
                    formData.append('phone', document.getElementById('doctor-phone').value);
                    formData.append('specialization', document.getElementById('doctor-specialization').value);
                    formData.append('experience', document.getElementById('doctor-experience').value);
                    formData.append('fees', document.getElementById('doctor-fees').value);
                    formData.append('clinic_name', document.getElementById('doctor-clinic').value);
                    formData.append('address', document.getElementById('doctor-address').value);

                    // Step 2
                    formData.append('license_number', document.getElementById('doctor-license').value);

                    // ❗ FIX: DO NOT set is_verified manually
                    // backend handles it

                    const fileInput = document.getElementById('doctor-certificate');
                    if (fileInput.files.length > 0) {
                        formData.append('certificate', fileInput.files[0]);
                    }

                    fetch('{{ url("/register/doctor") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(result => {
                        if (result.success) {
                            window.location.href = '/login';
                        } else {
                            alert(result.message || 'Registration failed');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Something went wrong');
                    });
         });

           
            });
    </script>

</body>
</html>
