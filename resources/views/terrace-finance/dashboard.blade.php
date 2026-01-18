@extends('layouts.terrace-finance.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Dashboard</h4>
        <small class="text-muted">Overview of applications, invoices, offers, pricing factor, and status notifications.</small>
    </div>
</div>

@php
    // Counts (DB-backed)
    $applicationsTotal = class_exists(\App\Models\ApplicationRequest::class)
        ? \App\Models\ApplicationRequest::count()
        : 0;

    $invoicesTotal = class_exists(\App\Models\InvoiceRequest::class)
        ? \App\Models\InvoiceRequest::count()
        : 0;

    $offersTotal = class_exists(\App\Models\OfferRequest::class)
        ? \App\Models\OfferRequest::count()
        : 0;

    $notificationsTotal = class_exists(\App\Models\StatusNotificationRequest::class)
        ? \App\Models\StatusNotificationRequest::count()
        : 0;

    // Optional: total funded amount from status notifications
    $fundedTotal = class_exists(\App\Models\StatusNotificationRequest::class)
        ? (float) \App\Models\StatusNotificationRequest::whereNotNull('FundedAmount')->sum('FundedAmount')
        : 0.00;

    // Application lists (DB-backed)
    $successApps = class_exists(\App\Models\ApplicationRequest::class)
        ? \App\Models\ApplicationRequest::with('result')
            ->where('status', 'success')
            ->orderByDesc('id')
            ->take(6)
            ->get()
        : collect();

    $pendingApps = class_exists(\App\Models\ApplicationRequest::class)
        ? \App\Models\ApplicationRequest::with('result')
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->take(6)
            ->get()
        : collect();

    // Fallback samples if empty
    $sampleSuccess = [
        [
            'ApplicationID' => 99044,
            'Applicant' => 'John Doe',
            'ProductInformation' => 'Tire',
            'Amount' => 5000.00,
            'Status' => 'success',
            'CreatedAt' => 'Jan 13, 2026 09:10 AM',
        ],
    ];

    $samplePending = [
        [
            'ApplicationID' => 99111,
            'Applicant' => 'Kim Lee',
            'ProductInformation' => 'Electronics',
            'StatusLabel' => 'Pending Signature',
            'Status' => 'pending',
            'CreatedAt' => 'Jan 13, 2026 11:35 AM',
        ],
    ];
@endphp

<div class="row">
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-icon">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <i class="fas fa-file-signature"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Applications</p>
                            <h4 class="card-title">{{ $applicationsTotal }}</h4>
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
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Invoices</p>
                            <h4 class="card-title">{{ $invoicesTotal }}</h4>
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
                            <i class="fas fa-handshake"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Offers</p>
                            <h4 class="card-title">{{ $offersTotal }}</h4>
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
                        <div class="icon-big text-center icon-warning bubble-shadow-small">
                            <i class="fas fa-bell"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Status Notifications</p>
                            <h4 class="card-title">{{ $notificationsTotal }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Optional funded total --}}
<div class="card mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <tbody>
                    <tr>
                        <th style="width: 220px;">Total Funded Amount</th>
                        <td><span class="tf-chip tf-chip-money">${{ number_format($fundedTotal, 2) }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-3 mt-0">
    {{-- Successful Applications --}}
    <div class="col-lg-6">
        <div class="card mt-3 mt-lg-0">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <div class="card-title mb-0">Successful Applications</div>
                    <small class="text-muted">Latest saved application submissions with success status</small>
                </div>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('tfc.applications.index') }}">
                    Open Applications
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle tf-table">
                        <thead>
                            <tr>
                                <th>Application Identifier</th>
                                <th>Applicant</th>
                                <th>Product Information</th>
                                <th class="text-end">Requested Amount</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($successApps->count() > 0)
                                @foreach($successApps as $a)
                                    @php
                                        $appId = $a->result->response['Result'] ?? $a->id;
                                        $applicant = $a->FirstName . ' ' . $a->LastName;
                                        $amount = (float) $a->BestEstimate;

                                        $statusChip = 'tf-chip-money';
                                    @endphp
                                    <tr>
                                        <td><span class="tf-chip tf-chip-soft">{{ $appId }}</span></td>
                                        <td>{{ $applicant }}</td>
                                        <td><span class="tf-chip tf-chip-info">{{ $a->ProductInformation }}</span></td>
                                        <td class="text-end"><span class="tf-chip tf-chip-money">${{ number_format($amount, 2) }}</span></td>
                                        <td><span class="tf-chip {{ $statusChip }}">Success</span></td>
                                        <td class="text-muted">{{ optional($a->created_at)->format('Y-m-d H:i') }}</td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-primary"
                                               href="{{ route('tfc.application-status.index', ['ApplicationID' => $appId]) }}">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach($sampleSuccess as $r)
                                    <tr>
                                        <td><span class="tf-chip tf-chip-soft">{{ $r['ApplicationID'] }}</span></td>
                                        <td>{{ $r['Applicant'] }}</td>
                                        <td><span class="tf-chip tf-chip-info">{{ $r['ProductInformation'] }}</span></td>
                                        <td class="text-end"><span class="tf-chip tf-chip-money">${{ number_format((float)$r['Amount'], 2) }}</span></td>
                                        <td><span class="tf-chip tf-chip-money">{{ ucfirst($r['Status']) }}</span></td>
                                        <td class="text-muted">{{ $r['CreatedAt'] }}</td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-primary"
                                               href="{{ route('tfc.application-status.index', ['ApplicationID' => $r['ApplicationID']]) }}">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Applications --}}
    <div class="col-lg-6">
        <div class="card mt-3 mt-lg-0">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <div class="card-title mb-0">Pending Applications</div>
                    <small class="text-muted">Applications waiting for completion</small>
                </div>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('tfc.applications.index') }}">
                    Open Applications
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle tf-table">
                        <thead>
                            <tr>
                                <th>Application Identifier</th>
                                <th>Applicant</th>
                                <th>Product Information</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($pendingApps->count() > 0)
                                @foreach($pendingApps as $a)
                                    @php
                                        $appId = $a->result->response['Result'] ?? $a->id;
                                        $applicant = $a->FirstName . ' ' . $a->LastName;
                                    @endphp
                                    <tr>
                                        <td><span class="tf-chip tf-chip-soft">{{ $appId }}</span></td>
                                        <td>{{ $applicant }}</td>
                                        <td><span class="tf-chip tf-chip-info">{{ $a->ProductInformation }}</span></td>
                                        <td><span class="tf-chip tf-chip-warning">Pending</span></td>
                                        <td class="text-muted">{{ optional($a->created_at)->format('Y-m-d H:i') }}</td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-primary"
                                               href="{{ route('tfc.application-status.index', ['ApplicationID' => $appId]) }}">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach($samplePending as $r)
                                    <tr>
                                        <td><span class="tf-chip tf-chip-soft">{{ $r['ApplicationID'] }}</span></td>
                                        <td>{{ $r['Applicant'] }}</td>
                                        <td><span class="tf-chip tf-chip-info">{{ $r['ProductInformation'] }}</span></td>
                                        <td><span class="tf-chip tf-chip-warning">{{ $r['StatusLabel'] }}</span></td>
                                        <td class="text-muted">{{ $r['CreatedAt'] }}</td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-primary"
                                               href="{{ route('tfc.application-status.index', ['ApplicationID' => $r['ApplicationID']]) }}">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <small class="text-muted d-block mt-2">
                    Pending is based on saved request status. Application status details can be checked through Retrieve Application Status.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
