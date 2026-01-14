<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', config('app.name') . ' - Admin')</title>

    <link rel="icon" href="{{ asset('images/logo/logo.png') }}" type="image/x-icon" />

    {{-- Fonts and icons (Kaiadmin uses WebFont Loader) --}}
    <script src="{{ asset(config('theme.path') . '/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ asset(config('theme.path') . '/assets/css/fonts.min.css') }}"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    {{-- CSS Files --}}
    <link rel="stylesheet" href="{{ asset(config('theme.path') . '/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset(config('theme.path') . '/assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset(config('theme.path') . '/assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ asset(config('theme.path') . '/assets/css/app-custom.css') }}" />

    {{-- Optional: add your own app overrides after the theme --}}
    @stack('styles')
</head>
<body>
<div class="wrapper">
    {{-- Sidebar --}}
    @include('partials.terrace-finance.sidebar')

    <div class="main-panel">
        {{-- Topbar/Header --}}
        @include('partials.terrace-finance.topbar')

        <div class="container">
            <div class="page-inner">
                {{-- Page header / breadcrumbs --}}
                @hasSection('page_header')
                    @yield('page_header')
                @else
                    <x-terrace-finance.page-header :title="trim($__env->yieldContent('page_title', 'Dashboard'))" />
                @endif

                {{-- Flash messages (optional) --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Page content --}}
                @yield('content')
            </div>
        </div>

        @include('partials.terrace-finance.footer')
    </div>
</div>

{{-- Core JS Files (minimal, stable base) --}}
<script src="{{ asset(config('theme.path') . '/assets/js/core/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset(config('theme.path') . '/assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset(config('theme.path') . '/assets/js/core/bootstrap.min.js') }}"></script>

{{-- Required by Kaiadmin for sidebar scroll, etc. --}}
<script src="{{ asset(config('theme.path') . '/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

{{-- Kaiadmin core --}}
<script src="{{ asset(config('theme.path') . '/assets/js/kaiadmin.min.js') }}"></script>

{{-- Optional plugins per-page --}}
@stack('plugin-scripts')

{{-- Page-specific scripts --}}
@stack('scripts')
</body>
</html>
