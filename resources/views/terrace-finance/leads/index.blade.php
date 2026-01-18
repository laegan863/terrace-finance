@extends('layouts.terrace-finance.app')

@section('title', 'Leads')
@section('page_title', 'Leads')

@php
$staticLeads = \App\Models\Lead::with('result')->latest()->get();
@endphp


@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0">Post Lead</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#postLeadModal">
        <i class="fas fa-plus me-1"></i> New Lead
    </button>
</div>

{{-- Leads Table (STATIC for now) --}}
<div class="card mt-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <div class="card-title mb-0">Submitted Leads</div>
            <small class="text-muted">Static sample data (API not connected yet)</small>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm" type="button" disabled>
                <i class="fas fa-download me-1"></i> Export
            </button>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover align-middle tf-table">
                <thead>
                    <tr>
                        <th style="width: 140px;">Lead #</th>
                        <th>Name</th>
                        <th style="width: 140px;">Phone</th>
                        <th>Email</th>
                        <th style="width: 140px;">Product</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 160px;">Created</th>
                        <th style="width: 140px;" class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staticLeads as $l)
                        <tr>
                            <td><span class="fw-bold">#{{ $l->id }}</span></td>
                            <td>{{ $l->FirstName }} {{ $l->LastName }}</td>
                            <td>{{ $l->PhoneNumber }}</td>
                            <td>{{ $l->Email }}</td>
                            <td>{{ $l->ProductInformation }}</td>
                            <td>
                                @if($l->status === 'success')
                                    <span class="tf-chip tf-chip-money">{{ $l->status }}</span>
                                @elseif($l->status === 'failed')
                                    <span class="tf-chip tf-chip-danger">{{ $l->status }}</span>
                                @else
                                    <span class="tf-chip tf-chip-warning">{{ $l->status }}</span>
                                @endif
                            </td>
                            <td>{{ $l->created_at->format('M d, Y H:i') }}</td>
                            <td class="text-end">
                                <button
                                    class="btn btn-sm btn-outline-primary view-lead-btn"
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewLeadModal"
                                    data-lead-id="{{ $l->id }}"
                                    data-lead-name="{{ $l->FirstName }} {{ $l->LastName }}"
                                    data-lead-phone="{{ $l->PhoneNumber }}"
                                    data-lead-email="{{ $l->Email }}"
                                    data-lead-address="{{ $l->Address }}, {{ $l->City }}, {{ $l->State }} {{ $l->Zip }}"
                                    data-lead-product="{{ $l->ProductInformation }}"
                                    data-lead-fingerprint="{{ $l->Fingerprint }}"
                                    data-lead-status="{{ $l->status }}"
                                    data-lead-created="{{ $l->created_at }}"
                                    data-result-http="{{ $l->result?->http_status }}"
                                    data-result-response="{{ json_encode($l->result?->response) }}"
                                >
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No leads found.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>


{{-- Result Panel --}}
@if(!empty($result))
    @php
        $api = $result['api'] ?? [];
        $status = $result['http_status'] ?? null;
        $isSuccess = $api['IsSuccess'] ?? false;
        $message = $api['Message'] ?? null;
        $leadNo = $api['Result'] ?? null;
        $url = $api['Url'] ?? null;
    @endphp

    <div class="card">
        <div class="card-header">
            <div class="card-title">API Response</div>
        </div>
        <div class="card-body">
            <div class="mb-2">
                <span class="badge bg-secondary">HTTP {{ $status }}</span>
            </div>

            <div class="alert {{ $isSuccess ? 'alert-success' : 'alert-danger' }} mb-3">
                <strong>{{ $isSuccess ? 'Success' : 'Failed' }}</strong><br>
                {{ $message }}
                @if($leadNo)
                    <div class="mt-2">Lead #: <strong>{{ $leadNo }}</strong></div>
                @endif
            </div>

            <div class="d-flex gap-2 flex-wrap">
                @if($url)
                    <a class="btn btn-primary" href="{{ $url }}" target="_blank" rel="noopener">
                        Continue Application
                    </a>
                @endif

                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#postLeadModal">
                    Create Another
                </button>
            </div>

            <hr class="my-3">
            <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($api, JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
@endif

{{-- Modal --}}
<div class="modal fade" id="postLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('tfc.leads.store') }}">
                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Post Lead</h5>
                        <small class="text-muted">Fill out the customer details below</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">First Name</label>
                                <input class="form-control" name="FirstName" value="{{ old('FirstName') }}" required maxlength="16">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Last Name</label>
                                <input class="form-control" name="LastName" value="{{ old('LastName') }}" required maxlength="16">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Phone Number</label>
                                <input class="form-control" name="PhoneNumber" value="{{ old('PhoneNumber') }}" required placeholder="10 digits">
                                <small class="form-text text-muted">Numbers only (e.g., 7135551234)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Email</label>
                                <input class="form-control" type="email" name="Email" value="{{ old('Email') }}" required maxlength="50">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="form-label">Address</label>
                                <input class="form-control" name="Address" value="{{ old('Address') }}" required maxlength="100">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group mb-0">
                                <label class="form-label">City</label>
                                <input class="form-control" name="City" value="{{ old('City') }}" required maxlength="20">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="form-label">State</label>
                                <input class="form-control" name="State" value="{{ old('State') }}" required maxlength="2" placeholder="TX">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Zip</label>
                                <input class="form-control" name="Zip" value="{{ old('Zip') }}" required placeholder="5 digits">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="form-label">Product Information</label>
                                <select class="form-control" name="ProductInformation" required>
                                    <option value="">Select product…</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p }}" @selected(old('ProductInformation')===$p)>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="form-label">Fingerprint (Optional)</label>
                                <input class="form-control" name="Fingerprint" value="{{ old('Fingerprint') }}" maxlength="256">
                                <small class="form-text text-muted">Optional device/browser fingerprint string.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Submit Lead</button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- View Lead Modal --}}
