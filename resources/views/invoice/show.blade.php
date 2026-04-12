@extends('layouts.layout')

@section('title', 'View Invoice')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="ti ti-eye"></i> Invoice Details
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Invoice ID:</strong> {{ $invoice->id }}</p>
                <p><strong>Patient:</strong> {{ $invoice->patient->user->name }}</p>
                <p><strong>Email:</strong> {{ $invoice->patient->user->email }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Total Amount:</strong> <strong style="font-size: 1.5rem; color: #667eea;">${{ number_format($invoice->total_amount, 2) }}</strong></p>
                <p><strong>Issued Date:</strong> {{ \Carbon\Carbon::parse($invoice->issued_date)->format('M d, Y') }}</p>
                <p>
                    <strong>Status:</strong>
                    @if($invoice->status == 'paid')
                        <span class="badge bg-success">Paid</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </p>
            </div>
        </div>

        <hr>

        <div class="d-flex gap-2">
            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary"><i class="ti ti-pencil"></i> Edit</a>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary"><i class="ti ti-arrow-left"></i> Back</a>
        </div>
    </div>
</div>
@endsection
