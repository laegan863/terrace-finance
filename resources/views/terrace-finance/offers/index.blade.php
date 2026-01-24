@extends('layouts.terrace-finance.app')

@section('title', 'Offers')
@section('page_title', 'Offers')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Post Offer</h4>
        <small class="text-muted">Submit a selected offer for an application, with optional bank details.</small>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#offerModal">
            <i class="fas fa-plus me-1"></i> New Offer
        </button>
    </div>
</div>

@php
    $req = $result['request'] ?? null;
    $res = $result['response'] ?? null;

    $isSuccess = (bool)($res['IsSuccess'] ?? false);
    $message = (string)($res['Message'] ?? '');
    $resultUrl = $res['Result'] ?? null;

    $payloadKey = 'offerPayload';

    $applicationId = $req['ApplicationID'] ?? '-';
    $offerName = $req['Offer'] ?? '-';

    $bank = $req['BankDetails'] ?? null;
    $bankName = $bank['BankName'] ?? '-';
    $accountType = $bank['AccountType'] ?? '-';
@endphp

@if($req && $res)
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Application Identifier</p>
                                <h4 class="card-title">{{ $applicationId }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-tag"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Offer</p>
                                <h4 class="card-title">{{ $offerName }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-university"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Bank Name</p>
                                <h4 class="card-title">{{ $bankName }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Account Type</p>
                                <h4 class="card-title">{{ $accountType }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <div class="card-title mb-0"><strong>{{ $isSuccess ? 'Success' : 'Failed' }}</strong></div>
                {{ $message }}
            </div>

            <div class="d-flex gap-2">
                @if(!empty($resultUrl))
                    <a class="btn btn-sm btn-primary" href="{{ $resultUrl }}" target="_blank" rel="noopener">
                        Continue Signing
                    </a>
                @endif

                <button class="btn btn-sm btn-outline-secondary" type="button"
                        data-bs-toggle="collapse" data-bs-target="#{{ $payloadKey }}Req"
                        aria-expanded="false" aria-controls="{{ $payloadKey }}Req">
                    Toggle Request
                </button>

                <button class="btn btn-sm btn-outline-secondary" type="button"
                        data-bs-toggle="collapse" data-bs-target="#{{ $payloadKey }}Res"
                        aria-expanded="false" aria-controls="{{ $payloadKey }}Res">
                    Toggle Response
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="collapse" id="{{ $payloadKey }}Req">
                <h6 class="mb-2">Request Payload</h6>
                <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($req, JSON_PRETTY_PRINT) }}</pre>
                <hr class="my-3">
            </div>

            <div class="collapse" id="{{ $payloadKey }}Res">
                <h6 class="mb-2">Response Payload</h6>
                <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($res, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    </div>

    @if(!empty($bank))
        <div class="card mt-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <div class="card-title mb-0">Bank Details</div>
                    <small class="text-muted">Submitted with the selected offer</small>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle tf-table">
                        <thead>
                            <tr>
                                <th>Bank Name</th>
                                <th>Bank State</th>
                                <th>Routing Number</th>
                                <th>Account Number</th>
                                <th>Account Type</th>
                                <th>Start Date Of Bank Account</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="tf-chip tf-chip-soft">{{ $bank['BankName'] ?? '-' }}</span></td>
                                <td>{{ $bank['BankState'] ?? '-' }}</td>
                                <td>{{ $bank['RoutingNumber'] ?? '-' }}</td>
                                <td>{{ $bank['AccountNumber'] ?? '-' }}</td>
                                <td><span class="tf-chip tf-chip-info">{{ $bank['AccountType'] ?? '-' }}</span></td>
                                <td>{{ $bank['StartDateOfBankAccount'] ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if(isset($logs))
    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <div class="card-title mb-0">Offer Requests</div>
                <small class="text-muted">Latest submissions</small>
            </div>

            <button
                class="btn btn-sm btn-outline-secondary"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#postOfferLogs"
                aria-expanded="false"
                aria-controls="postOfferLogs"
            >
                Toggle Logs
            </button>
        </div>

        <div id="postOfferLogs" class="collapse">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle tf-table">
                    <thead>
                        <tr>
                            <th>Created At</th>
                            <th>Application Identifier</th>
                            <th>Offer</th>
                            <th>Bank Name</th>
                            <th>Account Type</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            @php
                                $bank = $log->BankDetails ?? null;
                                $bankName = $bank['BankName'] ?? '-';
                                $accountType = $bank['AccountType'] ?? '-';

                                $statusChipClass = 'tf-chip-soft';
                                if ($log->status === 'success') $statusChipClass = 'tf-chip-money';
                                elseif ($log->status === 'failed') $statusChipClass = 'tf-chip-danger';
                            @endphp

                            <tr>
                                <td class="text-muted">{{ optional($log->created_at)->format('Y-m-d H:i') }}</td>
                                <td><span class="tf-chip tf-chip-soft">{{ $log->ApplicationID }}</span></td>
                                <td><span class="tf-chip tf-chip-info">{{ $log->Offer }}</span></td>
                                <td>{{ $bankName }}</td>
                                <td>{{ $accountType }}</td>
                                <td><span class="tf-chip {{ $statusChipClass }}">{{ ucfirst($log->status) }}</span></td>
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
                                <td colspan="7" class="text-muted">No offers available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
    @endif

@endif

<div class="modal fade" id="offerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('tfc.offers.store') }}">
                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Post Offer</h5>
                        <small class="text-muted">Select an offer and optionally include bank details.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">{{ $errors->first() }}</div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Application Identifier</label>
                                <input class="form-control" name="ApplicationID" value="{{ old('ApplicationID') }}" required>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group mb-0">
                                <label class="form-label">Offer</label>
                                <select class="form-control" name="Offer" required>
                                    <option value="">Select an offer...</option>
                                    @foreach($offerOptions as $opt)
                                        @php
                                            $optLabel = $opt['Offer'] . ' - ' . $opt['PaymentFrequency'] . ' - $' . number_format((float)$opt['PaymentAmount'], 2);
                                        @endphp
                                        <option value="{{ $opt['Offer'] }}" @selected(old('Offer')===$opt['Offer'])>
                                            {{ $optLabel }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Offer value should match an offer identifier used by Terrace Finance.</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" value="1" id="IncludeBankDetails" name="IncludeBankDetails" @checked(old('IncludeBankDetails')==='1')>
                                <label class="form-check-label" for="IncludeBankDetails">
                                    Include bank details
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="collapse mt-3" id="bankDetailsSection">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label">Bank Name</label>
                                            <input class="form-control" name="BankDetails[BankName]" value="{{ old('BankDetails.BankName') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label class="form-label">Bank State</label>
                                            <input class="form-control" name="BankDetails[BankState]" value="{{ old('BankDetails.BankState') }}" maxlength="2">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-0">
                                            <label class="form-label">Account Type</label>
                                            <input class="form-control" name="BankDetails[AccountType]" value="{{ old('BankDetails.AccountType') }}" placeholder="Checking">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label">Account Number</label>
                                            <input class="form-control" name="BankDetails[AccountNumber]" value="{{ old('BankDetails.AccountNumber') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label">Routing Number</label>
                                            <input class="form-control" name="BankDetails[RoutingNumber]" value="{{ old('BankDetails.RoutingNumber') }}" maxlength="9">
                                        </div>
                                    </div>

                                    @php
                                        $startOld = old('BankDetails.StartDateOfBankAccount');
                                        $startPickerValue = '';
                                        try {
                                            if (!empty($startOld)) {
                                                $startPickerValue = \Carbon\Carbon::createFromFormat('m/d/Y', $startOld)->format('Y-m-d');
                                            }
                                        } catch (\Throwable $e) {
                                            $startPickerValue = '';
                                        }
                                    @endphp

                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label">Start Date Of Bank Account</label>
                                            <input
                                                type="date"
                                                class="form-control tf-datepicker"
                                                id="offer_StartDate_picker"
                                                value="{{ $startPickerValue }}"
                                                data-date-target="offer_StartDate_hidden"
                                            />
                                            <input
                                                type="hidden"
                                                id="offer_StartDate_hidden"
                                                name="BankDetails[StartDateOfBankAccount]"
                                                value="{{ old('BankDetails.StartDateOfBankAccount') }}"
                                            >
                                            <small class="form-text text-muted">MM/DD/YYYY</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
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
@if ($errors->any())
<script>
    const modal = new bootstrap.Modal(document.getElementById('offerModal'));
    modal.show();
</script>
@endif

<script>
(function () {
    function toApiDate(ymd) {
        if (!ymd) return '';
        const parts = ymd.split('-');
        if (parts.length !== 3) return '';
        const y = parts[0];
        const m = parts[1];
        const d = parts[2];
        return m + '/' + d + '/' + y;
    }

    function openNativePicker(input) {
        if (typeof input.showPicker === 'function') {
            input.showPicker();
            return;
        }
        input.focus();
    }

    function syncDateInputs(scope) {
        const inputs = scope.querySelectorAll('[data-date-target]');
        inputs.forEach(function (picker) {
            const hiddenId = picker.getAttribute('data-date-target');
            const hidden = document.getElementById(hiddenId);
            if (!hidden) return;

            const sync = function () {
                hidden.value = toApiDate(picker.value);
            };

            picker.addEventListener('change', sync);
            picker.addEventListener('blur', sync);

            picker.addEventListener('click', function () { openNativePicker(picker); });
            picker.addEventListener('focus', function () { openNativePicker(picker); });

            sync();
        });
    }

    function bindBankDetailsToggle() {
        const checkbox = document.getElementById('IncludeBankDetails');
        const section = document.getElementById('bankDetailsSection');
        if (!checkbox || !section) return;

        const sync = function () {
            const show = checkbox.checked;
            const collapse = bootstrap.Collapse.getOrCreateInstance(section, { toggle: false });
            if (show) collapse.show();
            else collapse.hide();
        };

        checkbox.addEventListener('change', sync);
        sync();
    }

    document.addEventListener('DOMContentLoaded', function () {
        syncDateInputs(document);
        bindBankDetailsToggle();
    });

    const offerModal = document.getElementById('offerModal');
    if (offerModal) {
        offerModal.addEventListener('shown.bs.modal', function () {
            syncDateInputs(offerModal);
        });
    }
})();
</script>
@endpush

{{-- View Offer Modal --}}
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
