<!DOCTYPE html>
<html lang="en" class="stisla-theme">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} - {{ config('app.name', 'Laravel') }}</title>

    <!--Favicon -->
    <link rel="icon" href="{!! setting('favicon') !!}" type="image/x-icon">

    @include('partials.theme-styles')
    
    <script type="text/javascript">
        if(top != self){
            top.location.replace(document.location);
        }
    </script>
        
    @include('partials.app-styles')
</head>

<body>
    <div id="app">
        @yield('content')
    </div>

    @include('partials.modals')
    
    @include('partials.theme-scripts')

    @include('partials.app-scripts')
</body>
</html>