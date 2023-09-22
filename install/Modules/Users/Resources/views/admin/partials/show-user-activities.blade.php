<div class="card">
    <div class="card-header">
        <h4>@lang('Latest Activity')</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive mb-0">
            <table class="table table-striped table-md mb-0">
                @if(count($activities))
                    <tr>
                        <th width="1%">#</th>
                        <th width="50%">@lang('Activity')</th>
                    </tr>

                    @php
                        $i = 1;
                    @endphp
                    @foreach($activities as $activity)
                    <tr>
                        <td>{{$i}}</td>
                        <td>
                            @if($activity->causer->full_name != 'N/A')
                                <b>{{$activity->causer->full_name}} </b>
                            @else
                                <b>{{$activity->causer->username}} </b>
                            @endif

                            {{$activity->description}} &#9679; <i class="small">{{ $activity->created_at_for_humans }}</i>
                            @php
                                $properties = json_decode($activity->properties);
                            @endphp
                            <br/>
                            (<a href="https://whatismyipaddress.com/ip/{{ $properties->user_ip }}" target="_blank" rel="nofollow">{{ $properties->user_ip }}</a>)
                            <br/><br/>
                            

                            {{ $properties->user_agent }}
                        </td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">@lang('No records found!')</td>
                    </tr>
                @endif
                
            </table>
        </div>
    </div>
</div>