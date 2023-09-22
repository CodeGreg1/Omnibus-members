<div class="card" id="devices">
    <div class="card-header">
        <h4 class="width-100per">
            <i class="fas fa-desktop"></i> @lang('Devices')

            <a href="javascript:void(0)" class="float-right text-right" id="logout-all-devices">@lang('Log out all devices')</a>
        </h4>

    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-12">
                <p>@lang("You're currently logged in to :app_name on these devices. If you don't recognize a device, log out to keep your account secure.", ['app_name' => "<b>$appName</b>"])</p>
            </div>

            
            <div class="col-12 col-md-12">
                <div class="list-group list-group-sessions">
                    @foreach($sessions->reverse() as $session)
                        <div class="list-group-item list-group-item-action flex-column align-items-start" data-id="{{ $session->id }}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1"><i class="fas fa-{{ $session->device }}"></i> {{ $session->browser }} on {{ $session->os }} 
                                    @if( $session->id == session()->getId() )
                                    <span class="badge badge-primary">This device</span>
                                    @endif
                                </h6>
                            </div>
                            <p class="mb-1">{{ Carbon\Carbon::parse(date('Y-m-d H:i:s', $session->last_activity))->diffForHumans() }}</p>
                            @if( !is_null($session->location) )
                            <small>
                                {{ $session->location->city . ' City' }} 
                                {{ '(Region of ' . $session->location->region . ')' }},
                                {{ $session->location->country->name }}
                            </small>
                            @endif

                            <a href="javascript:void(0)" class="device-logout float-right">@lang('Log out')</a>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>                 
</div>