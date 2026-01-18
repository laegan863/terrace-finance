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
                    @forelse($logs as $log)
                        @php
                            $resp = $log->result->response ?? [];
                            $resultObj = $resp['Result'] ?? [];

                            $applicationStatus = $resultObj['ApplicationStatus'] ?? '-';
                            $approvalAmount = $resultObj['ApprovalAmount'] ?? null;

                            $statusChipClass = 'tf-chip-soft';
                            if ($log->status === 'success') $statusChipClass = 'tf-chip-money';
                            elseif ($log->status === 'failed') $statusChipClass = 'tf-chip-danger';
                        @endphp

                        <tr>
                            <td><span class="tf-chip tf-chip-soft">{{ $log->InvoiceNumber }}</span></td>
                            <td>{{ $log->ApplicationID ?? '-' }}</td>
                            <td>{{ $log->LeadID ?? '-' }}</td>
                            <td>{{ $log->InvoiceDate }}</td>
                            <td>{{ $log->DeliveryDate }}</td>
                            <td><span class="tf-chip tf-chip-info">{{ $applicationStatus }}</span></td>
                            <td class="text-end">
                                @if(is_null($approvalAmount))
                                    <span class="tf-chip tf-chip-muted">-</span>
                                @else
                                    <span class="tf-chip tf-chip-money">${{ number_format((float)$approvalAmount, 2) }}</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ optional($log->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary invoiceViewBtn"
                                    data-id="{{ $log->id }}"
                                    data-invoicenumber="{{ $log->InvoiceNumber }}"
                                    data-applicationid="{{ $log->ApplicationID ?? '-' }}"
                                    data-leadid="{{ $log->LeadID ?? '-' }}"
                                    data-invoicedate="{{ $log->InvoiceDate }}"
                                    data-deliverydate="{{ $log->DeliveryDate }}"
                                    data-status="{{ $log->status }}"
                                    data-created="{{ optional($log->created_at)->format('Y-m-d H:i') }}"
                                    data-http="{{ $log->result->http_status ?? '-' }}"
                                    data-items='@json($log->Items ?? [])'
                                    data-response='@json($log->result->response ?? [])'
                                >
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted">No invoices available.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>

        </div>
    </div>
</div>

{{-- View Invoice Modal --}}
<div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0">Invoice Details <span id="viewInvoiceId" class="text-muted"></span></h5>
                    <small class="text-muted">View invoice request information and API response</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i>Invoice Information</h6>

                <div class="row mb-4">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Invoice Number</small>
                        <span id="viewInvoiceNumber" class="fw-semibold"></span>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Application Identifier</small>
                        <span id="viewInvoiceApplicationId"></span>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Invoice Date</small>
                        <span id="viewInvoiceDate"></span>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Delivery Date</small>
                        <span id="viewDeliveryDate"></span>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Lead Identifier</small>
                        <span id="viewInvoiceLeadId"></span>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Created At</small>
                        <span id="viewInvoiceCreated"></span>
                    </div>
                </div>

                <hr>

                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Status</h6>
                <div class="mb-4">
                    <span id="viewInvoiceStatusBadge" class="badge"></span>
                    <span id="viewInvoiceHttpBadge" class="badge bg-secondary ms-2"></span>
                </div>

                <hr>

                <h6 class="fw-bold mb-3"><i class="fas fa-boxes me-2"></i>Invoice Items</h6>
                <div class="bg-light rounded p-3 mb-4">
                    <pre id="viewInvoiceItems" class="mb-0" style="white-space: pre-wrap; word-break: break-word; max-height: 240px; overflow-y: auto;"></pre>
                </div>

                <hr>

                <h6 class="fw-bold mb-3"><i class="fas fa-code me-2"></i>API Response</h6>
                <div class="bg-light rounded p-3">
                    <pre id="viewInvoiceResponse" class="mb-0" style="white-space: pre-wrap; word-break: break-word; max-height: 240px; overflow-y: auto;"></pre>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const modalEl = document.getElementById('viewInvoiceModal');
    if (!modalEl) return;

    const modal = new bootstrap.Modal(modalEl);

    function setText(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = (value === null || value === undefined || value === '') ? '-' : value;
    }

    function setStatusBadge(status) {
        const el = document.getElementById('viewInvoiceStatusBadge');
        if (!el) return;

        el.className = 'badge';
        const s = (status || '').toLowerCase();

        if (s === 'success') el.classList.add('bg-success');
        else if (s === 'failed') el.classList.add('bg-danger');
        else el.classList.add('bg-warning', 'text-dark');

        el.textContent = status ? (status.charAt(0).toUpperCase() + status.slice(1)) : '-';
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.invoiceViewBtn');
        if (!btn) return;

        setText('viewInvoiceId', '#' + (btn.dataset.id || '-'));
        setText('viewInvoiceNumber', btn.dataset.invoicenumber);
        setText('viewInvoiceApplicationId', btn.dataset.applicationid);
        setText('viewInvoiceLeadId', btn.dataset.leadid);
        setText('viewInvoiceDate', btn.dataset.invoicedate);
        setText('viewDeliveryDate', btn.dataset.deliverydate);
        setText('viewInvoiceCreated', btn.dataset.created);

        setStatusBadge(btn.dataset.status || '-');
        setText('viewInvoiceHttpBadge', 'HTTP ' + (btn.dataset.http || '-'));

        let itemsObj = [];
        let responseObj = {};

        try { itemsObj = JSON.parse(btn.dataset.items || '[]'); } catch (e1) { itemsObj = []; }
        try { responseObj = JSON.parse(btn.dataset.response || '{}'); } catch (e2) { responseObj = {}; }

        setText('viewInvoiceItems', JSON.stringify(itemsObj, null, 4));
        setText('viewInvoiceResponse', JSON.stringify(responseObj, null, 4));

        modal.show();
    });
})();
</script>
@endpush

