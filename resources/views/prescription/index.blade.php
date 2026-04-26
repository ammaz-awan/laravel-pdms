@extends('layouts.layout')

@section('title', 'Prescriptions')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <span><i class="ti ti-bottle"></i> Prescription Management</span>
            <a href="{{ route('prescriptions.create') }}" class="btn btn-sm btn-light"><i class="ti ti-plus"></i> Add Prescription</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Appointment</th>
                        <th>Doctor</th>
                        <th>Patient</th>
                        <th>Medicines</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prescriptions as $prescription)
                        <tr>
                            <td>{{ $prescription->id }}</td>
                            <td>#{{ $prescription->appointment_id }}</td>
                            <td>{{ $prescription->doctor->user->name }}</td>
                            <td>{{ $prescription->patient->user->name }}</td>
                            {{-- <td><span class="badge bg-info">{{ count($prescription->medicines) }} items</span></td> --}}
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('prescriptions.show', $prescription->id) }}" class="btn btn-info"><i class="ti ti-eye"></i></a>
                                    <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="btn btn-warning"><i class="ti ti-pencil"></i></a>
                                    <form action="{{ route('prescriptions.destroy', $prescription->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="ti ti-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No prescriptions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $prescriptions->links() }}
    </div>
</div>
@endsection
