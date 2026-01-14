@extends('layouts.terrace-finance.app')

@section('title', 'Applications')
@section('page_title', 'Applications')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Post Application</h4>
        <small class="text-muted">Create a complete application for evaluation and lender routing.</small>
    </div>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applicationModal">
        <i class="fas fa-plus me-1"></i> New Application
    </button>
</div>

@php
    $req = $result['request'] ?? null;
    $res = $result['response'] ?? null;

    $appId = $res['Result'] ?? null;
    $isSuccess = (bool)($res['IsSuccess'] ?? false);
    $message = (string)($res['Message'] ?? '');
@endphp

@if($req && $res)
    @php
        $appId = $res['Result'] ?? '-';
        $product = $req['ProductInformation'] ?? '-';
        $amount = number_format((float)($req['BestEstimate'] ?? 0), 2);
        $payFreq = $req['PayFrequency'] ?? '-';
    @endphp

    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Application ID</p>
                                <h4 class="card-title">{{ $appId }}</h4>
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
                                <p class="card-category">Product</p>
                                <h4 class="card-title">{{ $product }}</h4>
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
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Requested Amount</p>
                                <h4 class="card-title">${{ $amount }}</h4>
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
                                <i class="far fa-calendar-alt"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Pay Frequency</p>
                                <h4 class="card-title">{{ $payFreq }}</h4>
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
                        data-bs-toggle="collapse" data-bs-target="#reqPayload"
                        aria-expanded="false" aria-controls="reqPayload">
                    Toggle Request
                </button>

                <button class="btn btn-sm btn-outline-secondary" type="button"
                        data-bs-toggle="collapse" data-bs-target="#resPayload"
                        aria-expanded="false" aria-controls="resPayload">
                    Toggle Response
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="collapse" id="reqPayload">
                <h6 class="mb-2">Request Payload</h6>
                <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($req, JSON_PRETTY_PRINT) }}</pre>
                <hr class="my-3">
            </div>

            <div class="collapse" id="resPayload">
                <h6 class="mb-2">Response Payload</h6>
                <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($res, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    </div>


@endif

<div class="card mt-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <div class="card-title mb-0">Recent Applications</div>
            <small class="text-muted">Latest submissions</small>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle tf-table">
                <thead>
                    <tr>
                        <th title="Application ID">App ID</th>
                        <th title="Applicant Name">Applicant</th>
                        <th title="Product Information">Product</th>
                        <th class="text-end" title="Best Estimate">Amount</th>
                        <th title="Pay Frequency">Pay Frequency</th>
                        <th title="Status">Status</th>
                        <th title="Created At">Created</th>
                        <th class="text-end" title="Action">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $h)
                        <tr>
                            <td><span class="tf-chip tf-chip-soft">{{ $h['ApplicationID'] }}</span></td>
                            <td>{{ $h['Applicant'] }}</td>
                            <td><span class="tf-chip tf-chip-info">{{ $h['ProductInformation'] }}</span></td>
                            <td class="text-end"><span class="tf-chip tf-chip-money">${{ number_format((float)$h['BestEstimate'], 2) }}</span></td>
                            <td><span class="tf-chip tf-chip-soft">{{ $h['PayFrequency'] }}</span></td>
                            <td><span class="tf-chip tf-chip-soft">{{ $h['Status'] }}</span></td>
                            <td class="text-muted">{{ $h['CreatedAt'] }}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" type="button" disabled>View</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="applicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('tfc.applications.store') }}">
                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Post Application</h5>
                        <small class="text-muted">All required fields must be complete before submission.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">{{ $errors->first() }}</div>
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

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Cell Number</label>
                                <input class="form-control" name="CellNumber" value="{{ old('CellNumber') }}" required placeholder="10 digits">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Cell Validation</label>
                                <select class="form-control" name="CellValidation" required>
                                    <option value="1" @selected(old('CellValidation')==='1')>True</option>
                                    <option value="0" @selected(old('CellValidation')==='0')>False</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Consent</label>
                                <select class="form-control" name="Consent" required>
                                    <option value="1" @selected(old('Consent')==='1')>True</option>
                                    <option value="0" @selected(old('Consent')==='0')>False</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="form-label">Address</label>
                                <input class="form-control" name="Address" value="{{ old('Address') }}" required maxlength="100">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="form-label">Address 2</label>
                                <input class="form-control" name="Address2" value="{{ old('Address2') }}" maxlength="50">
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

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Email</label>
                                <input class="form-control" type="email" name="Email" value="{{ old('Email') }}" required maxlength="50">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Fingerprint</label>
                                <input class="form-control" name="Fingerprint" value="{{ old('Fingerprint') }}" required maxlength="256">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">SSN</label>
                                <input class="form-control" name="SSN" value="{{ old('SSN') }}" required maxlength="9" placeholder="9 digits">
                            </div>
                        </div>

                        {{-- Date pickers -> hidden fields in MM/DD/YYYY --}}
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">DOB</label>
                                <input type="date" class="form-control tf-datepicker" id="DOB_picker" data-date-target="DOB" />
                                <input type="hidden" name="DOB" id="DOB" value="{{ old('DOB') }}">
                                <small class="form-text text-muted">MM/DD/YYYY</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Pay Frequency</label>
                                <select class="form-control" name="PayFrequency" required>
                                    <option value="">Select...</option>
                                    @foreach($payFrequencies as $pf)
                                        <option value="{{ $pf }}" @selected(old('PayFrequency')===$pf)>{{ $pf }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Use exact values.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Last Pay Date</label>
                                <input type="date" class="form-control tf-datepicker" id="LastPayDate_picker" data-date-target="LastPayDate" />
                                <input type="hidden" name="LastPayDate" id="LastPayDate" value="{{ old('LastPayDate') }}">
                                <small class="form-text text-muted">MM/DD/YYYY</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Next Pay Date</label>
                                <input type="date" class="form-control tf-datepicker" id="NextPayDate_picker" data-date-target="NextPayDate" />
                                <input type="hidden" name="NextPayDate" id="NextPayDate" value="{{ old('NextPayDate') }}">
                                <small class="form-text text-muted">MM/DD/YYYY</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Gross Income</label>
                                <input class="form-control" name="GrossIncome" value="{{ old('GrossIncome') }}" placeholder="####.##">
                                <small class="form-text text-muted">Either GrossIncome or NetIncome is required.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Net Income</label>
                                <input class="form-control" name="NetIncome" value="{{ old('NetIncome') }}" placeholder="####.##">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group mb-0">
                                <label class="form-label">Product Information</label>
                                <select class="form-control" name="ProductInformation" required>
                                    <option value="">Select product...</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p }}" @selected(old('ProductInformation')===$p)>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Best Estimate</label>
                                <input class="form-control" name="BestEstimate" value="{{ old('BestEstimate') }}" required placeholder="####.##">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label class="form-label">Identification Document ID</label>
                                <input class="form-control" name="IdentificationDocumentID" value="{{ old('IdentificationDocumentID') }}" maxlength="30">
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
@endsection

@push('scripts')
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

            picker.addEventListener('click', function () {
                openNativePicker(picker);
            });

            picker.addEventListener('focus', function () {
                openNativePicker(picker);
            });

            sync();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        syncDateInputs(document);
    });

    const modal = document.getElementById('applicationModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            syncDateInputs(modal);
        });
    }
})();
</script>

@if ($errors->any())
<script>
    const modal = new bootstrap.Modal(document.getElementById('applicationModal'));
    modal.show();
</script>
@endif
@endpush
