
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} - {{ config('app.name', 'Laravel') }}</title>

    <!--Favicon -->
    <link rel="icon" href="{!! setting('favicon') !!}" type="image/x-icon"/>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ url('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">

    <!-- CSS Libraries -->
    @yield('module-styles')

    <!-- Template CSS -->
    {!! theme_style('assets/css/style.css') !!}
    {!! theme_style('assets/css/components.css') !!}

    <!-- App Style -->
    <link rel="stylesheet" href="{{ url('css/checkout.css') }}">

    <script>
        @if (isset($appCurrencies))
            window.currencies = @json($appCurrencies);
        @endif
    </script>
</head>

<body class="checkout">
    <div id="app">
        @include('partials.content')
    </div>

    @section('modals')

    @show

    <!-- General JS Scripts -->
    <script src="{{ url('plugins/jquery/js/jquery-3.6.4.min.js') }}"></script>
    <script src="{{ url('plugins/popper/js/popper.min.js') }}"></script>
    <script src="{{ url('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('plugins/jquery-nicescroll/js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ url('plugins/moment/js/moment.min.js') }}"></script>
    <script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- JS Local -->
    {!! theme_script('assets/js/stisla.js') !!}

    <!-- JS Libraies -->
    @yield('module-scripts')

    <!-- Template JS File -->
    {!! theme_script('assets/js/scripts.js') !!}

    <script>
        var app = {};

        app.translations = {!! Translations::all() !!};

        @section('policies')

        @show
    </script>
    <!-- App Script -->
    <script src="{{ url(mix('js/app.min.js')) }}"></script>

    <!-- Custom Scripts -->
    @yield('scripts')

</body>
</html>
