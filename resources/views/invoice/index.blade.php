@extends('layouts.layout')

@section('title', 'Invoices')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <span><i class="ti ti-file-text"></i> Invoice Management</span>
            <a href="{{ route('invoices.create') }}" class="btn btn-sm btn-light"><i class="ti ti-plus"></i> Add Invoice</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Total Amount</th>
                        <th>Issued Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->id }}</td>
                            <td>{{ $invoice->patient->user->name }}</td>
                            <td><strong>${{ number_format($invoice->total_amount, 2) }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($invoice->issued_date)->format('M d, Y') }}</td>
                            <td>
                                @if($invoice->status == 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info"><i class="ti ti-eye"></i></a>
                                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning"><i class="ti ti-pencil"></i></a>
                                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="ti ti-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No invoices found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $invoices->links() }}
    </div>
</div>
@endsection
