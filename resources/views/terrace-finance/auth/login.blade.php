@extends('layouts.terrace-finance.guest')

@section('title', 'Merchant Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12 col-lg-5">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('auth_error'))
            <div class="alert alert-danger">
                {{ session('auth_error') }}
            </div>
        @endif


        <div class="card">
            <div class="card-header">
                <div class="card-title text-center">Terrace Finance Login</div>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('tfc.login.submit') }}">
                    @csrf

                    <div class="form-group">
                        <label for="UserName">UserName</label>
                        <input
                            type="text"
                            class="form-control"
                            id="UserName"
                            name="UserName"
                            value="{{ old('UserName') }}"
                            placeholder="Enter UserName"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <small class="form-text text-muted">
                            Use your merchant username provided by Terrace Finance.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="Password">Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="Password"
                            name="Password"
                            placeholder="Password"
                            required
                            autocomplete="current-password"
                        />
                    </div>

                    <div class="form-group form-action-d-flex mb-0">
                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>
                    </div>

                    <div class="mt-3 text-muted small text-center">
                        Token expires in ~30 minutes.
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
