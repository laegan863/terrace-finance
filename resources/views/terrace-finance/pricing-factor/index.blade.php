@extends('layouts.terrace-finance.app')

@section('title', 'Pricing Factor')
@section('page_title', 'Pricing Factor')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Get Pricing Factor</h4>
        <small class="text-muted">Calculate pricing factor, approval amount, and available offers.</small>
    </div>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pfModal">
        <i class="fas fa-plus me-1"></i> New Request
    </button>
</div>

@php
    $req = $result['request'] ?? null;
    $res = $result['response'] ?? null;

    $isSuccess = (bool)($res['IsSuccess'] ?? false);
    $message = (string)($res['Message'] ?? '');

    $pricingFactor = $res['PricingFactor'] ?? null;
    $approvalAmount = $res['ApprovalAmount'] ?? null;
    $status = $res['Status'] ?? null;
    $url = $res['Url'] ?? null;
@endphp

@if($req && $res)
    @php
        $pfDisplay = is_null($pricingFactor) ? '-' : number_format((float)$pricingFactor, 4);
        $approvalDisplay = is_null($approvalAmount) ? '-' : '$' . number_format((float)$approvalAmount, 2);
        $statusDisplay = $status ?? '-';
        $payloadKey = 'pfPayload';
    @endphp

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-percentage"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Pricing Factor</p>
                                <h4 class="card-title">{{ $pfDisplay }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
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
                                <p class="card-category">Approval Amount</p>
                                <h4 class="card-title">{{ $approvalDisplay }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
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
                                <p class="card-category">Status</p>
                                <h4 class="card-title">{{ $statusDisplay }}</h4>
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
                @if(!empty($url))
                    <a class="btn btn-sm btn-primary" href="{{ $url }}" target="_blank" rel="noopener">
                        Continue Application
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

    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <div class="card-title mb-0">Offers</div>
                <small class="text-muted">Available payment options</small>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle tf-table">
                    <thead>
                        <tr>
                            <th title="Price Sheet">Price Sheet</th>
                            <th title="Payment Frequency">Payment Frequency</th>

                            <th class="text-end" title="Payment Amount">Payment Amount</th>
                            <th class="text-end" title="Number Of Payments">Number Of Payments</th>
                            <th class="text-end" title="Amount Due At Signing">Amount Due At Signing</th>

                            <th class="text-end" title="BiWeekly Amount">BiWeekly Amount</th>
                            <th class="text-end" title="BiWeekly Number Of Payments">BiWeekly Number Of Payments</th>

                            <th class="text-end" title="Monthly Amount">Monthly Amount</th>
                            <th class="text-end" title="Monthly Number Of Payments">Monthly Number Of Payments</th>

                            <th title="Promotion">Promotion</th>
                            <th class="text-end" title="Promotion Period">Promotion Period</th>
                            <th class="text-end" title="Promotion Cost">Promotion Cost</th>

                            <th class="text-end" title="Merchant Discount Rate">Merchant Discount Rate</th>
                            <th title="Merchant Discount Rate Type">Merchant Discount Rate Type</th>
                        </tr>
                    </thead>


                    <tbody>
                        @forelse($offers as $o)
                            @php
                                $freq = $o['PaymentFrequency'] ?? '-';
                                $promo = $o['Promotion'] ?? null;
                            @endphp

                            <tr>
                                <td>
                                    <span class="tf-chip tf-chip-soft">{{ $o['PriceSheet'] ?? '-' }}</span>
                                </td>

                                <td>
                                    <span class="tf-chip tf-chip-info">{{ $freq }}</span>
                                </td>

                                <td class="text-end">
                                    <span class="tf-chip tf-chip-money">
                                        ${{ number_format((float)($o['PaymentAmount'] ?? 0), 2) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <span class="tf-chip tf-chip-soft">
                                        {{ $o['NumberOfPayments'] ?? '-' }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <span class="tf-chip tf-chip-warning">
                                        ${{ number_format((float)($o['AmountDueAtSigning'] ?? 0), 2) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    ${{ number_format((float)($o['BiWeeklyAmount'] ?? 0), 2) }}
                                </td>
                                <td class="text-end">{{ $o['BiWeeklyNumPayments'] ?? '-' }}</td>

                                <td class="text-end">
                                    ${{ number_format((float)($o['MonthlyAmount'] ?? 0), 2) }}
                                </td>
                                <td class="text-end">{{ $o['MonthlyNumPayments'] ?? '-' }}</td>

                                <td>
                                    @if(is_null($promo) || $promo === '')
                                        <span class="tf-chip tf-chip-muted">None</span>
                                    @else
                                        <span class="tf-chip tf-chip-info">{{ $promo }}</span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <span class="tf-chip tf-chip-soft">{{ $o['PromotionPeriod'] ?? '-' }}</span>
                                </td>

                                <td class="text-end">
                                    <span class="tf-chip tf-chip-money">
                                        ${{ number_format((float)($o['PromotionCost'] ?? 0), 2) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <span class="tf-chip tf-chip-soft">{{ number_format((float)($o['MDR'] ?? 0), 2) }}</span>
                                </td>

                                <td>
                                    <span class="tf-chip tf-chip-soft">{{ $o['MDRType'] ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-muted">No offers available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<div class="modal fade" id="pfModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('tfc.pricing-factor.store') }}">
                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Get Pricing Factor</h5>
                        <small class="text-muted">Enter applicant details to generate pricing and offers.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">{{ $errors->first() }}</div>
                    @endif

                    <div class="row g-3">
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
                                <input class="form-control" name="ProductInformation" value="{{ old('ProductInformation') }}" required maxlength="100" placeholder="Jewelry">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">SSN</label>
                                <input class="form-control" name="SSN" value="{{ old('SSN') }}" maxlength="9" placeholder="9 digits">
                            </div>
                        </div>

                        @php
                            $dobOld = old('DOB');
                            $dobPickerValue = '';
                            try {
                                if (!empty($dobOld)) {
                                    $dobPickerValue = \Carbon\Carbon::createFromFormat('m/d/Y', $dobOld)->format('Y-m-d');
                                }
                            } catch (\Throwable $e) {
                                $dobPickerValue = '';
                            }
                        @endphp

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">DOB</label>
                                <input
                                    type="date"
                                    class="form-control tf-datepicker"
                                    id="pf_DOB_picker"
                                    value="{{ $dobPickerValue }}"
                                    data-date-target="pf_DOB"
                                />
                                <input type="hidden" name="DOB" id="pf_DOB" value="{{ old('DOB') }}">
                                <small class="form-text text-muted">MM/DD/YYYY</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Gross Income</label>
                                <input class="form-control" name="GrossIncome" value="{{ old('GrossIncome') }}" placeholder="4200">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Fingerprint</label>
                                <input class="form-control" name="Fingerprint" value="{{ old('Fingerprint') }}" maxlength="256">
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
@if ($errors->any())
<script>
    const modal = new bootstrap.Modal(document.getElementById('pfModal'));
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

    const pfModal = document.getElementById('pfModal');
    if (pfModal) {
        pfModal.addEventListener('shown.bs.modal', function () {
            syncDateInputs(pfModal);
        });
    }
})();
</script>
@endpush
