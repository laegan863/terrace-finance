@extends('layouts.terrace-finance.app')

@section('title', 'Status Notifications')
@section('page_title', 'Status Notifications')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Receive Status Notification</h4>
        <small class="text-muted">Receive and inspect status notifications for leads or applications.</small>
    </div>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#notificationFormModal">
        <i class="fas fa-plus me-1"></i> New Notification
    </button>
</div>

@php
    $latestRow = $latest ?? null;

    $applicationId = $latestRow?->ApplicationID ?? '-';
    $leadId = $latestRow?->LeadID ?? '-';
    $applicationStatus = $latestRow?->ApplicationStatus ?? '-';
    $lenderName = $latestRow?->LenderName ?? '-';

    $invoiceNumber = $latestRow?->InvoiceNumber ?? '-';
    $invoiceId = $latestRow?->InvoiceID ?? '-';

    $approvalDisplay = is_null($latestRow?->ApprovalAmount) ? '-' : '$' . number_format((float)$latestRow->ApprovalAmount, 2);
    $fundedDisplay = is_null($latestRow?->FundedAmount) ? '-' : '$' . number_format((float)$latestRow->FundedAmount, 2);

    $headers = [
        'Token' => $latestRow?->token_header,
        'Authorization' => $latestRow?->authorization_header,
    ];

    $payloadKey = 'statusNotifyPayload';
@endphp

<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <i class="fas fa-bell"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Notifications</p>
                            <h4 class="card-title">{{ $stats['total'] }}</h4>
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
                            <i class="fas fa-flag"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Last Status</p>
                            <h4 class="card-title">{{ $stats['last_status'] }}</h4>
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
                            <i class="fas fa-hashtag"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Last Application Identifier</p>
                            <h4 class="card-title">{{ $stats['last_application_id'] }}</h4>
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
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Last Received At</p>
                            <h4 class="card-title">{{ $stats['last_received_at'] }}</h4>
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
            <div class="card-title mb-0"><strong>Success</strong></div>
            Latest notification overview.
        </div>

        <div class="d-flex gap-2">
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
        <div class="table-responsive mb-3">
            <table class="table table-bordered align-middle mb-0">
                <tbody>
                    <tr>
                        <th style="width: 220px;">Application Identifier</th>
                        <td><span class="tf-chip tf-chip-soft">{{ $applicationId }}</span></td>

                        <th style="width: 220px;">Lead Identifier</th>
                        <td><span class="tf-chip tf-chip-soft">{{ $leadId }}</span></td>
                    </tr>

                    <tr>
                        <th>Application Status</th>
                        <td><span class="tf-chip tf-chip-info">{{ $applicationStatus }}</span></td>

                        <th>Lender Name</th>
                        <td><span class="tf-chip tf-chip-soft">{{ $lenderName }}</span></td>
                    </tr>

                    <tr>
                        <th>Invoice Number</th>
                        <td><span class="tf-chip tf-chip-soft">{{ $invoiceNumber }}</span></td>

                        <th>Invoice Identifier</th>
                        <td><span class="tf-chip tf-chip-soft">{{ $invoiceId }}</span></td>
                    </tr>

                    <tr>
                        <th>Approval Amount</th>
                        <td>
                            @if($approvalDisplay === '-')
                                <span class="tf-chip tf-chip-muted">-</span>
                            @else
                                <span class="tf-chip tf-chip-money">{{ $approvalDisplay }}</span>
                            @endif
                        </td>

                        <th>Funded Amount</th>
                        <td>
                            @if($fundedDisplay === '-')
                                <span class="tf-chip tf-chip-muted">-</span>
                            @else
                                <span class="tf-chip tf-chip-money">{{ $fundedDisplay }}</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="collapse" id="{{ $payloadKey }}Req">
            <h6 class="mb-2">Request Headers</h6>
            <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($headers, JSON_PRETTY_PRINT) }}</pre>
            <hr class="my-3">
        </div>

        <div class="collapse" id="{{ $payloadKey }}Res">
            <h6 class="mb-2">Notification Payload</h6>
            <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($latestRow?->raw_payload ?? [], JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <div class="card-title mb-0">Recent Notifications</div>
            <small class="text-muted">Latest received notifications</small>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle tf-table">
                <thead>
                    <tr>
                        <th>Created At</th>
                        <th>Source</th>
                        <th>Application Identifier</th>
                        <th>Application Status</th>
                        <th>Lender Name</th>
                        <th>Invoice Number</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recent as $r)
                        @php
                            $approval = is_null($r->ApprovalAmount) ? '-' : '$' . number_format((float)$r->ApprovalAmount, 2);
                            $funded = is_null($r->FundedAmount) ? '-' : '$' . number_format((float)$r->FundedAmount, 2);

                            $httpStatus = $r->result->http_status ?? '-';
                            $resultResponse = $r->result->response ?? [];
                            $rawPayload = $r->raw_payload ?? [];

                            $headers = [
                                'Token' => $r->token_header,
                                'Authorization' => $r->authorization_header,
                            ];
                        @endphp

                        <tr>
                            <td class="text-muted">{{ optional($r->created_at)->format('Y-m-d H:i') }}</td>
                            <td><span class="tf-chip tf-chip-soft">{{ $r->source }}</span></td>
                            <td><span class="tf-chip tf-chip-soft">{{ $r->ApplicationID ?? '-' }}</span></td>
                            <td><span class="tf-chip tf-chip-info">{{ $r->ApplicationStatus ?? '-' }}</span></td>
                            <td>{{ $r->LenderName ?? '-' }}</td>
                            <td>{{ $r->InvoiceNumber ?? '-' }}</td>

                            <td class="text-end">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary snViewBtn"
                                    data-id="{{ $r->id }}"
                                    data-created="{{ optional($r->created_at)->format('Y-m-d H:i') }}"
                                    data-source="{{ $r->source }}"
                                    data-status="{{ $r->status }}"
                                    data-http="{{ $httpStatus }}"
                                    data-applicationid="{{ $r->ApplicationID ?? '-' }}"
                                    data-leadid="{{ $r->LeadID ?? '-' }}"
                                    data-invoicenumber="{{ $r->InvoiceNumber ?? '-' }}"
                                    data-invoiceid="{{ $r->InvoiceID ?? '-' }}"
                                    data-approval="{{ $approval }}"
                                    data-funded="{{ $funded }}"
                                    data-lender="{{ $r->LenderName ?? '-' }}"
                                    data-appstatus="{{ $r->ApplicationStatus ?? '-' }}"
                                    data-offer='@json($r->Offer ?? null)'
                                    data-headers='@json($headers)'
                                    data-raw='@json($rawPayload)'
                                    data-result='@json($resultResponse)'
                                >
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted">No notifications available.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

