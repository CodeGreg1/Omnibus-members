<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $pageTitle }}</title>

        @if (setting('google_nalytics_code'))
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_nalytics_code') }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ setting('google_nalytics_code') }}');
            </script>
        @endif

        @section('meta')

        @show

        <link rel="shortcut icon" type="image/x-icon" href="{!! setting('favicon') !!}">

        <script type="text/javascript">
            if(top != self){
                top.location.replace(document.location);
            }
        @if (isset($appCurrencies))
            window.currencies = @json($appCurrencies);
        @endif
    </script>

        @include('partials.site-styles')
    </head>
    <body class="@yield('page-class') {{ color_scheme(isset($page) ? $page : null) }}">
        <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

        @include('partials.header')

        @include('partials.content-header')

        @include('partials.content')

        @include('partials.cookie-consent')

        @include('partials.footer')

        <a href="#" class="scroll-top">
            <i class="lni lni-arrow-up"></i>
        </a>
        
        @include('partials.site-scripts')
    </body>
</html>
