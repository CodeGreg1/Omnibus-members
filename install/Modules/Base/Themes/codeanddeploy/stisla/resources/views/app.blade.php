<!DOCTYPE html>
<html lang="en" id="{{ $pageId ?? '' }}" class="stisla-theme">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} - {{ config('app.name', 'Laravel') }}</title>

    <!--Favicon -->
    <link rel="icon" href="{!! setting('favicon') !!}" type="image/x-icon"/>

    @include('partials.theme-styles')
    
    <script type="text/javascript">
        if(top != self){
            top.location.replace(document.location);
        }

        @if (isset($appCurrencies))
            window.currencies = @json($appCurrencies);
        @endif
    </script>
    
    @include('partials.app-styles')
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            
            @include('partials.navbar')

            @include('partials.sidebar')

            <!-- Main Content -->
            <div class="main-content{{ isset($removePageHeader) ? ' no-page-header-content' : '' }}">
                <section class="section @yield('custom-section-class')">
                    @include('partials.content-header')

                    <div class="section-body" id="@yield('module-name')">
                        @include('partials.content')
                    </div>
                </section>
            </div>
            <footer class="main-footer">
                <div class="footer-left">
                    @lang('Copyright') {{date('Y')}} &copy; Developed By <a href="https://koolscripts.com">KoolScripts</a>
                </div>
                <div class="footer-right">
                    1.0.0
                </div>
            </footer>
        </div>
    </div>

    @include('partials.modals')

    @include('partials.theme-scripts')

    @include('partials.app-scripts')
</body>
</html>