{{-- Logs Recent Notifications --}}
@if(isset($logs))
<div class="card mt-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <div class="card-title mb-0">Status Notification Requests</div>
            <small class="text-muted">Latest submissions</small>
        </div>

        <button
            class="btn btn-sm btn-outline-secondary"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#statusNotificationLogs"
            aria-expanded="false"
            aria-controls="statusNotificationLogs"
        >
            Toggle Logs
        </button>
    </div>

    <div id="statusNotificationLogs" class="collapse">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle tf-table">
                <thead>
                    <tr>
                        <th>Created At</th>
                        <th>Source</th>
                        <th>Application Identifier</th>
                        <th>Application Status</th>
                        <th>Lender Name</th>
                        <th class="text-end">Approval Amount</th>
                        <th class="text-end">Funded Amount</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($logs as $l)
                        @php
                            $approval = is_null($l->ApprovalAmount)
                                ? '-'
                                : '$' . number_format((float)$l->ApprovalAmount, 2);

                            $funded = is_null($l->FundedAmount)
                                ? '-'
                                : '$' . number_format((float)$l->FundedAmount, 2);

                            $httpStatus = $l->result->http_status ?? '-';
                            $resultResponse = $l->result->response ?? [];
                            $rawPayload = $l->raw_payload ?? [];
                        @endphp

                        <tr>
                            <td class="text-muted">
                                {{ optional($l->created_at)->format('Y-m-d H:i') }}
                            </td>

                            <td>
                                <span class="tf-chip tf-chip-soft">{{ $l->source }}</span>
                            </td>

                            <td>
                                <span class="tf-chip tf-chip-soft">
                                    {{ $l->ApplicationID ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <span class="tf-chip tf-chip-info">
                                    {{ $l->ApplicationStatus ?? '-' }}
                                </span>
                            </td>

                            <td>{{ $l->LenderName ?? '-' }}</td>

                            <td class="text-end">
                                @if($approval === '-')
                                    <span class="tf-chip tf-chip-muted">-</span>
                                @else
                                    <span class="tf-chip tf-chip-money">{{ $approval }}</span>
                                @endif
                            </td>

                            <td class="text-end">
                                @if($funded === '-')
                                    <span class="tf-chip tf-chip-muted">-</span>
                                @else
                                    <span class="tf-chip tf-chip-money">{{ $funded }}</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary snViewBtn"
                                    data-id="{{ $l->id }}"
                                    data-created="{{ optional($l->created_at)->format('Y-m-d H:i') }}"
                                    data-source="{{ $l->source }}"
                                    data-http="{{ $httpStatus }}"
                                    data-applicationid="{{ $l->ApplicationID ?? '-' }}"
                                    data-leadid="{{ $l->LeadID ?? '-' }}"
                                    data-invoicenumber="{{ $l->InvoiceNumber ?? '-' }}"
                                    data-invoiceid="{{ $l->InvoiceID ?? '-' }}"
                                    data-approval="{{ $approval }}"
                                    data-funded="{{ $funded }}"
                                    data-lender="{{ $l->LenderName ?? '-' }}"
                                    data-appstatus="{{ $l->ApplicationStatus ?? '-' }}"
                                    data-offer='@json($l->Offer ?? null)'
                                    data-headers='@json([
                                        "Token" => $l->token_header,
                                        "Authorization" => $l->authorization_header
                                    ])'
                                    data-raw='@json($rawPayload)'
                                    data-result='@json($resultResponse)'
                                >
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted">
                                No logs available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endif



