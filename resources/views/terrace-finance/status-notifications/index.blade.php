@extends('layouts.terrace-finance.app')

@section('title', 'Status Notifications')
@section('page_title', 'Status Notifications')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Receive Status Notification</h4>
        <small class="text-muted">Receive and inspect status notifications for leads or applications.</small>
    </div>

    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('tfc.status-notifications.history') }}">
            <i class="fas fa-list me-1"></i> Status Notification History
        </a>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#notificationFormModal">
            <i class="fas fa-plus me-1"></i> New Notification
        </button>
    </div>
</div>

@php
    $latest = $latest ?? null;
    $headers = $latest['headers'] ?? [];
    $payload = $latest['payload'] ?? null;

    $isSuccess = true;
    $message = $latest ? 'Last notification received.' : 'No notifications received yet.';
    $payloadKey = 'statusNotifyPayload';

    $applicationId = is_array($payload) ? ($payload['ApplicationID'] ?? '-') : '-';
    $leadId = is_array($payload) ? ($payload['LeadID'] ?? '-') : '-';
    $applicationStatus = is_array($payload) ? ($payload['ApplicationStatus'] ?? '-') : '-';
    $lenderName = is_array($payload) ? ($payload['LenderName'] ?? '-') : '-';

    $invoiceNumber = is_array($payload) ? ($payload['InvoiceNumber'] ?? '-') : '-';
    $invoiceId = is_array($payload) ? ($payload['InvoiceID'] ?? '-') : '-';

    $approvalAmount = is_array($payload) ? ($payload['ApprovalAmount'] ?? null) : null;
    $fundedAmount = is_array($payload) ? ($payload['FundedAmount'] ?? null) : null;

    $approvalDisplay = is_null($approvalAmount) ? '-' : '$' . number_format((float)$approvalAmount, 2);
    $fundedDisplay = is_null($fundedAmount) ? '-' : '$' . number_format((float)$fundedAmount, 2);
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
            <div class="card-title mb-0"><strong>{{ $isSuccess ? 'Success' : 'Failed' }}</strong></div>
            {{ $message }}
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

                    <tr>
                        <th>Received At</th>
                        <td colspan="3">
                            <span class="tf-chip tf-chip-soft">{{ $latest['received_at'] ?? '-' }}</span>
                            <span class="tf-chip tf-chip-soft">Source: {{ $latest['source'] ?? '-' }}</span>
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
            <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($payload, JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <div class="card-title mb-0">Recent Notifications</div>
            <small class="text-muted">Latest received notifications</small>
        </div>
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('tfc.status-notifications.history') }}">
            View All
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle tf-table">
                <thead>
                    <tr>
                        <th title="Received At">Received At</th>
                        <th title="Source">Source</th>
                        <th title="Application Identifier">Application Identifier</th>
                        <th title="Application Status">Application Status</th>
                        <th title="Lender Name">Lender Name</th>
                        <th title="Invoice Number">Invoice Number</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent as $r)
                        @php
                            $p = $r['payload'] ?? [];
                        @endphp
                        <tr>
                            <td class="text-muted">{{ $r['received_at'] ?? '-' }}</td>
                            <td><span class="tf-chip tf-chip-soft">{{ $r['source'] ?? '-' }}</span></td>
                            <td><span class="tf-chip tf-chip-soft">{{ $p['ApplicationID'] ?? '-' }}</span></td>
                            <td><span class="tf-chip tf-chip-info">{{ $p['ApplicationStatus'] ?? '-' }}</span></td>
                            <td>{{ $p['LenderName'] ?? '-' }}</td>
                            <td>{{ $p['InvoiceNumber'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">No notifications available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- New Notification (Live payload format) --}}
<div class="modal fade" id="notificationFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            {{-- This form is for copying the expected payload format.
                 It does not submit anywhere because notifications are received from the webhook endpoint. --}}
            <form onsubmit="return false;">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Notification Payload Format</h5>
                        <small class="text-muted">These are the fields expected in a status notification payload.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Application Identifier</label>
                                <input class="form-control" value="1020472" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Lead Identifier</label>
                                <input class="form-control" value="356811" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Application Status</label>
                                <input class="form-control" value="Approved" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Invoice Number</label>
                                <input class="form-control" value="3885adcf-2358-4a78-ad16-eb7c5b882239" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Invoice Identifier</label>
                                <input class="form-control" value="219718" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Approval Amount</label>
                                <input class="form-control" value="5000.00" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Funded Amount</label>
                                <input class="form-control" value="" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Lender Name</label>
                                <input class="form-control" value="Vernance, LLC" disabled>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="form-label">Offer</label>
                                <textarea class="form-control" rows="6" disabled>[...]</textarea>
                                <small class="form-text text-muted">
                                    Offer can be null or an array depending on the status.
                                </small>
                            </div>
                        </div>

                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    function copyText(text) {
        if (!text) return;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text);
            return;
        }
        const t = document.createElement('textarea');
        t.value = text;
        document.body.appendChild(t);
        t.select();
        document.execCommand('copy');
        document.body.removeChild(t);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const copyBtn = document.getElementById('copySampleJson');
        const pre = document.getElementById('sampleJsonPre');
        if (copyBtn && pre) {
            copyBtn.addEventListener('click', function () {
                copyText(pre.textContent);
            });
        }
    });
})();
</script>
@endpush
