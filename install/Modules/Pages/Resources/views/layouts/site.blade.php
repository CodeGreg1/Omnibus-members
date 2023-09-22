@extends('site')

@section("styles")
    <link rel="stylesheet" href="{{ url('css/site/pages.min.css') }}" media="all" type="text/css">
@stop

@section("content")

@stop

@section("scripts")
    <script src="{{ url('js/site/pages.min.js') }}"></script>

    @if (setting('recaptcha_site_key') && setting('recaptcha_secret_key'))
        {!! ReCaptcha::htmlScriptTagJsApi() !!}
    @endif
@stop
