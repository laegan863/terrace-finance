@php($theme = config('theme.path'))
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'Login') - Terrace Finance</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />

    <link rel="icon" href="{{ asset('images/logo/logo.png') }}" type="image/x-icon" />

    {{-- Fonts and icons (same as template pages like forms.html) --}}
    <script src="{{ asset($theme . '/assets/js/plugin/webfont/webfont.min.js') }}"></script>
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
                urls: ["{{ asset($theme . '/assets/css/fonts.min.css') }}"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    {{-- CSS Files --}}
    <link rel="stylesheet" href="{{ asset($theme . '/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset($theme . '/assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset($theme . '/assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ asset($theme . '/assets/css/app-custom.css') }}" />

    @stack('styles')
</head>

<body class="bg-light tf-login-bg">
    <div class="tf-login-overlay">
        <div class="container d-flex flex-column flex-wrap justify-content-center vh-100">
            <div class="page-inner py-5">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Core JS (minimal for login) --}}
    <script src="{{ asset($theme . '/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset($theme . '/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset($theme . '/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset($theme . '/assets/js/kaiadmin.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
