@extends('layouts.terrace-finance.app')

@section('title', 'Offer History')
@section('page_title', 'Offers')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Offer History</h4>
        <small class="text-muted">Saved offer submissions.</small>
    </div>

    <a class="btn btn-primary" href="{{ route('tfc.offers.index') }}">
        <i class="fas fa-arrow-left me-1"></i> Back to Post Offer
    </a>
</div>

<div class="card mt-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <div class="card-title mb-0">Offers</div>
            <small class="text-muted">Latest records</small>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle tf-table">
                <thead>
                    <tr>
                        <th>Application Identifier</th>
                        <th>Offer</th>
                        <th>Bank Name</th>
                        <th>Account Type</th>
                        <th>Signing Uniform Resource Locator</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $r)
                        <tr>
                            <td><span class="tf-chip tf-chip-soft">{{ $r['ApplicationID'] }}</span></td>
                            <td><span class="tf-chip tf-chip-info">{{ $r['Offer'] }}</span></td>
                            <td>{{ $r['BankName'] }}</td>
                            <td>{{ $r['AccountType'] }}</td>
                            <td class="text-muted" style="max-width: 420px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $r['ResultUrl'] }}
                            </td>
                            <td class="text-muted">{{ $r['CreatedAt'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">No offers available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
