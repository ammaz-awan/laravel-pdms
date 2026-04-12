@extends('layouts.layout')

@section('title', 'View Doctor')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="ti ti-eye"></i> Doctor Details
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>ID:</strong> {{ $doctor->id }}</p>
                <p><strong>Name:</strong> {{ $doctor->user->name }}</p>
                <p><strong>Email:</strong> {{ $doctor->user->email }}</p>
                <p><strong>Specialization:</strong> {{ $doctor->specialization }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Experience:</strong> {{ $doctor->experience }} years</p>
                <p><strong>Consultation Fees:</strong> ${{ number_format($doctor->fees, 2) }}</p>
                <p><strong>Rating:</strong> {{ number_format($doctor->rating_avg, 1) }} / 5</p>
                <p>
                    <strong>Status:</strong>
                    @if($doctor->is_verified)
                        <span class="badge bg-success"><i class="ti ti-check"></i> Verified</span>
                    @else
                        <span class="badge bg-warning">Not Verified</span>
                    @endif
                </p>
            </div>
        </div>

        <hr>

        <div class="d-flex gap-2">
            {{-- <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-primary"><i class="ti ti-pencil"></i> Edit</a> --}}
            <a href="{{ route('doctors.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
