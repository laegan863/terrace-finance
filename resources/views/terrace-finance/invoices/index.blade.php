@extends('layouts.terrace-finance.app')

@section('title', 'Invoices')
@section('page_title', 'Invoices')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h4 class="mb-0">Post Invoice</h4>
        <small class="text-muted">Send invoice and cart details for an application or lead.</small>
    </div>

    <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('tfc.invoices.history') }}">
            <i class="fas fa-list me-1"></i> Invoice History
        </a>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#invoiceModal">
            <i class="fas fa-plus me-1"></i> New Invoice
        </button>
    </div>

</div>

@php
    $req = $result['request'] ?? null;
    $res = $result['response'] ?? null;

    $isSuccess = (bool)($res['IsSuccess'] ?? false);
    $message = (string)($res['Message'] ?? '');

    $resultObj = $res['Result'] ?? [];
    $applicationId = $resultObj['ApplicationID'] ?? '-';
    $applicationStatus = $resultObj['ApplicationStatus'] ?? '-';
    $approvalAmount = $resultObj['ApprovalAmount'] ?? null;
    $approvalAmountDisplay = is_null($approvalAmount) ? '-' : '$' . number_format((float)$approvalAmount, 2);

    $lender = $resultObj['Lender'] ?? [];
    $lenderName = $lender['LenderName'] ?? '-';

    $signingUrl = $resultObj['SigningUrl'] ?? null;
    $offers = $resultObj['Offer'] ?? [];

    $payloadKey = 'invoicePayload';
@endphp

@if($req && $res)
    @php
        $invoiceNumber = $req['InvoiceNumber'] ?? '-';
        $invoiceDate = $req['InvoiceDate'] ?? '-';
        $deliveryDate = $req['DeliveryDate'] ?? '-';
    @endphp

    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-receipt"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Invoice Number</p>
                                <h4 class="card-title">{{ $invoiceNumber }}</h4>
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
                                <i class="fas fa-hashtag"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Application Identifier</p>
                                <h4 class="card-title">{{ $applicationId }}</h4>
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
                                <h4 class="card-title">{{ $approvalAmountDisplay }}</h4>
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

    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <div class="card-title mb-0"><strong>{{ $isSuccess ? 'Success' : 'Failed' }}</strong></div>
                {{ $message }}
            </div>

            <div class="d-flex gap-2">
                @if(!empty($signingUrl))
                    <a class="btn btn-sm btn-primary" href="{{ $signingUrl }}" target="_blank" rel="noopener">
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
                            <th style="width: 220px;">Invoice Date</th>
                            <td><span class="tf-chip tf-chip-soft">{{ $invoiceDate }}</span></td>

                            <th style="width: 220px;">Delivery Date</th>
                            <td><span class="tf-chip tf-chip-soft">{{ $deliveryDate }}</span></td>
                        </tr>

                        <tr>
                            <th>Application Status</th>
                            <td colspan="3">
                                <span class="tf-chip tf-chip-info">{{ $applicationStatus }}</span>
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

    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <div class="card-title mb-0">Invoice Items</div>
                <small class="text-muted">Items included in the invoice</small>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle tf-table">
                    <thead>
                        <tr>
                            <th title="Item Description">Item Description</th>
                            <th title="Brand">Brand</th>
                            <th title="Serial Number">Serial Number</th>
                            <th title="Stock Keeping Unit">Stock Keeping Unit</th>
                            <th title="Condition">Condition</th>
                            <th class="text-end" title="Price">Price</th>
                            <th class="text-end" title="Quantity">Quantity</th>
                            <th class="text-end" title="Discount">Discount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($req['Items'] ?? []) as $it)
                            <tr>
                                <td>{{ $it['ItemDescription'] ?? '-' }}</td>
                                <td><span class="tf-chip tf-chip-soft">{{ $it['Brand'] ?? '-' }}</span></td>
                                <td>{{ $it['SerialNumber'] ?? '-' }}</td>
                                <td><span class="tf-chip tf-chip-info">{{ $it['SKU'] ?? '-' }}</span></td>
                                <td><span class="tf-chip tf-chip-soft">{{ $it['Condition'] ?? '-' }}</span></td>
                                <td class="text-end"><span class="tf-chip tf-chip-money">${{ number_format((float)($it['Price'] ?? 0), 2) }}</span></td>
                                <td class="text-end"><span class="tf-chip tf-chip-soft">{{ $it['Quantity'] ?? '-' }}</span></td>
                                <td class="text-end">${{ number_format((float)($it['Discount'] ?? 0), 2) }}</td>
                            </tr>
                        @endforeach
                        @if(empty($req['Items']))
                            <tr><td colspan="8" class="text-muted">No items available.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <div class="card-title mb-0">Offers</div>
                <small class="text-muted">Offers returned with the invoice response</small>
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


