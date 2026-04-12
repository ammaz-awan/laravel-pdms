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
                <p><strong>Age:</strong> {{ $patient->age }} years</p>
            </div>
            <div class="col-md-6">
                <p><strong>Gender:</strong> {{ ucfirst($patient->gender) }}</p>
                <p><strong>Blood Group:</strong> <span class="badge bg-info">{{ $patient->blood_group }}</span></p>
                <p>
                    <strong>Payment Method:</strong>
                    @if($patient->is_payment_method_verified)
                        <span class="badge bg-success">Verified</span>
                    @else
                        <span class="badge bg-warning">Not Verified</span>
                    @endif
                </p>
            </div>
        </div>

        <hr>

        <div class="d-flex gap-2">
            {{-- <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary"><i class="ti ti-pencil"></i> Edit</a> --}}
            <a href="{{ route('patients.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
