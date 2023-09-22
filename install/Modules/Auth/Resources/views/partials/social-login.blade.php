<div class="text-center mt-4 mb-3">
    <div class="text-job text-muted">@lang('Login With Social')</div>
</div>
<div class="row sm-gutters">
  @if(setting('services_login_with_facebook'))
  <div class="col-{{ setting('services_login_with_facebook') == 1 && setting('services_login_with_google') == 1 ? '6' : '12' }}">
    <a href="{{ route('auth.login.social', 'facebook') }}" class="btn btn-block btn-social btn-facebook">
        <span class="fab fa-facebook"></span> @lang('Facebook')
    </a>
  </div>
  @endif

  @if(setting('services_login_with_google'))
  <div class="col-{{ setting('services_login_with_facebook') == 1 && setting('services_login_with_google') == 1 ? '6' : '12' }}">
    <a href="{{ route('auth.login.social', 'google') }}" class="btn btn-block btn-social btn-google">
        <span class="fab fa-google"></span> @lang('Google')
    </a>
  </div>
  @endif
</div>
