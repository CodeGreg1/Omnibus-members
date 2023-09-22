<div class="sidebar-item">
    <div class="make-me-sticky">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-columns"></i> @lang('Sections')</h4>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills flex-column security-profile-sections">
                    <li class="nav-item"><a href="#password" class="nav-link active"><i class="fas fa-key"></i> @lang('Password')</a></li>
                    @if(setting('two_factor'))
                    <li class="nav-item"><a href="#2fa" class="nav-link"><i class="fas fa-shield-alt"></i> @lang('Two-step authentication')</a></li>
                    @endif
                    <li class="nav-item"><a href="#devices" class="nav-link"><i class="fas fa-desktop"></i> @lang('Devices')</a></li>                             
                </ul>
            </div>                      
        </div>
        
    </div>
</div>