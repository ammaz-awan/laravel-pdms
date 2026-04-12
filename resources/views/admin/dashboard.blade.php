@extends('layouts.layout')

@section('title', 'Admin Dashboard')

@section('content')

<!-- Page Header -->
<div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h4 class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}!</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap gap-2">
        {{-- <a href="{{ route('appointments.index') }}" class="btn btn-primary d-inline-flex align-items-center">
            <i class="ti ti-plus me-1"></i> Appointments
        </a> --}}
        {{-- <a href="" class="btn btn-outline-white bg-white d-inline-flex align-items-center">
            <i class="ti ti-users me-1"></i>Manage Admins
        </a> --}}
    </div>
</div>
<!-- End Page Header -->

<!-- Dashboard Statistics -->
<div class="row mb-4">
    <!-- Total Doctors Card -->
    <div class="col-xl-3 col-md-6">
        <div class="position-relative border card rounded-2 shadow-sm">
            <img src="{{ asset('assets/img/bg/bg-01.svg') }}" alt="bg" class="position-absolute start-0 top-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2 justify-content-between">
                    <span class="avatar bg-primary rounded-circle"><i class="ti ti-user-plus fs-24"></i></span>
                    <div class="text-end">
                        <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-success">+12%</span>
                        <p class="fs-13 mb-0">This Month</p>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 fw-semibold">Total Doctors</p>
                        <h3 class="fw-bold mb-0">{{ \App\Models\Doctor::count() }}</h3>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="ti ti-arrow-right me-1"></i>View Doctors
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Patients Card -->
    <div class="col-xl-3 col-md-6">
        <div class="position-relative border card rounded-2 shadow-sm">
            <img src="{{ asset('assets/img/bg/bg-02.svg') }}" alt="bg" class="position-absolute start-0 top-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2 justify-content-between">
                    <span class="avatar bg-danger rounded-circle"><i class="ti ti-user-heart fs-24"></i></span>
                    <div class="text-end">
                        <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-success">+8%</span>
                        <p class="fs-13 mb-0">This Month</p>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 fw-semibold">Total Patients</p>
                        <h3 class="fw-bold mb-0">{{ \App\Models\Patient::count() }}</h3>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('patients.index') }}" class="btn btn-sm btn-outline-danger w-100">
                        <i class="ti ti-arrow-right me-1"></i>View Patients
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Appointments Card -->
    <div class="col-xl-3 col-md-6">
        <div class="position-relative border card rounded-2 shadow-sm">
            <img src="{{ asset('assets/img/bg/bg-03.svg') }}" alt="bg" class="position-absolute start-0 top-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2 justify-content-between">
                    <span class="avatar bg-info rounded-circle"><i class="ti ti-calendar-check fs-24"></i></span>
                    <div class="text-end">
                        <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-warning">-5%</span>
                        <p class="fs-13 mb-0">This Month</p>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 fw-semibold">Total Appointments</p>
                        <h3 class="fw-bold mb-0">{{ \App\Models\Appointment::count() }}</h3>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-info w-100">
                        <i class="ti ti-arrow-right me-1"></i>View Appointments
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Revenue Card -->
    <div class="col-xl-3 col-md-6">
        <div class="position-relative border card rounded-2 shadow-sm">
            <img src="{{ asset('assets/img/bg/bg-04.svg') }}" alt="bg" class="position-absolute start-0 top-0">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2 justify-content-between">
                    <span class="avatar bg-success rounded-circle"><i class="ti ti-cards fs-24"></i></span>
                    <div class="text-end">
                        <span class="badge px-2 py-1 fs-12 fw-medium d-inline-flex mb-1 bg-success">+15%</span>
                        <p class="fs-13 mb-0">This Month</p>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="mb-1 fw-semibold">Total Revenue</p>
                        <h3 class="fw-bold mb-0">${{ number_format(\App\Models\Payment::sum('amount') ?? 0, 2) }}</h3>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-success w-100">
                        <i class="ti ti-arrow-right me-1"></i>View Payments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Dashboard Statistics -->

<!-- Quick Actions & Recent Activities -->
<div class="row mb-4">
     <!-- Quick Actions -->
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="fw-bold mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    {{-- <div class="col">
                        <a href="{{ route('doctors.create') }}" class="btn btn-light border d-flex align-items-center justify-content-between p-3 rounded-2">
                            <div>
                                <h6 class="fw-semibold mb-0">Add Doctor</h6>
                                <small class="text-muted">Register new doctor</small>
                            </div>
                            <i class="ti ti-arrow-right fs-18"></i>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('patients.create') }}" class="btn btn-light border d-flex align-items-center justify-content-between p-3 rounded-2">
                            <div>
                                <h6 class="fw-semibold mb-0">Add Patient</h6>
                                <small class="text-muted">Register new patient</small>
                            </div>
                            <i class="ti ti-arrow-right fs-18"></i>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('appointments.create') }}" class="btn btn-light border d-flex align-items-center justify-content-between p-3 rounded-2">
                            <div>
                                <h6 class="fw-semibold mb-0">Schedule Appointment</h6>
                                <small class="text-muted">Create new appointment</small>
                            </div>
                            <i class="ti ti-arrow-right fs-18"></i>
                        </a>
                    </div>
                    <div class="col">
                        <a href="{{ route('invoices.create') }}" class="btn btn-light border d-flex align-items-center justify-content-between p-3 rounded-2">
                            <div>
                                <h6 class="fw-semibold mb-0">Create Invoice</h6>
                                <small class="text-muted">Generate new invoice</small>
                            </div>
                            <i class="ti ti-arrow-right fs-18"></i>
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div> 



  

