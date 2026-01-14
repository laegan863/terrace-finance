@extends('layouts.terrace-finance.app')

@section('title', 'Status Notification History')
@section('page_title', 'Status Notification History')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Status Notification History</h4>
        <small class="text-muted">Saved notification records.</small>
    </div>

    <a class="btn btn-primary" href="{{ route('tfc.status-notifications.index') }}">
        <i class="fas fa-arrow-left me-1"></i> Back to Receive Status Notification
    </a>
</div>

<div class="card mt-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <div class="card-title mb-0">Notifications</div>
            <small class="text-muted">Latest records</small>
        </div>
        <div>
            <span class="tf-chip tf-chip-soft">Total: {{ $total ?? count($rows ?? []) }}</span>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle tf-table">
                <thead>
                    <tr>
                        <th title="Received At">Received At</th>
                        <th title="Source">Source</th>
                        <th title="Application Identifier">Application Identifier</th>
                        <th title="Lead Identifier">Lead Identifier</th>
                        <th title="Application Status">Application Status</th>
                        <th title="Lender Name">Lender Name</th>
                        <th title="Invoice Number">Invoice Number</th>
                        <th title="Invoice Identifier">Invoice Identifier</th>
                        <th class="text-end" title="Approval Amount">Approval Amount</th>
                        <th class="text-end" title="Funded Amount">Funded Amount</th>
                        <th class="text-end" title="Action">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($rows as $r)
                        @php
                            $p = $r['payload'] ?? [];
                            $approval = $p['ApprovalAmount'] ?? null;
                            $funded = $p['FundedAmount'] ?? null;

                            $approvalDisplay = is_null($approval) ? '-' : '$' . number_format((float)$approval, 2);
                            $fundedDisplay = is_null($funded) ? '-' : '$' . number_format((float)$funded, 2);

                            $payloadJson = json_encode($p, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                            $headersJson = json_encode(($r['headers'] ?? []), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
                        @endphp

                        <tr>
                            <td class="text-muted">{{ $r['received_at'] ?? '-' }}</td>
                            <td><span class="tf-chip tf-chip-soft">{{ $r['source'] ?? '-' }}</span></td>
                            <td><span class="tf-chip tf-chip-soft">{{ $p['ApplicationID'] ?? '-' }}</span></td>
                            <td><span class="tf-chip tf-chip-soft">{{ $p['LeadID'] ?? '-' }}</span></td>
                            <td><span class="tf-chip tf-chip-info">{{ $p['ApplicationStatus'] ?? '-' }}</span></td>
                            <td>{{ $p['LenderName'] ?? '-' }}</td>
                            <td>{{ $p['InvoiceNumber'] ?? '-' }}</td>
                            <td>{{ $p['InvoiceID'] ?? '-' }}</td>

                            <td class="text-end">
                                @if($approvalDisplay === '-')
                                    <span class="tf-chip tf-chip-muted">-</span>
                                @else
                                    <span class="tf-chip tf-chip-money">{{ $approvalDisplay }}</span>
                                @endif
                            </td>

                            <td class="text-end">
                                @if($fundedDisplay === '-')
                                    <span class="tf-chip tf-chip-muted">-</span>
                                @else
                                    <span class="tf-chip tf-chip-money">{{ $fundedDisplay }}</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary viewNotificationBtn"
                                    data-received="{{ e($r['received_at'] ?? '-') }}"
                                    data-headers="{{ e($headersJson) }}"
                                    data-payload="{{ e($payloadJson) }}"
                                >
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-muted">No notifications available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- View Modal --}}
<div class="modal fade" id="viewNotificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0">Notification Details</h5>
                    <small class="text-muted" id="viewReceivedAt">-</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <div class="card-title mb-0"><strong>Success</strong></div>
                            Notification details loaded.
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#historyReqPayload"
                                    aria-expanded="false" aria-controls="historyReqPayload">
                                Toggle Request
                            </button>

                            <button class="btn btn-sm btn-outline-secondary" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#historyResPayload"
                                    aria-expanded="false" aria-controls="historyResPayload">
                                Toggle Response
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="collapse" id="historyReqPayload">
                            <h6 class="mb-2">Request Headers</h6>
                            <pre class="mb-0" id="viewHeaders" style="white-space: pre-wrap;">{}</pre>
                            <hr class="my-3">
                        </div>

                        <div class="collapse" id="historyResPayload">
                            <h6 class="mb-2">Notification Payload</h6>
                            <pre class="mb-0" id="viewPayload" style="white-space: pre-wrap;">{}</pre>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const modalEl = document.getElementById('viewNotificationModal');
    if (!modalEl) return;

    const modal = new bootstrap.Modal(modalEl);

    const receivedAtEl = document.getElementById('viewReceivedAt');
    const headersEl = document.getElementById('viewHeaders');
    const payloadEl = document.getElementById('viewPayload');

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.viewNotificationBtn');
        if (!btn) return;

        const received = btn.getAttribute('data-received') || '-';
        const headers = btn.getAttribute('data-headers') || '{}';
        const payload = btn.getAttribute('data-payload') || '{}';

        receivedAtEl.textContent = received;
        headersEl.textContent = headers;
        payloadEl.textContent = payload;

        // collapse reset (optional)
        const req = document.getElementById('historyReqPayload');
        const res = document.getElementById('historyResPayload');
        if (req) bootstrap.Collapse.getOrCreateInstance(req, { toggle: false }).hide();
        if (res) bootstrap.Collapse.getOrCreateInstance(res, { toggle: false }).hide();

        modal.show();
    });
})();
</script>
@endpush
