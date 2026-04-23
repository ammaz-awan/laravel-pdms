@extends('layouts.layout')

@section('title', 'Doctor Verification Requests')

@section('content')

<div class="content">

    <h4 class="mb-3">Doctor Verification Requests</h4>

    @foreach($doctors as $doctor)

    <div class="card mb-3 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-1">{{ $doctor->user->name }}</h5>
                    <p class="text-muted mb-1">{{ $doctor->user->email }}</p>

                    <span class="badge bg-warning">
                        {{ $doctor->verification_status }}
                    </span>
                </div>

                <div class="text-end">

                    <!-- VIEW CERTIFICATE -->
                    <a href="{{ asset('storage/' . $doctor->certificate_path) }}"
                       target="_blank"
                       class="btn btn-sm btn-outline-primary">
                        View Certificate
                    </a>

                    <!-- APPROVE -->
                   <form method="POST" action="{{ route('doctor.approve', $doctor->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-success">Approve</button>
                    </form>
                    <!-- REJECT -->
                    <form method="POST" action="{{ route('doctor.reject', $doctor->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-danger">Reject</button>
                    </form>

                </div>

            </div>

            {{-- AI RESULT SECTION --}}
            @if($doctor->ai_result)
            <div class="mt-3 p-3 bg-light rounded">
                <strong>AI Analysis:</strong>

                <pre class="mb-0">
{{ json_encode($doctor->ai_result, JSON_PRETTY_PRINT) }}
                </pre>
            </div>
            @endif

        </div>
    </div>

    @endforeach

</div>

@endsection