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
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('M d, Y') }}</p>
                <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }}</p>
                <p>
                    <strong>Status:</strong>
                    @if($appointment->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($appointment->status == 'completed')
                        <span class="badge bg-success">Completed</span>
                    @else
                        <span class="badge bg-danger">Cancelled</span>
                    @endif
                </p>
            </div>
        </div>

        @if($appointment->notes)
            <div class="alert alert-info">
                <strong>Notes:</strong> {{ $appointment->notes }}
            </div>
        @endif

        <hr>

        <div class="d-flex gap-2">
            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-primary"><i class="ti ti-pencil"></i> Edit</a>
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
