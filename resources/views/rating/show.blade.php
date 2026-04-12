@extends('layouts.layout')

@section('title', 'View Rating')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="ti ti-eye"></i> Rating Details
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>ID:</strong> {{ $rating->id }}</p>
                <p><strong>Doctor:</strong> {{ $rating->doctor->user->name }}</p>
                <p><strong>Specialization:</strong> {{ $rating->doctor->specialization }}</p>
                <p><strong>Patient:</strong> {{ $rating->patient->user->name }}</p>
            </div>
            <div class="col-md-6">
                <p>
                    <strong>Rating:</strong>
                    <span class="badge bg-info" style="font-size: 1.1rem;">
                        @for($i = 0; $i < $rating->rating; $i++)
                            <i class="ti ti-star"></i>
                        @endfor
                        {{ $rating->rating }}/5
                    </span>
                </p>
                <p><strong>Date:</strong> {{ $rating->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>

        @if($rating->review)
            <hr>
            <h5>Review</h5>
            <p>{{ $rating->review }}</p>
        @endif

        <hr>

        <div class="d-flex gap-2">
            <a href="{{ route('ratings.edit', $rating->id) }}" class="btn btn-primary"><i class="ti ti-pencil"></i> Edit</a>
            <a href="{{ route('ratings.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
