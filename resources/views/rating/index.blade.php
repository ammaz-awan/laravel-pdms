@extends('layouts.layout')

@section('title', 'Ratings')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <span><i class="ti ti-star"></i> Rating Management</span>
            <a href="{{ route('ratings.create') }}" class="btn btn-sm btn-light"><i class="ti ti-plus"></i> Add Rating</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Doctor</th>
                        <th>Patient</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ratings as $rating)
                        <tr>
                            <td>{{ $rating->id }}</td>
                            <td>{{ $rating->doctor->user->name }}</td>
                            <td>{{ $rating->patient->user->name }}</td>
                            <td>
                                <span class="badge bg-info">
                                    @for($i = 0; $i < $rating->rating; $i++)
                                        <i class="ti ti-star"></i>
                                    @endfor
                                    {{ $rating->rating }}/5
                                </span>
                            </td>
                            <td><small>{{ Str::limit($rating->review, 50) }}</small></td>
                            <td>{{ $rating->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('ratings.show', $rating->id) }}" class="btn btn-info"><i class="ti ti-eye"></i></a>
                                    <a href="{{ route('ratings.edit', $rating->id) }}" class="btn btn-warning"><i class="ti ti-pencil"></i></a>
                                    <form action="{{ route('ratings.destroy', $rating->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="ti ti-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No ratings found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $ratings->links() }}
    </div>
</div>
@endsection
