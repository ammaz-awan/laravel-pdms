@extends('layouts.layout')

@section('title', 'Doctor Dashboard')

@section('content')
<div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h4 class="fw-bold mb-0">Doctor Dashboard</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap gap-2">
       <a href="{{ route('doctor.appointments') }}" class="btn btn-primary d-inline-flex align-items-center"><i class="ti ti-calendar-check me-1"></i>My Appointments</a>
       <a href="{{ route('doctor.my-patients') }}" class="btn btn-outline-white bg-white d-inline-flex align-items-center"><i class="ti ti-user-heart me-1"></i>My Patients</a>
       <a href="#availability-card" class="btn btn-outline-white bg-white d-inline-flex align-items-center"><i class="ti ti-calendar-time me-1"></i>Schedule Availability</a>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 d-flex">
        <div class="card shadow-sm flex-fill w-100">
           <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <p class="mb-1">Total Appointments</p>
                        <h3 class="fw-bold mb-0">{{ $stats['total_appointments'] ?? 0 }}</h3>
                    </div>
                    <span class="avatar border border-primary text-primary rounded-2 flex-shrink-0"><i class="ti ti-calendar-heart fs-20"></i></span>
                </div>
           </div>
        </div>
    </div>
    <div class="col-xl-4 d-flex">
        <div class="card shadow-sm flex-fill w-100">
           <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <p class="mb-1">Pending Appointments</p>
                        <h3 class="fw-bold mb-0">{{ $stats['pending_appointments'] ?? 0 }}</h3>
                    </div>
                    <span class="avatar border border-warning text-warning rounded-2 flex-shrink-0"><i class="ti ti-hourglass fs-20"></i></span>
                </div>
           </div>
        </div>
    </div>
    <div class="col-xl-4 d-flex">
        <div class="card shadow-sm flex-fill w-100">
           <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <p class="mb-1">Approved Appointments</p>
                        <h3 class="fw-bold mb-0">{{ $stats['approved_appointments'] ?? 0 }}</h3>
                    </div>
                    <span class="avatar border border-success text-success rounded-2 flex-shrink-0"><i class="ti ti-check fs-20"></i></span>
                </div>
           </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-7 d-flex">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0">Recent Appointments</h5>
                <a href="{{ route('doctor.appointments') }}" class="btn btn-sm btn-light">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive table-nowrap">
                    <table class="table border align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Patient</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Fee</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($stats['recent_appointments'] ?? collect()) as $appointment)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="fs-14 mb-1">{{ $appointment->patient->user->name ?? 'Patient' }}</h6>
                                            <p class="mb-0 fs-13">{{ $appointment->patient->phone ?? 'N/A' }}</p>
                                        </div>
                                    </td>
                                    <td>{{ $appointment->appointment_date?->format('d M Y') }} - {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $appointment->status === 'approved' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($appointment->fee_snapshot ?? 0, 2) }}</td>
                                    <td>
                                        @if($appointment->status === 'pending')
                                            <div class="d-flex gap-1">
                                                <form action="{{ route('doctor.appointments.approve', $appointment) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success"><i class="ti ti-check"></i></button>
                                                </form>
                                                <form action="{{ route('doctor.appointments.reject', $appointment) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="ti ti-x"></i></button>
                                                </form>
                                            </div>
                                        @else
                                            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-light"><i class="ti ti-eye"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No recent appointments</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-5 d-flex" id="availability-card">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header">
                <h5 class="fw-bold mb-0">Availability</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('doctor.schedule.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Available Date</label>
                        <input type="date" name="available_date" class="form-control @error('available_date') is-invalid @enderror" value="{{ old('available_date') }}" required>
                        @error('available_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-light w-100 mt-3 fs-13">Add Availability</button>
                </form>

                <div id="doctor-dashboard-calendar" style="min-height: 320px;"></div>

                <div class="mt-3">
                    @forelse(($doctorSchedules ?? collect())->take(6) as $schedule)
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">{{ $schedule->available_date->format('D, d M') }}</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</p>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No availability added yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0">My Patients</h5>
                <a href="{{ route('doctor.my-patients') }}" class="btn btn-sm btn-light">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive table-nowrap">
                    <table class="table border align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Patient</th>
                                <th>Email</th>
                                <th>Appointment History</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($stats['my_patients'] ?? collect()) as $patient)
                                <tr>
                                    <td>{{ $patient->user->name ?? 'Patient' }}</td>
                                    <td>{{ $patient->user->email ?? 'N/A' }}</td>
                                    <td>{{ $patient->appointment_history_count ?? 0 }} appointments</td>
                                    <td>
                                        <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-light"><i class="ti ti-eye"></i> View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No patients have booked with you yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php
    $doctorScheduleEvents = ($doctorSchedules ?? collect())->map(function ($schedule) {
        $date = $schedule->available_date->format('Y-m-d');

        return [
            'title' => \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') . ' - ' . \Carbon\Carbon::parse($schedule->end_time)->format('g:i A'),
            'start' => $date . 'T' . \Carbon\Carbon::parse($schedule->start_time)->format('H:i:s'),
            'end' => $date . 'T' . \Carbon\Carbon::parse($schedule->end_time)->format('H:i:s'),
        ];
    })->values();
@endphp

@section('scripts')
    <script src="{{ asset('assets/plugins/fullcalendar/index.global.min.js') }}"></script>
    <script>
        const scheduleCalendar = new FullCalendar.Calendar(document.getElementById('doctor-dashboard-calendar'), {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: @json($doctorScheduleEvents),
        });

        scheduleCalendar.render();
    </script>
@endsection
