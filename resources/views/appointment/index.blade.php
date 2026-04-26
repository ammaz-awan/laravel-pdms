@extends('layouts.layout')

@section('title', 'Appointments')

@php
    $userRole = auth()->user()->role;
@endphp

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span><i class="ti ti-calendar"></i> {{ ucfirst($listScope) }}</span>
            @if($userRole === 'patient')
                <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-light"><i class="ti ti-plus"></i> Book Appointment</a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('appointments.index') }}" class="mb-3">
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="date" name="appointment_date" class="form-control" value="{{ request('appointment_date') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search"></i> Filter</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Doctor</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Fee</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->doctor->user->name }}</td>
                            <td>{{ $appointment->patient->user->name }}</td>
                            <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                            <td>${{ number_format($appointment->fee_snapshot ?? $appointment->doctor->fees, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $appointment->status === 'approved' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-info"><i class="ti ti-eye"></i></a>
                                    @if($userRole === 'admin')
                                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning"><i class="ti ti-pencil"></i></a>
                                    @endif
                                    @if(in_array($userRole, ['admin', 'doctor'], true) && $appointment->status === 'pending')
                                        <form action="{{ route('doctor.appointments.approve', $appointment) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="Approve appointment"><i class="ti ti-check"></i></button>
                                        </form>
                                        <form action="{{ route('doctor.appointments.reject', $appointment) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" title="Reject appointment"><i class="ti ti-x"></i></button>
                                        </form>
                                    @endif
                                    @if($userRole === 'admin')
                                        <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" style="display:inline;" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i class="ti ti-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No appointments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $appointments->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.delete-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Delete appointment?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                confirmButtonColor: '#d33'
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