<div class="modal fade" id="viewLeadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0">Lead Details <span id="viewLeadId" class="text-muted"></span></h5>
                    <small class="text-muted">View lead information and API response</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                {{-- Lead Info Section --}}
                <h6 class="fw-bold mb-3"><i class="fas fa-user me-2"></i>Lead Information</h6>
                <div class="row mb-4">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Name</small>
                        <span id="viewLeadName" class="fw-semibold"></span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Phone</small>
                        <span id="viewLeadPhone"></span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Email</small>
                        <span id="viewLeadEmail"></span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Product</small>
                        <span id="viewLeadProduct"></span>
                    </div>
                    <div class="col-12 mb-2">
                        <small class="text-muted d-block">Address</small>
                        <span id="viewLeadAddress"></span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Fingerprint</small>
                        <span id="viewLeadFingerprint" class="text-break"></span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted d-block">Created At</small>
                        <span id="viewLeadCreated"></span>
                    </div>
                </div>

                <hr>

                {{-- Status Section --}}
                <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Status</h6>
                <div class="mb-4">
                    <span id="viewLeadStatusBadge" class="badge"></span>
                    <span id="viewResultHttp" class="badge bg-secondary ms-2"></span>
                </div>

                <hr>

                {{-- API Response Section --}}
                <h6 class="fw-bold mb-3"><i class="fas fa-code me-2"></i>API Response</h6>
                <div class="bg-light rounded p-3">
                    <pre id="viewResultResponse" class="mb-0" style="white-space: pre-wrap; word-break: break-word; max-height: 300px; overflow-y: auto;"></pre>
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
    const modal = new bootstrap.Modal(document.getElementById('postLeadModal'));
    modal.show();
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-lead-btn');

    viewButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const leadId = this.dataset.leadId;
            const leadName = this.dataset.leadName;
            const leadPhone = this.dataset.leadPhone;
            const leadEmail = this.dataset.leadEmail;
            const leadAddress = this.dataset.leadAddress;
            const leadProduct = this.dataset.leadProduct;
            const leadFingerprint = this.dataset.leadFingerprint;
            const leadStatus = this.dataset.leadStatus;
            const leadCreated = this.dataset.leadCreated;
            const resultHttp = this.dataset.resultHttp;
            const resultResponse = this.dataset.resultResponse;

            // Populate modal fields
            document.getElementById('viewLeadId').textContent = '#' + leadId;
            document.getElementById('viewLeadName').textContent = leadName;
            document.getElementById('viewLeadPhone').textContent = leadPhone;
            document.getElementById('viewLeadEmail').textContent = leadEmail;
            document.getElementById('viewLeadAddress').textContent = leadAddress;
            document.getElementById('viewLeadProduct').textContent = leadProduct;
            document.getElementById('viewLeadFingerprint').textContent = leadFingerprint || 'N/A';
            document.getElementById('viewLeadCreated').textContent = leadCreated;

            // Status badge
            const statusBadge = document.getElementById('viewLeadStatusBadge');
            statusBadge.textContent = leadStatus;
            statusBadge.className = 'badge';
            if (leadStatus === 'success') {
                statusBadge.classList.add('bg-success');
            } else if (leadStatus === 'failed') {
                statusBadge.classList.add('bg-danger');
            } else {
                statusBadge.classList.add('bg-warning', 'text-dark');
            }

            // HTTP status
            const httpBadge = document.getElementById('viewResultHttp');
            if (resultHttp) {
                httpBadge.textContent = 'HTTP ' + resultHttp;
                httpBadge.style.display = 'inline';
            } else {
                httpBadge.style.display = 'none';
            }

            // API Response
            const responseEl = document.getElementById('viewResultResponse');
            if (resultResponse && resultResponse !== 'null') {
                try {
                    const parsed = JSON.parse(resultResponse);
                    responseEl.textContent = JSON.stringify(parsed, null, 2);
                } catch (e) {
                    responseEl.textContent = resultResponse;
                }
            } else {
                responseEl.textContent = 'No response recorded.';
            }
        });
    });
});
</script>
@endpush
