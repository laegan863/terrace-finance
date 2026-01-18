@extends('layouts.terrace-finance.app')

@section('title', 'Offer History')
@section('page_title', 'Offers')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Offer History</h4>
        <small class="text-muted">Saved offer submissions.</small>
    </div>

    <a class="btn btn-primary" href="{{ route('tfc.offers.index') }}">
        <i class="fas fa-arrow-left me-1"></i> Back to Post Offer
    </a>
</div>

<div class="card mt-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <div class="card-title mb-0">Offers</div>
            <small class="text-muted">Latest records</small>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle tf-table">
                <thead>
                    <tr>
                        <th>Application Identifier</th>
                        <th>Offer</th>
                        <th>Bank Name</th>
                        <th>Account Type</th>
                        <th>Signing Uniform Resource Locator</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        @php
                            $bank = $log->BankDetails ?? null;
                            $bankName = $bank['BankName'] ?? '-';
                            $accountType = $bank['AccountType'] ?? '-';

                            $resp = $log->result->response ?? [];
                            $resultUrl = $resp['Result'] ?? '-';

                            $statusChipClass = 'tf-chip-soft';
                            if ($log->status === 'success') $statusChipClass = 'tf-chip-money';
                            elseif ($log->status === 'failed') $statusChipClass = 'tf-chip-danger';
                        @endphp

                        <tr>
                            <td><span class="tf-chip tf-chip-soft">{{ $log->ApplicationID }}</span></td>
                            <td><span class="tf-chip tf-chip-info">{{ $log->Offer }}</span></td>
                            <td>{{ $bankName }}</td>
                            <td>{{ $accountType }}</td>
                            <td class="text-muted" style="max-width: 420px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $resultUrl }}
                            </td>
                            <td><span class="tf-chip {{ $statusChipClass }}">{{ ucfirst($log->status) }}</span></td>
                            <td class="text-muted">{{ optional($log->created_at)->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary offerViewBtn"
                                    data-id="{{ $log->id }}"
                                    data-applicationid="{{ $log->ApplicationID }}"
                                    data-offer="{{ $log->Offer }}"
                                    data-status="{{ $log->status }}"
                                    data-created="{{ optional($log->created_at)->format('Y-m-d H:i') }}"
                                    data-http="{{ $log->result->http_status ?? '-' }}"
                                    data-bank='@json($log->BankDetails ?? [])'
                                    data-response='@json($log->result->response ?? [])'
                                >
                                    View
                                </button>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">No offers available.</td>
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

{{-- View Offer Modal --}}
<div class="modal fade" id="viewOfferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0">Offer Details <span id="viewOfferId" class="text-muted"></span></h5>
                    <small class="text-muted">View offer request information and API response</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-handshake me-2"></i>Offer Information</h6>

                <div class="row mb-4">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Application Identifier</small>
                        <span id="viewOfferApplicationId" class="fw-semibold"></span>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Offer</small>
                        <span id="viewOfferName"></span>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Created At</small>
                        <span id="viewOfferCreated"></span>
                    </div>
                </div>

                <hr>

                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Status</h6>
                <div class="mb-4">
                    <span id="viewOfferStatusBadge" class="badge"></span>
                    <span id="viewOfferHttpBadge" class="badge bg-secondary ms-2"></span>
                </div>

                <hr>

                <h6 class="fw-bold mb-3"><i class="fas fa-university me-2"></i>Bank Details</h6>
                <div class="bg-light rounded p-3 mb-4">
                    <pre id="viewOfferBank" class="mb-0" style="white-space: pre-wrap; word-break: break-word; max-height: 220px; overflow-y: auto;"></pre>
                </div>

                <hr>

                <h6 class="fw-bold mb-3"><i class="fas fa-code me-2"></i>API Response</h6>
                <div class="bg-light rounded p-3">
                    <pre id="viewOfferResponse" class="mb-0" style="white-space: pre-wrap; word-break: break-word; max-height: 240px; overflow-y: auto;"></pre>
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
    const modalEl = document.getElementById('viewOfferModal');
    if (!modalEl) return;

    const modal = new bootstrap.Modal(modalEl);

    function setText(id, value) {
        const el = document.getElementById(id);
        if (!el) return;
        el.textContent = (value === null || value === undefined || value === '') ? '-' : value;
    }

    function setStatusBadge(status) {
        const el = document.getElementById('viewOfferStatusBadge');
        if (!el) return;

        el.className = 'badge';
        const s = (status || '').toLowerCase();

        if (s === 'success') el.classList.add('bg-success');
        else if (s === 'failed') el.classList.add('bg-danger');
        else el.classList.add('bg-warning', 'text-dark');

        el.textContent = status ? (status.charAt(0).toUpperCase() + status.slice(1)) : '-';
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.offerViewBtn');
        if (!btn) return;

        setText('viewOfferId', '#' + (btn.dataset.id || '-'));
        setText('viewOfferApplicationId', btn.dataset.applicationid || '-');
        setText('viewOfferName', btn.dataset.offer || '-');
        setText('viewOfferCreated', btn.dataset.created || '-');

        setStatusBadge(btn.dataset.status || '-');
        setText('viewOfferHttpBadge', 'HTTP ' + (btn.dataset.http || '-'));

        let bankObj = {};
        let responseObj = {};

        try { bankObj = JSON.parse(btn.dataset.bank || '{}'); } catch (e1) { bankObj = {}; }
        try { responseObj = JSON.parse(btn.dataset.response || '{}'); } catch (e2) { responseObj = {}; }

        // If bank details are empty, show "-"
        const bankText = (bankObj && Object.keys(bankObj).length > 0)
            ? JSON.stringify(bankObj, null, 4)
            : '-';

        setText('viewOfferBank', bankText);
        setText('viewOfferResponse', JSON.stringify(responseObj, null, 4));

        modal.show();
    });
})();
</script>
@endpush
