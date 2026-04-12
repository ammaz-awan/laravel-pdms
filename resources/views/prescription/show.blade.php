@extends('layouts.layout')

@section('title', 'View Prescription')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="ti ti-eye"></i> Prescription Details
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>ID:</strong> {{ $prescription->id }}</p>
                <p><strong>Appointment:</strong> #{{ $prescription->appointment_id }}</p>
                <p><strong>Doctor:</strong> {{ $prescription->doctor->user->name }}</p>
                <p><strong>Patient:</strong> {{ $prescription->patient->user->name }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Created:</strong> {{ $prescription->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>

        <hr>

        <h5>Medicines</h5>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Dosage</th>
                    <th>Frequency</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prescription->medicines as $medicine)
                    <tr>
                        <td>{{ $medicine['name'] ?? 'N/A' }}</td>
                        <td>{{ $medicine['dosage'] ?? 'N/A' }}</td>
                        <td>{{ $medicine['frequency'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <h5>Notes</h5>
        <p>{{ $prescription->notes }}</p>

        <hr>

        <div class="d-flex gap-2">
            <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="btn btn-primary"><i class="ti ti-pencil"></i> Edit</a>
            <a href="{{ route('prescriptions.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