{{-- New Notification Modal (Clean) --}}
<div class="modal fade" id="notificationFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('tfc.status-notifications.receive') }}" id="notificationReceiveForm">
                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">New Notification</h5>
                        <small class="text-muted">Submit a status notification payload to be recorded.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- scroll container to prevent overflow --}}
                <div class="modal-body p-4" style="max-height: calc(100vh - 180px); overflow-y: auto;">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="row g-3">
                        {{-- Row 1 --}}
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Application Identifier</label>
                                <input class="form-control" name="ApplicationID" value="{{ old('ApplicationID') }}" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Lead Identifier</label>
                                <input class="form-control" name="LeadID" value="{{ old('LeadID') }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Application Status</label>
                                <input class="form-control" name="ApplicationStatus" value="{{ old('ApplicationStatus') }}" required>
                                <small class="form-text text-muted">Example: Approved, Funded, Draft, Pending_Signature</small>
                            </div>
                        </div>

                        {{-- Row 2 --}}
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Invoice Number</label>
                                <input class="form-control" name="InvoiceNumber" value="{{ old('InvoiceNumber') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Invoice Identifier</label>
                                <input class="form-control" name="InvoiceID" value="{{ old('InvoiceID') }}">
                            </div>
                        </div>

                        {{-- Row 3 --}}
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Approval Amount</label>
                                <input class="form-control" name="ApprovalAmount" value="{{ old('ApprovalAmount') }}" placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Funded Amount</label>
                                <input class="form-control" name="FundedAmount" value="{{ old('FundedAmount') }}" placeholder="0.00">
                            </div>
                        </div>

                        {{-- Row 4 --}}
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Lender Name</label>
                                <input class="form-control" name="LenderName" value="{{ old('LenderName') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Offer</label>
                                <small class="form-text text-muted d-block">Optional JSON (array/object). Leave empty if not applicable.</small>
                            </div>
                        </div>

                        {{-- Offer JSON (full width) --}}
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <textarea
                                    class="form-control"
                                    name="Offer"
                                    id="OfferJson"
                                    rows="8"
                                    placeholder='Optional JSON. Example: [{"PaymentFrequency":"Monthly","PaymentAmount":229.16}]'
                                >{{ old('Offer') }}</textarea>
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

