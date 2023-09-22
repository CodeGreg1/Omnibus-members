@if(setting('frontend_cookie_status') == 'enabled' && !Cookie::has('cookie-consent'))
<div class="cookie-consent" id="cookie-consent-box">
    <div class="container">
        <div class="cookie-header mb-2">
            <h5 class="text-white">@lang('Cookie Policy')</h5>
            
        </div>
        <p class="mb-4">
            {{setting('frontend_cookie_content')}}
        </p>
        
        <button type="button" class="btn btn-primary btn-sm" id="btn-accept-cookie"> @lang('Accept')</button>
        <a class="btn btn-info btn-sm" href="{{ route('pages.show', setting('frontend_page_policy', 'contact')) }}" target="_blank">@lang('Learn More')</a>
    </div>
</div>
@endif