<div class="col-lg-6">
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="fw-bold mb-0">System Overview</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-semibold">Completed Appointments</span>
                    <span class="badge bg-success">{{ \App\Models\Appointment::where('status', 'completed')->count() }}</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: {{ (\App\Models\Appointment::where('status', 'completed')->count() / (\App\Models\Appointment::count() ?: 1)) * 100 }}%"></div>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-semibold">Pending Appointments</span>
                    <span class="badge bg-warning">{{ \App\Models\Appointment::where('status', 'pending')->count() }}</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-warning" style="width: {{ (\App\Models\Appointment::where('status', 'pending')->count() / (\App\Models\Appointment::count() ?: 1)) * 100 }}%"></div>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-semibold">Cancelled Appointments</span>
                    <span class="badge bg-danger">{{ \App\Models\Appointment::where('status', 'cancelled')->count() }}</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-danger" style="width: {{ (\App\Models\Appointment::where('status', 'cancelled')->count() / (\App\Models\Appointment::count() ?: 1)) * 100 }}%"></div>
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-semibold">Outstanding Invoices</span>
                    <span class="badge bg-info">{{ \App\Models\Invoice::where('status', 'unpaid')->count() }}</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-info" style="width: {{ (\App\Models\Invoice::where('status', 'unpaid')->count() / (\App\Models\Invoice::count() ?: 1)) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End Quick Actions & Recent Activities -->

<!-- Recent Appointments -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0">Recent Appointments</h5>
                <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3">Patient</th>
                            <th class="py-3">Doctor</th>
                            <th class="py-3">Date & Time</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Appointment::latest()->limit(5)->get() as $appointment)
                            <tr>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar bg-light-primary rounded-circle me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                            {{ strtoupper(substr($appointment->patient->name ?? 'N/A', 0, 1)) }}
                                        </span>
                                        <span>{{ $appointment->patient->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="py-3">{{ $appointment->doctor->name ?? 'N/A' }}</td>
                                <td class="py-3">
                                    @if($appointment->appointment_date)
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y h:i A') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="py-3">
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            'rescheduled' => 'info'
                                        ];
                                        $statusColor = $statusColors[$appointment->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">{{ ucfirst($appointment->status) }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-primary" title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-outline-secondary" title="Edit">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <p class="text-muted">No appointments found</p>
                                    <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-primary">Create First Appointment</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Management Links -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="fw-bold mb-0">Management Sections</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href" " class="card text-decoration-none border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <span class="avatar bg-light-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="ti ti-users fs-20 text-primary"></i>
                                </span>
                                <h6 class="fw-semibold mb-1">Admins</h6>
                                <p class="text-muted mb-0 fs-13">{{ \App\Models\Admin::count() }} Total</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('doctors.index') }}" class="card text-decoration-none border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <span class="avatar bg-light-success rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="ti ti-user-plus fs-20 text-success"></i>
                                </span>
                                <h6 class="fw-semibold mb-1">Doctors</h6>
                                <p class="text-muted mb-0 fs-13">{{ \App\Models\Doctor::count() }} Total</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('patients.index') }}" class="card text-decoration-none border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <span class="avatar bg-light-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="ti ti-user-heart fs-20 text-danger"></i>
                                </span>
                                <h6 class="fw-semibold mb-1">Patients</h6>
                                <p class="text-muted mb-0 fs-13">{{ \App\Models\Patient::count() }} Total</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('payments.index') }}" class="card text-decoration-none border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <span class="avatar bg-light-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="ti ti-cards fs-20 text-warning"></i>
                                </span>
                                <h6 class="fw-semibold mb-1">Payments</h6>
                                <p class="text-muted mb-0 fs-13">{{ \App\Models\Payment::count() }} Total</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('prescriptions.index') }}" class="card text-decoration-none border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <span class="avatar bg-light-info rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="ti ti-prescription fs-20 text-info"></i>
                                </span>
                                <h6 class="fw-semibold mb-1">Prescriptions</h6>
                                <p class="text-muted mb-0 fs-13">{{ \App\Models\Prescription::count() }} Total</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('invoices.index') }}" class="card text-decoration-none border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <span class="avatar bg-light-success rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="ti ti-file-invoice fs-20 text-success"></i>
                                </span>
                                <h6 class="fw-semibold mb-1">Invoices</h6>
                                <p class="text-muted mb-0 fs-13">{{ \App\Models\Invoice::count() }} Total</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('ratings.index') }}" class="card text-decoration-none border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <span class="avatar bg-light-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="ti ti-star fs-20 text-primary"></i>
                                </span>
                                <h6 class="fw-semibold mb-1">Ratings</h6>
                                <p class="text-muted mb-0 fs-13">{{ \App\Models\Rating::count() }} Total</p>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('appointments.index') }}" class="card text-decoration-none border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                <span class="avatar bg-light-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                    <i class="ti ti-calendar-check fs-20 text-danger"></i>
                                </span>
                                <h6 class="fw-semibold mb-1">Appointments</h6>
                                <p class="text-muted mb-0 fs-13">{{ \App\Models\Appointment::count() }} Total</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

