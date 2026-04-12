@extends('layouts.layout')

@section('title', 'Appointments')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <span><i class="ti ti-calendar"></i> Appointment Management</span>
            <a href="{{ route('appointments.create') }}" class="btn btn-sm btn-light"><i class="ti ti-plus"></i> Add Appointment</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('appointments.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <input type="date" name="date" class="form-control" placeholder="Filter by date..." value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search"></i> Filter</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->patient->user->name }}</td>
                            <td>{{ $appointment->doctor->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }}</td>
                            <td>
                                @if($appointment->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($appointment->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-info"><i class="ti ti-eye"></i></a>
                                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning"><i class="ti ti-pencil"></i></a>
                                    <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="ti ti-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No appointments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $appointments->links() }}
    </div>
</div>
@endsection
