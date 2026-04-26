@extends('layouts.layout')

@section('title', 'Doctors')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <span><i class="ti ti-stethoscope"></i> Doctor Management</span>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('doctors.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by specialization..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="ti ti-search"></i> Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Specialization</th>
                        <th>Experience</th>
                        <th>Fees</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->id }}</td>
                            <td>{{ $doctor->user->name }}</td>
                            <td>{{ $doctor->specialization }}</td>
                            <td>{{ $doctor->experience }} yrs</td>
                            <td><strong>${{ number_format($doctor->fees, 2) }}</strong></td>
                            <td>
                                <span class="badge bg-info">{{ number_format($doctor->rating_avg, 1) }} / 5</span>
                            </td>
                            <td>
                                @if($doctor->is_verified)
                                    <span class="badge bg-success"><i class="ti ti-check"></i> Verified</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-info"><i class="ti ti-eye"></i></a>
                                    @if(auth()->user()->role === 'patient')
                                        <a href="{{ route('doctors.show', $doctor->id) }}#appointment-booking-card" class="btn btn-primary" title="{{ $doctor->is_verified ? 'Book appointment' : 'Doctor is not verified yet' }}">
                                            <i class="ti ti-calendar-plus"></i>
                                        </a>
                                    @endif
                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" style="display:inline;" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger" data-doctor-id="{{ $doctor->id }}"><i class="ti ti-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No doctors found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $doctors->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
