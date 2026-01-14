@extends('layouts.terrace-finance.app')

@section('title', 'Application Status')
@section('page_title', 'Application Status')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Retrieve Application Status</h4>
        <small class="text-muted">Retrieve the latest status and offers for an application.</small>
    </div>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
        <i class="fas fa-search me-1"></i> New Request
    </button>
</div>

@php
    $req = $result['request'] ?? null;
    $res = $result['response'] ?? null;

    $isSuccess = (bool)($res['IsSuccess'] ?? false);
    $message = (string)($res['Message'] ?? '');
    $payloadKey = 'applicationStatusPayload';

    $resultObj = $res['Result'] ?? [];

    $applicationIdVal = $resultObj['ApplicationID'] ?? '-';
    $applicationStatus = $resultObj['ApplicationStatus'] ?? '-';
    $approvalAmount = $resultObj['ApprovalAmount'] ?? null;
    $lenderName = $resultObj['LenderName'] ?? '-';

    $lenderStatus = $resultObj['LenderStatus'] ?? '-';
    $pricingFactor = $resultObj['PricingFactor'] ?? null;
    $fundedAmount = $resultObj['FundedAmount'] ?? null;
    $fulfilledAmount = $resultObj['FulfilledAmount'] ?? null;
    $selectedOffer = $resultObj['SelectedOffer'] ?? null;

    $signingRoomUrl = $resultObj['SigningRoomURL'] ?? null;

    $approvalDisplay = is_null($approvalAmount) ? '-' : '$' . number_format((float)$approvalAmount, 2);
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
                                <h4 class="card-title">{{ $applicationIdVal }}</h4>
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
                                <p class="card-category">Application Status</p>
                                <h4 class="card-title">{{ $applicationStatus }}</h4>
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
                                <p class="card-category">Approval Amount</p>
                                <h4 class="card-title">{{ $approvalDisplay }}</h4>
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
                                <i class="fas fa-university"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Lender Name</p>
                                <h4 class="card-title">{{ $lenderName }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $pricingFactorDisplay = is_null($pricingFactor) ? '-' : number_format((float)$pricingFactor, 4);
        $fundedAmountDisplay = is_null($fundedAmount) ? '-' : '$' . number_format((float)$fundedAmount, 2);
        $fulfilledAmountDisplay = is_null($fulfilledAmount) ? '-' : '$' . number_format((float)$fulfilledAmount, 2);
        $selectedOfferDisplay = $selectedOffer ?? '-';
    @endphp

    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <div class="card-title mb-0"><strong>{{ $isSuccess ? 'Success' : 'Failed' }}</strong></div>
                {{ $message }}
            </div>

            <div class="d-flex gap-2">
                @if(!empty($signingRoomUrl))
                    <a class="btn btn-sm btn-primary" href="{{ $signingRoomUrl }}" target="_blank" rel="noopener">
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

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th style="width: 220px;">Lender Status</th>
                            <td><span class="tf-chip tf-chip-soft">{{ $lenderStatus }}</span></td>

                            <th style="width: 220px;">Pricing Factor</th>
                            <td><span class="tf-chip tf-chip-soft">{{ $pricingFactorDisplay }}</span></td>
                        </tr>

                        <tr>
                            <th>Funded Amount</th>
                            <td>
                                @if($fundedAmountDisplay === '-')
                                    <span class="tf-chip tf-chip-muted">-</span>
                                @else
                                    <span class="tf-chip tf-chip-money">{{ $fundedAmountDisplay }}</span>
                                @endif
                            </td>

                            <th>Fulfilled Amount</th>
                            <td>
                                @if($fulfilledAmountDisplay === '-')
                                    <span class="tf-chip tf-chip-muted">-</span>
                                @else
                                    <span class="tf-chip tf-chip-money">{{ $fulfilledAmountDisplay }}</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Selected Offer</th>
                            <td colspan="3">
                                <span class="tf-chip tf-chip-info">{{ $selectedOfferDisplay }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

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

    @php
        $offersArray = is_array($offers) ? $offers : [];
        $firstOffer = $offersArray[0] ?? null;

        // Offer schema detection:
        // Core offer schema has PriceSheet/BiWeeklyAmount keys.
        // Redirect offer schema has RedirectUrl/Frequency keys.
        $isCoreOfferSchema = is_array($firstOffer) && (array_key_exists('PriceSheet', $firstOffer) || array_key_exists('BiWeeklyAmount', $firstOffer));
        $isRedirectOfferSchema = is_array($firstOffer) && (array_key_exists('RedirectUrl', $firstOffer) || array_key_exists('Frequency', $firstOffer));
    @endphp

    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <div class="card-title mb-0">Offers</div>
                <small class="text-muted">Offers presented to the consumer by the lender.</small>
            </div>
        </div>

        <div class="card-body">
            @if(empty($offersArray))
                <div class="text-muted">No offers available.</div>
            @elseif($isCoreOfferSchema)
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
                            @foreach($offersArray as $o)
                                @php
                                    $freq = $o['PaymentFrequency'] ?? '-';
                                    $promo = $o['Promotion'] ?? null;
                                @endphp

                                <tr>
                                    <td><span class="tf-chip tf-chip-soft">{{ $o['PriceSheet'] ?? '-' }}</span></td>
                                    <td><span class="tf-chip tf-chip-info">{{ $freq }}</span></td>

                                    <td class="text-end"><span class="tf-chip tf-chip-money">${{ number_format((float)($o['PaymentAmount'] ?? 0), 2) }}</span></td>
                                    <td class="text-end"><span class="tf-chip tf-chip-soft">{{ $o['NumberOfPayments'] ?? '-' }}</span></td>
                                    <td class="text-end"><span class="tf-chip tf-chip-warning">${{ number_format((float)($o['AmountDueAtSigning'] ?? 0), 2) }}</span></td>

                                    <td class="text-end">${{ number_format((float)($o['BiWeeklyAmount'] ?? 0), 2) }}</td>
                                    <td class="text-end">{{ $o['BiWeeklyNumPayments'] ?? '-' }}</td>

                                    <td class="text-end">${{ number_format((float)($o['MonthlyAmount'] ?? 0), 2) }}</td>
                                    <td class="text-end">{{ $o['MonthlyNumPayments'] ?? '-' }}</td>

                                    <td>
                                        @if(is_null($promo) || $promo === '')
                                            <span class="tf-chip tf-chip-muted">None</span>
                                        @else
                                            <span class="tf-chip tf-chip-info">{{ $promo }}</span>
                                        @endif
                                    </td>

                                    <td class="text-end"><span class="tf-chip tf-chip-soft">{{ $o['PromotionPeriod'] ?? '-' }}</span></td>
                                    <td class="text-end"><span class="tf-chip tf-chip-money">${{ number_format((float)($o['PromotionCost'] ?? 0), 2) }}</span></td>

                                    <td class="text-end"><span class="tf-chip tf-chip-soft">{{ number_format((float)($o['MDR'] ?? 0), 2) }}</span></td>
                                    <td><span class="tf-chip tf-chip-soft">{{ $o['MDRType'] ?? '-' }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif($isRedirectOfferSchema)
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle tf-table">
                        <thead>
                            <tr>
                                <th title="Frequency">Frequency</th>
                                <th class="text-end" title="Number Of Payments">Number Of Payments</th>
                                <th class="text-end" title="Regular Payment With Tax">Regular Payment With Tax</th>
                                <th class="text-end" title="Total Contract Amount With Tax">Total Contract Amount With Tax</th>
                                <th class="text-end" title="Total Contract Amount Without Tax">Total Contract Amount Without Tax</th>
                                <th class="text-end" title="First Payment With Fees And Tax">First Payment With Fees And Tax</th>
                                <th class="text-end" title="First Payment With Fees Without Tax">First Payment With Fees Without Tax</th>
                                <th title="First Payment Date">First Payment Date</th>
                                <th class="text-end" title="Payment Due Today">Payment Due Today</th>
                                <th title="Redirect Uniform Resource Locator">Redirect Uniform Resource Locator</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offersArray as $o)
                                <tr>
                                    <td><span class="tf-chip tf-chip-info">{{ $o['Frequency'] ?? '-' }}</span></td>
                                    <td class="text-end"><span class="tf-chip tf-chip-soft">{{ $o['NumberOfPayments'] ?? '-' }}</span></td>

                                    <td class="text-end">${{ number_format((float)($o['RegularPaymentWithTax'] ?? 0), 2) }}</td>
                                    <td class="text-end">${{ number_format((float)($o['TotalContractAmountWithTax'] ?? 0), 2) }}</td>
                                    <td class="text-end">${{ number_format((float)($o['TotalContractAmountNoTax'] ?? 0), 2) }}</td>

                                    <td class="text-end">${{ number_format((float)($o['FirstPaymentWithFeesAndTax'] ?? 0), 2) }}</td>
                                    <td class="text-end">${{ number_format((float)($o['FirstPaymentWithFeesNoTax'] ?? 0), 2) }}</td>

                                    <td>{{ $o['FirstPaymentDate'] ?? '-' }}</td>
                                    <td class="text-end">${{ number_format((float)($o['PaymentDueToday'] ?? 0), 2) }}</td>

                                    <td class="text-muted" style="max-width: 520px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $o['RedirectUrl'] ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    Offer format is not recognized. Showing raw offer payload.
                    <pre class="mb-0 mt-2" style="white-space: pre-wrap;">{{ json_encode($offersArray, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif
        </div>
    </div>
@endif

<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="GET" action="{{ route('tfc.application-status.index') }}">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Retrieve Application Status</h5>
                        <small class="text-muted">Enter an application identifier to retrieve the latest status.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <label class="form-label">Application Identifier</label>
                                <input class="form-control" name="ApplicationID" value="{{ request('ApplicationID', $applicationId) }}" required>
                                <small class="form-text text-muted">Example identifiers: 80741, 80766</small>
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