{{-- View Status Notification Modal --}}
<div class="modal fade" id="viewStatusNotificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0">Status Notification Details <span id="viewSnId" class="text-muted"></span></h5>
                    <small class="text-muted">View request information and stored result</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Notification Information</h6>

                <div class="row mb-4">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Created At</small>
                        <span id="viewSnCreated" class="fw-semibold"></span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Source</small>
                        <span id="viewSnSource"></span>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Application Identifier</small>
                        <span id="viewSnApplicationId"></span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Lead Identifier</small>
                        <span id="viewSnLeadId"></span>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Application Status</small>
                        <span id="viewSnApplicationStatus"></span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Lender Name</small>
                        <span id="viewSnLender"></span>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Invoice Number</small>
                        <span id="viewSnInvoiceNumber"></span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Invoice Identifier</small>
                        <span id="viewSnInvoiceId"></span>
                    </div>

                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Approval Amount</small>
                        <span id="viewSnApproval"></span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Funded Amount</small>
                        <span id="viewSnFunded"></span>
                    </div>
                </div>

                <hr>

                <h6 class="fw-bold mb-3"><i class="fas fa-shield-alt me-2"></i>Status</h6>
                <div class="mb-4">
                    <span id="viewSnStatusBadge" class="badge"></span>
                    <span id="viewSnHttpBadge" class="badge bg-secondary ms-2"></span>
                </div>

                <hr>

                <h6 class="fw-bold mb-3"><i class="fas fa-code me-2"></i>Stored Data</h6>
                <div class="d-flex gap-2 mb-3">
                    <button class="btn btn-sm btn-outline-secondary" type="button"
                            data-bs-toggle="collapse" data-bs-target="#snHeadersCollapse"
                            aria-expanded="false" aria-controls="snHeadersCollapse">
                        Toggle Headers
                    </button>

                    <button class="btn btn-sm btn-outline-secondary" type="button"
                            data-bs-toggle="collapse" data-bs-target="#snRawCollapse"
                            aria-expanded="false" aria-controls="snRawCollapse">
                        Toggle Raw Payload
                    </button>

                    <button class="btn btn-sm btn-outline-secondary" type="button"
                            data-bs-toggle="collapse" data-bs-target="#snResultCollapse"
                            aria-expanded="false" aria-controls="snResultCollapse">
                        Toggle Result
                    </button>

                    <button class="btn btn-sm btn-outline-secondary" type="button"
                            data-bs-toggle="collapse" data-bs-target="#snOfferCollapse"
                            aria-expanded="false" aria-controls="snOfferCollapse">
                        Toggle Offer
                    </button>
                </div>

                <div class="collapse" id="snHeadersCollapse">
                    <h6 class="mb-2">Headers</h6>
                    <div class="bg-light rounded p-3 mb-3">
                        <pre id="viewSnHeaders" class="mb-0" style="white-space: pre-wrap; word-break: break-word;"></pre>
                    </div>
                </div>

                <div class="collapse" id="snRawCollapse">
                    <h6 class="mb-2">Raw Payload</h6>
                    <div class="bg-light rounded p-3 mb-3">
                        <pre id="viewSnRaw" class="mb-0" style="white-space: pre-wrap; word-break: break-word;"></pre>
                    </div>
                </div>

                <div class="collapse" id="snResultCollapse">
                    <h6 class="mb-2">Result Response</h6>
                    <div class="bg-light rounded p-3 mb-3">
                        <pre id="viewSnResult" class="mb-0" style="white-space: pre-wrap; word-break: break-word;"></pre>
                    </div>
                </div>

                <div class="collapse" id="snOfferCollapse">
                    <h6 class="mb-2">Offer</h6>
                    <div class="bg-light rounded p-3">
                        <pre id="viewSnOffer" class="mb-0" style="white-space: pre-wrap; word-break: break-word;"></pre>
                    </div>
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
    const modalEl = document.getElementById('notificationFormModal');
    const formEl = document.getElementById('notificationReceiveForm');
    const offerEl = document.getElementById('OfferJson');

    @if ($errors->any())
        if (modalEl) {
            const m = new bootstrap.Modal(modalEl);
            m.show();
        }
    @endif

    if (!formEl || !offerEl) return;

    formEl.addEventListener('submit', function (e) {
        const raw = (offerEl.value || '').trim();
        if (!raw) return;

        try {
            const parsed = JSON.parse(raw);
            const isArray = Array.isArray(parsed);
            const isObject = typeof parsed === 'object' && parsed !== null;

            if (!isArray && !isObject) {
                e.preventDefault();
                alert('Offer must be a JSON array or object. Example: [{"PaymentFrequency":"Monthly","PaymentAmount":229.16}]');
                return;
            }
        } catch (err) {
            e.preventDefault();
            alert('Offer JSON is invalid. Please provide valid JSON or leave it empty.');
        }
    });
})();
</script>
@endpush