<div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('tfc.invoices.store') }}">
                @csrf

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0">Post Invoice</h5>
                        <small class="text-muted">Provide invoice details and invoice items.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">{{ $errors->first() }}</div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Invoice Number</label>
                                <input class="form-control" name="InvoiceNumber" value="{{ old('InvoiceNumber') }}" required maxlength="256">
                            </div>
                        </div>

                        @php
                            $invoiceDateOld = old('InvoiceDate');
                            $invoiceDatePicker = '';
                            try {
                                if (!empty($invoiceDateOld)) {
                                    $invoiceDatePicker = \Carbon\Carbon::createFromFormat('m-d-Y', $invoiceDateOld)->format('Y-m-d');
                                }
                            } catch (\Throwable $e) {
                                $invoiceDatePicker = '';
                            }

                            $deliveryDateOld = old('DeliveryDate');
                            $deliveryDatePicker = '';
                            try {
                                if (!empty($deliveryDateOld)) {
                                    $deliveryDatePicker = \Carbon\Carbon::createFromFormat('m-d-Y', $deliveryDateOld)->format('Y-m-d');
                                }
                            } catch (\Throwable $e) {
                                $deliveryDatePicker = '';
                            }
                        @endphp

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Invoice Date</label>
                                <input type="date" class="form-control tf-datepicker" id="invoice_InvoiceDate_picker" value="{{ $invoiceDatePicker }}" data-date-target="invoice_InvoiceDate" />
                                <input type="hidden" name="InvoiceDate" id="invoice_InvoiceDate" value="{{ old('InvoiceDate') }}">
                                <small class="form-text text-muted">mm-dd-yyyy</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Delivery Date</label>
                                <input type="date" class="form-control tf-datepicker" id="invoice_DeliveryDate_picker" value="{{ $deliveryDatePicker }}" data-date-target="invoice_DeliveryDate" />
                                <input type="hidden" name="DeliveryDate" id="invoice_DeliveryDate" value="{{ old('DeliveryDate') }}">
                                <small class="form-text text-muted">mm-dd-yyyy</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Application Identifier</label>
                                <input class="form-control" name="ApplicationID" value="{{ old('ApplicationID') }}" placeholder="Provide Application Identifier or Lead Identifier">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-label">Lead Identifier</label>
                                <input class="form-control" name="LeadID" value="{{ old('LeadID') }}" placeholder="Provide Lead Identifier or Application Identifier">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="form-label">Discount</label>
                                <input class="form-control" name="Discount" value="{{ old('Discount') }}" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="form-label">Down Payment</label>
                                <input class="form-control" name="DownPayment" value="{{ old('DownPayment') }}" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="form-label">Shipping</label>
                                <input class="form-control" name="Shipping" value="{{ old('Shipping') }}" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <label class="form-label">Tax</label>
                                <input class="form-control" name="Tax" value="{{ old('Tax') }}" required placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group mb-0">
                                <label class="form-label">Return Uniform Resource Locator</label>
                                <input class="form-control" name="ReturnURL" value="{{ old('ReturnURL') }}" placeholder="https://www.yourwebsite.com/returnpage">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label">Invoice Version</label>
                                <select class="form-control" name="InvoiceVersion">
                                    <option value="" @selected(old('InvoiceVersion')==='')>None</option>
                                    <option value="C" @selected(old('InvoiceVersion')==='C')>Cancellation</option>
                                    <option value="R" @selected(old('InvoiceVersion')==='R')>Replacement</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <h6 class="mb-0">Invoice Items</h6>
                            <small class="text-muted">Add one or more items</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="addInvoiceItem">
                            Add Item
                        </button>
                    </div>

                    <div class="tf-items-table-wrap">
                        <table class="table table-bordered align-middle tf-items-table" id="invoiceItemsTable">
                            <colgroup>
                                <col style="width: 300px;">
                                <col style="width: 180px;">
                                <col style="width: 190px;">
                                <col style="width: 170px;">
                                <col style="width: 170px;">
                                <col style="width: 140px;">
                                <col style="width: 110px;">
                                <col style="width: 140px;">
                                <col style="width: 120px;">
                            </colgroup>

                            <thead>
                                <tr>
                                    <th>Item Description</th>
                                    <th>Brand</th>
                                    <th>Serial Number</th>
                                    <th>Stock Keeping Unit</th>
                                    <th>Condition</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Quantity</th>
                                    <th class="text-end">Discount</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr class="invoice-item-row">
                                    <td class="tf-col-description">
                                        <input class="form-control" name="Items[0][ItemDescription]" required>
                                    </td>

                                    <td class="tf-col-brand">
                                        <input class="form-control" name="Items[0][Brand]" required>
                                    </td>

                                    <td class="tf-col-serial">
                                        <input class="form-control" name="Items[0][SerialNumber]">
                                    </td>

                                    <td class="tf-col-sku">
                                        <input class="form-control" name="Items[0][SKU]" required>
                                    </td>

                                    <td class="tf-col-condition">
                                        <select class="form-select" name="Items[0][Condition]" required>
                                            <option value="New">New</option>
                                            <option value="Used">Used</option>
                                            <option value="CPO">CPO</option>
                                            <option value="Refurbished">Refurbished</option>
                                            <option value="Parts">Parts</option>
                                            <option value="Salvage">Salvage</option>
                                        </select>
                                    </td>

                                    <td class="tf-col-price">
                                        <input class="form-control text-end" name="Items[0][Price]" required placeholder="0.00">
                                    </td>

                                    <td class="tf-col-qty">
                                        <input class="form-control text-end" name="Items[0][Quantity]" required value="1">
                                    </td>

                                    <td class="tf-col-discount">
                                        <input class="form-control text-end" name="Items[0][Discount]" placeholder="0.00">
                                    </td>

                                    <td class="text-end tf-col-action">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-item" disabled>
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
    const modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
    modal.show();
