@extends('layouts.layout')

@section('title', 'View Patient')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="ti ti-eye"></i> Patient Details
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>ID:</strong> {{ $patient->id }}</p>
                <p><strong>Name:</strong> {{ $patient->user->name }}</p>
                <p><strong>Email:</strong> {{ $patient->user->email }}</p>
                @if(auth()->user()->role !== 'doctor')
                    <p><strong>Age:</strong> {{ $patient->age }} years</p>
                @endif
            </div>
            <div class="col-md-6">
                @if(auth()->user()->role !== 'doctor')
                    <p><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</p>
                    <p><strong>Blood Group:</strong> <span class="badge bg-info">{{ $patient->blood_group }}</span></p>
                @endif
                <p>
                    <strong>Verification:</strong>
                    @if($patient->is_payment_method_verified)
                        <span class="badge bg-success">Verified</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </p>
            </div>
        </div>

        <hr>

        @if(auth()->user()->role === 'doctor')
            <h6 class="fw-semibold">Appointment History</h6>
            <div class="table-responsive mb-3">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patient->appointments ?? collect() as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                <td>{{ ucfirst($appointment->status) }}</td>
                                <td>${{ number_format($appointment->fee_snapshot ?? $appointment->doctor->fees ?? 0, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">No appointment history available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <div class="d-flex gap-2">
            {{-- <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary"><i class="ti ti-pencil"></i> Edit</a> --}}
            <a href="{{ route('patients.index') }}" class="btn btn-primary"><i class="ti ti-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