@push('scripts')
<script>
(function () {
    const modalEl = document.getElementById('viewStatusNotificationModal');
    if (!modalEl) return;

    const modal = new bootstrap.Modal(modalEl);

    function setText(id, value) {
        const el = document.getElementById(id);
        if (!el) return;
        el.textContent = (value === null || value === undefined || value === '') ? '-' : value;
    }

    function setStatusBadge(status) {
        const el = document.getElementById('viewSnStatusBadge');
        if (!el) return;

        el.className = 'badge';
        const s = (status || '').toLowerCase();

        if (s === 'success') el.classList.add('bg-success');
        else if (s === 'failed') el.classList.add('bg-danger');
        else el.classList.add('bg-warning', 'text-dark');

        el.textContent = status ? (status.charAt(0).toUpperCase() + status.slice(1)) : '-';
    }

    function pretty(obj) {
        try { return JSON.stringify(obj, null, 4); } catch (e) { return '{}'; }
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.snViewBtn');
        if (!btn) return;

        setText('viewSnId', '#' + (btn.dataset.id || '-'));
        setText('viewSnCreated', btn.dataset.created || '-');
        setText('viewSnSource', btn.dataset.source || '-');

        setText('viewSnApplicationId', btn.dataset.applicationid || '-');
        setText('viewSnLeadId', btn.dataset.leadid || '-');
        setText('viewSnApplicationStatus', btn.dataset.appstatus || '-');
        setText('viewSnLender', btn.dataset.lender || '-');

        setText('viewSnInvoiceNumber', btn.dataset.invoicenumber || '-');
        setText('viewSnInvoiceId', btn.dataset.invoiceid || '-');

        setText('viewSnApproval', btn.dataset.approval || '-');
        setText('viewSnFunded', btn.dataset.funded || '-');

        setStatusBadge(btn.dataset.status || '-');
        setText('viewSnHttpBadge', 'HTTP ' + (btn.dataset.http || '-'));

        let headersObj = {};
        let rawObj = {};
        let resultObj = {};
        let offerObj = null;

        try { headersObj = JSON.parse(btn.dataset.headers || '{}'); } catch (e1) { headersObj = {}; }
        try { rawObj = JSON.parse(btn.dataset.raw || '{}'); } catch (e2) { rawObj = {}; }
        try { resultObj = JSON.parse(btn.dataset.result || '{}'); } catch (e3) { resultObj = {}; }
        try { offerObj = JSON.parse(btn.dataset.offer || 'null'); } catch (e4) { offerObj = null; }

        setText('viewSnHeaders', pretty(headersObj));
        setText('viewSnRaw', pretty(rawObj));
        setText('viewSnResult', pretty(resultObj));
        setText('viewSnOffer', offerObj === null ? '-' : pretty(offerObj));

        // Collapse reset (optional)
        ['snHeadersCollapse','snRawCollapse','snResultCollapse','snOfferCollapse'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) bootstrap.Collapse.getOrCreateInstance(el, { toggle: false }).hide();
        });

        modal.show();
    });
})();
</script>
@endpush

