@extends('layouts.terrace-finance.app')

@section('title', 'Invoice History')
@section('page_title', 'Invoices')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Invoice History</h4>
        <small class="text-muted">Saved invoice submissions.</small>
    </div>

    <a class="btn btn-primary" href="{{ route('tfc.invoices.index') }}">
        <i class="fas fa-arrow-left me-1"></i> Back to Post Invoice
    </a>
</div>

<div class="card mt-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <div class="card-title mb-0">Invoices</div>
            <small class="text-muted">Latest records</small>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle tf-table">
                <thead>
                    <tr>
                        <th title="Invoice Number">Invoice Number</th>
                        <th title="Application Identifier">Application Identifier</th>
                        <th title="Lead Identifier">Lead Identifier</th>
                        <th title="Invoice Date">Invoice Date</th>
                        <th title="Delivery Date">Delivery Date</th>
                        <th title="Application Status">Application Status</th>
                        <th class="text-end" title="Approval Amount">Approval Amount</th>
                        <th title="Created At">Created At</th>
                        <th class="text-end" title="Action">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($invoices as $r)
                        <tr>
                            <td><span class="tf-chip tf-chip-soft">{{ $r['InvoiceNumber'] }}</span></td>
                            <td>{{ $r['ApplicationID'] ?? '-' }}</td>
                            <td>{{ $r['LeadID'] ?? '-' }}</td>
                            <td>{{ $r['InvoiceDate'] }}</td>
                            <td>{{ $r['DeliveryDate'] }}</td>
                            <td><span class="tf-chip tf-chip-info">{{ $r['ApplicationStatus'] }}</span></td>
                            <td class="text-end">
                                @if(is_null($r['ApprovalAmount']))
                                    -
                                @else
                                    <span class="tf-chip tf-chip-money">${{ number_format((float)$r['ApprovalAmount'], 2) }}</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $r['CreatedAt'] }}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" type="button" disabled>View</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted">No invoices available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