</script>
@endif

<script>
(function () {
    function toApiDateDash(ymd) {
        if (!ymd) return '';
        const parts = ymd.split('-');
        if (parts.length !== 3) return '';
        const y = parts[0];
        const m = parts[1];
        const d = parts[2];
        return m + '-' + d + '-' + y;
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
                hidden.value = toApiDateDash(picker.value);
            };

            picker.addEventListener('change', sync);
            picker.addEventListener('blur', sync);

            picker.addEventListener('click', function () { openNativePicker(picker); });
            picker.addEventListener('focus', function () { openNativePicker(picker); });

            sync();
        });
    }

    function bindInvoiceItems() {
        const tableBody = document.querySelector('#invoiceItemsTable tbody');
        const addBtn = document.getElementById('addInvoiceItem');
        if (!tableBody || !addBtn) return;

        function refreshIndexes() {
            const rows = tableBody.querySelectorAll('tr.invoice-item-row');
            rows.forEach(function (row, index) {
                row.querySelectorAll('input, select').forEach(function (el) {
                    const name = el.getAttribute('name');
                    if (!name) return;
                    el.setAttribute('name', name.replace(/Items\[\d+\]/, 'Items[' + index + ']'));
                });

                const removeBtn = row.querySelector('.remove-item');
                if (removeBtn) {
                    removeBtn.disabled = rows.length === 1;
                }
            });
        }

        addBtn.addEventListener('click', function () {
            const rows = tableBody.querySelectorAll('tr.invoice-item-row');
            const last = rows[rows.length - 1];
            const clone = last.cloneNode(true);

            clone.querySelectorAll('input').forEach(function (input) {
                if (input.name.includes('[Quantity]')) {
                    input.value = '1';
                } else if (input.name.includes('[Discount]') || input.name.includes('[Price]')) {
                    input.value = '';
                } else {
                    input.value = '';
                }
            });

            clone.querySelectorAll('select').forEach(function (sel) {
                sel.value = 'New';
            });

            tableBody.appendChild(clone);
            refreshIndexes();
        });

        tableBody.addEventListener('click', function (e) {
            const btn = e.target.closest('.remove-item');
            if (!btn) return;
            const row = btn.closest('tr.invoice-item-row');
            if (!row) return;

            const rows = tableBody.querySelectorAll('tr.invoice-item-row');
            if (rows.length <= 1) return;

            row.remove();
            refreshIndexes();
        });

        refreshIndexes();
    }

    document.addEventListener('DOMContentLoaded', function () {
        syncDateInputs(document);
        bindInvoiceItems();
    });

    const invoiceModal = document.getElementById('invoiceModal');
    if (invoiceModal) {
        invoiceModal.addEventListener('shown.bs.modal', function () {
            syncDateInputs(invoiceModal);
        });
    }
})();
</script>
@endpush
