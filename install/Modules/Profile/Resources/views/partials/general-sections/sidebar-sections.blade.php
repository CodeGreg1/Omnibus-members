<div class="sidebar-item">
    <div class="make-me-sticky">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-columns"></i> @lang('Sections')</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills flex-column general-profile-sections">
                    <li class="nav-item"><a href="#details" class="nav-link active"><i class="fas fa-flag"></i> @lang('Details')</a></li>
                    @if(setting('services_login_with_facebook') || setting('services_login_with_google'))
                    <li class="nav-item"><a href="#login" class="nav-link"><i class="fas fa-share"></i> @lang('Login service')</a></li>
                    @endif
                    <li class="nav-item"><a href="#address" class="nav-link"><i class="fa fa-address-card"></i> @lang('Address')</a></li>
                    <li class="nav-item"><a href="#company" class="nav-link"><i class="fa fa-briefcase"></i> @lang('Company')</a></li>
                    <li class="nav-item"><a href="#timezone" class="nav-link"><i class="fa fa-clock"></i> @lang('Timezone and date & time formats')</a></li>
                    <li class="nav-item"><a href="#language" class="nav-link"><i class="fa fa-language"></i> @lang('Preferred language')</a></li>
                    <li class="nav-item"><a href="#currency" class="nav-link"><i class="fa fa-dollar-sign"></i> @lang('Currency')</a></li>                              
                </ul>
            </div>                      
        </div>
        
    </div>
</div>