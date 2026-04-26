@extends('layouts.layout')

@section('title', 'Patients')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <span><i class="ti ti-user-heart"></i> {{ ucfirst($listScope ?? 'Patient Management') }}</span>
        </div>
    </div>
    <div class="card-body">
         <form method="GET" action="{{ route('patients.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary"><i class="ti ti-search"></i> Search</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Blood Group</th>
                        <th>Verified</th>
                        @if(auth()->user()->role === 'doctor')
                            <th>Appointment History</th>
                        @endif
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td>{{ $patient->id }}</td>
                            <td>{{ $patient->user->name }}</td>
                            <td>{{ $patient->user->email }}</td>
                            <td>{{ $patient->age }}</td>
                            <td>{{ ucfirst($patient->gender) }}</td>
                            <td><span class="badge bg-info">{{ $patient->blood_group }}</span></td>
                            <td>
                                @if($patient->is_payment_method_verified)
                                    <span class="badge bg-success"><i class="ti ti-check"></i> Yes</span>
                                @else
                                    <span class="badge bg-warning">No</span>
                                @endif
                            </td>
                            @if(auth()->user()->role === 'doctor')
                                <td>{{ $patient->appointment_history_count ?? 0 }}</td>
                            @endif
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-info"><i class="ti ti-eye"></i></a>
                                    @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display:inline;" class="delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger" data-patient-id="{{ $patient->id }}"><i class="ti ti-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'doctor' ? '9' : '8' }}" class="text-center text-muted py-4">No patients found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $patients->links() }}
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
