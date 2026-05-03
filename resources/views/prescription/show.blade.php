@extends('layouts.layout')

@section('title', 'Prescription Details')

@section('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .card { box-shadow: none !important; border: none !important; }
    }
</style>
@endsection

@section('content')

{{-- ── Back + Actions Bar ───────────────────────────────────────────────── --}}
<div class="d-flex align-items-sm-center flex-sm-row flex-column mb-4 gap-2 no-print">
    <div class="flex-grow-1">
        <a href="{{ route('prescriptions.index') }}" class="btn btn-light border d-inline-flex align-items-center gap-1">
            <i class="ti ti-chevron-left"></i> Prescriptions
        </a>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-dark d-inline-flex align-items-center gap-1">
            <i class="ti ti-printer"></i> Print
        </button>
    </div>
</div>

{{-- ── Prescription Card ───────────────────────────────────────────────── --}}
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm" id="prescription-print">
            <div class="card-body">

                {{-- ── Header: Logo + Prescription ID ──────────────────────── --}}
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <div class="invoice-logo">
                        <img src="{{ asset('assets/img/logo.svg') }}" class="logo-white" alt="logo" style="height:40px;">
                        <img src="{{ asset('assets/img/logo-white.svg') }}" class="logo-dark" alt="logo" style="height:40px;">
                    </div>
                    <span class="badge bg-info-subtle text-info-emphasis fs-13 fw-medium border border-primary py-1 px-2">
                        #PRE{{ str_pad($prescription->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                {{-- ── Doctor Info + Appointment Meta ──────────────────────── --}}
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3 flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="avatar avatar-xxl rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center flex-shrink-0">
                            <i class="ti ti-stethoscope fs-24"></i>
                        </span>
                        <div>
                            <h6 class="text-dark fw-semibold mb-1">
                                Dr. {{ optional(optional($prescription->doctor)->user)->name ?? '—' }}
                            </h6>
                            <p class="mb-1 text-muted">
                                {{ optional($prescription->doctor)->specialization ?? 'General Practice' }}
                            </p>
                            @if(optional($prescription->doctor)->license_number)
                                <p class="mb-0 fs-12 text-muted">
                                    Lic: {{ $prescription->doctor->license_number }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="text-lg-end">
                        @if(optional($prescription->appointment)->appointment_date)
                            <p class="text-dark mb-1">
                                Prescribed on:
                                <span class="text-body">
                                    {{ \Carbon\Carbon::parse($prescription->appointment->appointment_date)->format('d M Y') }}
                                </span>
                            </p>
                        @endif
                        <p class="text-dark mb-1">
                            Consultation:
                            <span class="text-body">Video / Online</span>
                        </p>
                        <p class="text-dark mb-0">
                            Appointment:
                            <span class="text-body">
                                #APT{{ str_pad($prescription->appointment_id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </p>
                    </div>
                </div>

                {{-- ── Patient Details ──────────────────────────────────────── --}}
                <div class="mb-3">
                    <h6 class="mb-2 fs-14 fw-medium">Patient Details</h6>
                    <div class="px-3 py-2 bg-light rounded d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h6 class="m-0 fw-semibold fs-16">
                            {{ optional(optional($prescription->patient)->user)->name ?? '—' }}
                        </h6>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            @if(optional($prescription->patient)->age)
                                <p class="mb-0 text-dark">
                                    {{ $prescription->patient->age }}Y
                                    @if(optional($prescription->patient)->gender)
                                        / {{ ucfirst($prescription->patient->gender) }}
                                    @endif
                                </p>
                            @endif
                            @if(optional($prescription->patient)->blood_group)
                                <p class="mb-0 text-dark">
                                    <span class="text-muted">Blood:</span>
                                    {{ $prescription->patient->blood_group }}
                                </p>
                            @endif
                            <p class="mb-0 text-dark">
                                Patient ID
                                <span class="text-muted">
                                    PT{{ str_pad($prescription->patient_id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── Diagnosis ────────────────────────────────────────────── --}}
                @if($prescription->diagnosis)
                    <div class="mb-3 pb-3 border-bottom">
                        <h6 class="mb-2 fs-14 fw-semibold">Diagnosis</h6>
                        <p class="mb-0">{{ $prescription->diagnosis }}</p>
                    </div>
                @endif

                {{-- ── Medicines Table ──────────────────────────────────────── --}}
                <div class="mb-4">
                    <h6 class="mb-3 fs-16 fw-bold text-center">Prescription Details</h6>
                    @php $medicines = is_array($prescription->medicines) ? $prescription->medicines : []; @endphp
                    @if(count($medicines) > 0)
                        <div class="table-responsive border bg-white rounded">
                            <table class="table table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>SNO</th>
                                        <th>Medicine Name</th>
                                        <th>Dosage</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicines as $idx => $med)
                                        <tr>
                                            <td>{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                            <td class="fw-semibold">{{ $med['name'] ?? '—' }}</td>
                                            <td>{{ $med['dosage'] ?? '—' }}</td>
                                            <td>{{ $med['duration'] ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3 text-muted border rounded">
                            <i class="ti ti-pill fs-24 mb-1 d-block"></i>
                            No medicines listed.
                        </div>
                    @endif
                </div>

                {{-- ── Notes / Advice ───────────────────────────────────────── --}}
                @if($prescription->notes)
                    <div class="pb-3 mb-3 border-bottom">
                        <h6 class="mb-1 fs-16 fw-semibold">Advice / Notes</h6>
                        <p class="mb-0">{{ $prescription->notes }}</p>
                    </div>
                @endif

                {{-- ── Footer: Doctor Signature + Dates ────────────────────── --}}
                <div class="pb-3 mb-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <p class="mb-1 text-muted fs-13">
                            Issued: {{ $prescription->created_at->format('d M Y, h:i A') }}
                        </p>
                        <span class="badge badge-soft-success d-inline-flex align-items-center">
                            <i class="ti ti-point-filled me-1"></i>Issued
                        </span>
                    </div>
                    <div class="text-end">
                        <h6 class="fs-14 fw-semibold mb-0">
                            Dr. {{ optional(optional($prescription->doctor)->user)->name ?? '—' }}
                        </h6>
                        <p class="fs-13 fw-normal text-muted mb-0">
                            {{ optional($prescription->doctor)->specialization ?? '' }}
                        </p>
                    </div>
                </div>

                {{-- ── Print/Download Buttons ───────────────────────────────── --}}
                <div class="text-center d-flex align-items-center justify-content-center gap-2 no-print">
                    <button onclick="window.print()" class="btn btn-dark d-inline-flex align-items-center gap-1">
                        <i class="ti ti-printer"></i> Print
                    </button>
                    <a href="{{ route('prescriptions.index') }}" class="btn btn-light border d-inline-flex align-items-center gap-1">
                        <i class="ti ti-arrow-left"></i> Back to List
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

