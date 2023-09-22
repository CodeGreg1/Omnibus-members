@if(setting('services_login_with_facebook') || setting('services_login_with_google'))
<div class="card" id="login">
    <div class="card-header">
        <h4><i class="fas fa-share"></i> @lang('Login service')</h4>
    </div>
    <div class="card-body">

        @include('partials.messages')

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="row sm-gutters">
                    @if(setting('services_login_with_facebook'))
                    <div class="col-6">
                        <button data-route="{{ auth()->user()->present()->facebookLoginRoute }}" 
                            class="btn btn-block btn-custom-facebook btn-primary btn-disconnect-social" 
                            data-provider="facebook"><span class="caption">{{ auth()->user()->present()->facebookLoginCaption }}</span>
                        </button>
                    </div>
                    @endif

                    @if(setting('services_login_with_google'))
                    <div class="col-6">
                        <button data-route="{{ auth()->user()->present()->googleLoginRoute }}" 
                            class="btn btn-block btn-custom-google btn-disconnect-social"
                            data-provider="google"><span class="caption">{{ auth()->user()->present()->googleLoginCaption }}</span>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 col-md-12">
                <div class="alert alert-warning">
                    @lang('Warning: Performing this action will logout the system. Once connected it will login automatically.')
                </div>
            </div>
        </div>  
    </div>                  
</div>
@endif