@extends('layouts.layout')

@section('title', 'Patient Profile')

@section('content')

<div class="content" id="profilePage">

    <!-- Page Header -->
    <div class="mb-3 border-bottom pb-3">
        <h4 class="fw-bold mb-0">Profile Settings</h4>
    </div>
    <!-- End Page Header -->

    <div class="card">
        <div class="card-body p-0">
            <div class="settings-wrapper d-flex">

                <div class="card flex-fill mb-0 border-0 bg-light-500 shadow-none">
                    <div class="card-header border-bottom px-0 mx-3">
                        <h5 class="fw-bold">Basic Information</h5>
                    </div>
                    <div class="card-body px-0 mx-3">
                        <form action="{{ route('profile.update', $patient->user->uuid) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- start row -->
                            <div class="row border-bottom mb-3">
                                <div class="col-lg-12">

                                    <!-- Profile Image -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-lg-2">
                                            <label class="form-label mb-0">Profile Image<span class="text-danger ms-1">*</span></label>
                                        </div>
                                        <div class="col-lg-10">
                                            <div class="profile-container">
                                                {{-- <img src="{{ $patient->user->profile_image_url ?? '' }}" alt="Profile" id="profilePreview"> --}}
                                                <div class="overlay-btn">
                                                    <a href="javascript:void(0);" class="text-white" id="uploadTrigger">
                                                        <i class="ti ti-photo fs-10"></i>
                                                    </a>
                                                </div>
                                                <input type="file" name="profile_image" id="profileUpload" accept="image/*" style="display: none;">
                                            </div>
                                            @error('profile_image')
                                                <div class="text-danger small mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- end row -->

                                </div>
                                <div class="col-lg-6">

                                    <!-- Name Field -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-lg-4">
                                            <label class="form-label mb-0">Name<span class="text-danger ms-1">*</span></label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" value="{{ $patient->user->name }}" disabled>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6">

                                    <!-- Email Field -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-lg-4">
                                            <label class="form-label mb-0">Email<span class="text-danger ms-1">*</span></label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="email" class="form-control" value="{{ $patient->user->email }}" disabled>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6">

                                    <!-- Phone Field -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-lg-4">
                                            <label class="form-label mb-0">Phone Number<span class="text-danger ms-1">*</span></label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="phone" value="{{ $patient->phone }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6">

                                    <!-- Age Field -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-lg-4">
                                            <label class="form-label mb-0">Age<span class="text-danger ms-1">*</span></label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="number" class="form-control" name="age" value="{{ $patient->age }}" min="0" max="150">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- end row -->

                            <!-- Medical Information -->
                            <div class="row border-bottom mb-3">
                                <div class="mb-3">
                                    <h5 class="fw-bold mb-0">Medical Information</h5>
                                </div>
                                <div class="col-lg-6">

                                    <!-- Gender Field -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-lg-4">
                                            <label class="form-label mb-0">Gender</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control" name="gender">
                                                <option value="male" {{ $patient->gender === 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ $patient->gender === 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ $patient->gender === 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6">

                                    <!-- Blood Group Field -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-lg-4">
                                            <label class="form-label mb-0">Blood Group</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control" name="blood_group">
                                                <option value="A+" {{ $patient->blood_group === 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ $patient->blood_group === 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ $patient->blood_group === 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ $patient->blood_group === 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="AB+" {{ $patient->blood_group === 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ $patient->blood_group === 'AB-' ? 'selected' : '' }}>AB-</option>
                                                <option value="O+" {{ $patient->blood_group === 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ $patient->blood_group === 'O-' ? 'selected' : '' }}>O-</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6">

                                    <!-- DOB Field -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-lg-4">
                                            <label class="form-label mb-0">Date of Birth</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="date" class="form-control" name="dob" value="{{ $patient->dob }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6">

                                    <!-- Verification Status -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-lg-4">
                                            <label class="form-label mb-0">Verification Status</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <span class="badge bg-{{ $patient->is_verified ? 'success' : 'warning' }}">
                                                {{ $patient->is_verified ? 'Verified' : 'Not Verified' }}
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- end row -->

                            <!-- Address Information -->
                            <div class="row border-bottom mb-3">
                                <div class="mb-3">
                                    <h5 class="fw-bold mb-0">Address Information</h5>
                                </div>
                                <div class="col-lg-12">

                                    <!-- Address Field -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-lg-2">
                                            <label class="form-label mb-0">Address</label>
                                        </div>
                                        <div class="col-lg-10">
                                            <textarea class="form-control" name="address" rows="3">{{ $patient->address }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- end row -->

                            <div class="d-flex align-items-center justify-content-end">
                                <a href="{{ route('patients.index') }}" class="btn btn-light me-3">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const trigger = document.getElementById('uploadTrigger');
        const input = document.getElementById('profileUpload');
        const preview = document.getElementById('profilePreview');

        if (!trigger || !input || !preview) {
            return;
        }

        trigger.addEventListener('click', function () {
            input.click();
        });

        input.addEventListener('change', function (event) {
            const [file] = event.target.files;

            if (!file) {
                return;
            }

            preview.src = URL.createObjectURL(file);
        });
    });
</script>
@endpush
