@extends('layouts.terrace-finance.app')

@section('title', 'Terrace Finance Test')
@section('page_title', 'Terrace Finance Test')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><div class="card-title">CSS Test</div></div>
                <div class="card-body">
                    <button class="btn btn-primary">Primary Button</button>
                    <button class="btn btn-success">Success Button</button>
                    <div class="mt-3 alert alert-info mb-0">
                        If this looks styled like the template, CSS is loading.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><div class="card-title">JS Test</div></div>
                <div class="card-body">
                    <button id="jsTestBtn" class="btn btn-danger">Click me</button>
                    <div id="jsTestResult" class="mt-3 text-muted">Waiting…</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('jsTestBtn')?.addEventListener('click', function () {
        document.getElementById('jsTestResult').innerHTML = '✅ JS is working (click handler fired).';
    });
</script>
@endpush
