@extends('layouts.layout')

@section('title', 'Add Appointment')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="ti ti-plus"></i> Add New Appointment
    </div>
    <div class="card-body">
        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="patient_id" class="form-label">Patient *</label>
                <input type="number" class="form-control @error('patient_id') is-invalid @enderror" id="patient_id" name="patient_id" value="{{ old('patient_id') }}" required>
                @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="doctor_id" class="form-label">Doctor *</label>
                <input type="number" class="form-control @error('doctor_id') is-invalid @enderror" id="doctor_id" name="doctor_id" value="{{ old('doctor_id') }}" required>
                @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date *</label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date') }}" required>
                @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="time" class="form-label">Time *</label>
                <input type="time" class="form-control @error('time') is-invalid @enderror" id="time" name="time" value="{{ old('time') }}" required>
                @error('time') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="pending" selected>Pending</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-storage"></i> Save</button>
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary"><i class="ti ti-x"></i> Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
