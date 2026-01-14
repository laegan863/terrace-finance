@extends('layouts.terrace-finance.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <small class="text-muted">Overview of applications, invoices, offers, and notifications.</small>
    </div>
</div>

@php
    $totalApplications = 128;
    $approvedCount = 42;
    $pendingCount = 19;
    $fundedTotal = 85274.50;

    $approvedRows = [
        [
            'ApplicationID' => 99044,
            'Applicant' => 'John Doe',
            'ProductInformation' => 'Tire',
            'ApprovalAmount' => 5000.00,
            'Status' => 'Approved',
            'CreatedAt' => 'Jan 13, 2026 09:10 AM',
        ],
        [
            'ApplicationID' => 99012,
            'Applicant' => 'Maria Santos',
            'ProductInformation' => 'Jewelry',
            'ApprovalAmount' => 2500.00,
            'Status' => 'Approved',
            'CreatedAt' => 'Jan 12, 2026 04:25 PM',
        ],
        [
            'ApplicationID' => 98988,
            'Applicant' => 'Alex Rivera',
            'ProductInformation' => 'Home',
            'ApprovalAmount' => 3200.00,
            'Status' => 'Approved',
            'CreatedAt' => 'Jan 12, 2026 10:02 AM',
        ],
    ];

    $pendingRows = [
        [
            'ApplicationID' => 99111,
            'Applicant' => 'Kim Lee',
            'ProductInformation' => 'Electronics',
            'ApprovalAmount' => 0,
            'Status' => 'Pending Signature',
            'CreatedAt' => 'Jan 13, 2026 11:35 AM',
        ],
        [
            'ApplicationID' => 99105,
            'Applicant' => 'Paolo Reyes',
            'ProductInformation' => 'Vehicle',
            'ApprovalAmount' => 0,
            'Status' => 'Pending Invoice',
            'CreatedAt' => 'Jan 13, 2026 10:48 AM',
        ],
        [
            'ApplicationID' => 99099,
            'Applicant' => 'Ana Cruz',
            'ProductInformation' => 'Medical Equipment',
            'ApprovalAmount' => 0,
            'Status' => 'Pending Docs',
            'CreatedAt' => 'Jan 12, 2026 06:12 PM',
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
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Applications</p>
                            <h4 class="card-title">{{ $totalApplications }}</h4>
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
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Approved</p>
                            <h4 class="card-title">{{ $approvedCount }}</h4>
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
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Pending</p>
                            <h4 class="card-title">{{ $pendingCount }}</h4>
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
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                            <p class="card-category">Total Funded Amount</p>
                            <h4 class="card-title">${{ number_format($fundedTotal, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-0">
    <div class="col-lg-6">
        <div class="card mt-3 mt-lg-0">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <div class="card-title mb-0">Approved Applications</div>
                    <small class="text-muted">Latest approved applications</small>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle tf-table">
                        <thead>
                            <tr>
                                <th title="Application Identifier">Application Identifier</th>
                                <th title="Applicant">Applicant</th>
                                <th title="Product Information">Product Information</th>
                                <th class="text-end" title="Approval Amount">Approval Amount</th>
                                <th title="Status">Status</th>
                                <th title="Created At">Created At</th>
                                <th class="text-end" title="Action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedRows as $r)
                                <tr>
                                    <td><span class="tf-chip tf-chip-soft">{{ $r['ApplicationID'] }}</span></td>
                                    <td>{{ $r['Applicant'] }}</td>
                                    <td><span class="tf-chip tf-chip-info">{{ $r['ProductInformation'] }}</span></td>
                                    <td class="text-end">
                                        <span class="tf-chip tf-chip-money">${{ number_format((float)$r['ApprovalAmount'], 2) }}</span>
                                    </td>
                                    <td><span class="tf-chip tf-chip-soft">{{ $r['Status'] }}</span></td>
                                    <td class="text-muted">{{ $r['CreatedAt'] }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" disabled>View</button>
                                    </td>
                                </tr>
                            @endforeach
                            @if(empty($approvedRows))
                                <tr>
                                    <td colspan="7" class="text-muted">No approved applications available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mt-3 mt-lg-0">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <div class="card-title mb-0">Pending Applications</div>
                    <small class="text-muted">Applications requiring action</small>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle tf-table">
                        <thead>
                            <tr>
                                <th title="Application Identifier">Application Identifier</th>
                                <th title="Applicant">Applicant</th>
                                <th title="Product Information">Product Information</th>
                                <th title="Status">Status</th>
                                <th title="Created At">Created At</th>
                                <th class="text-end" title="Action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingRows as $r)
                                <tr>
                                    <td><span class="tf-chip tf-chip-soft">{{ $r['ApplicationID'] }}</span></td>
                                    <td>{{ $r['Applicant'] }}</td>
                                    <td><span class="tf-chip tf-chip-info">{{ $r['ProductInformation'] }}</span></td>
                                    <td>
                                        <span class="tf-chip tf-chip-warning">{{ $r['Status'] }}</span>
                                    </td>
                                    <td class="text-muted">{{ $r['CreatedAt'] }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" disabled>View</button>
                                    </td>
                                </tr>
                            @endforeach
                            @if(empty($pendingRows))
                                <tr>
                                    <td colspan="6" class="text-muted">No pending applications available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
