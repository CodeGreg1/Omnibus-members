@extends('dashboard::layouts.master')

@section('custom-section-class', 'custom-section')

@section('module-scripts')
    <script src="{{ url('plugins/chart.js/js/chart.umd.js') }}"></script>
@endsection

@section('content')
    <div class="row">

        @if(auth()->user()->isAdmin())
            @foreach($widgets as $widget)

                @if($widget['type'] == 'counter')
                    <div class="{{$widget['column_class']}} col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon {{$widget['background_class']}}">
                                <i class="{{$widget['icon']}}"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ trans($widget['title']) }}</h4>
                                </div>
                                <div class="card-body">
                                    {{$widget['result']}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            @endforeach


            @foreach($widgets as $widget)
                @if($widget['type'] == 'latest_records')
                    @if(isset($widget['fields']) && $widget['fields'] > 0)
                    <div class="{{$widget['column_class']}} col-md-6 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ trans($widget['title']) }}</h4>
                                <div class="card-header-action">
                                    @if(isset($widget['view_more']))
                                        <a href="{{ route($widget['view_more']) }}" class="btn btn-danger">@lang('View More') <i class="fas fa-chevron-right"></i></a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-0">
                              <div class="table-responsive table-invoice">
                                <table class="table table-striped">
                                    <tr>
                                        @foreach($widget['fields'] as $field => $fieldSettings)
                                            @if(isset($fieldSettings['column_name']))
                                                <th width="{{ (isset($fieldSettings['width']) && $fieldSettings['width'] != '' ? $fieldSettings['width'] . '%' : '') }}">{{ $fieldSettings['column_name'] }}</th>
                                            @else
                                                <th>{{ ucwords(trans(str_replace('_', ' ', $field))) }}</th>
                                            @endif
                                        @endforeach
                                    </tr>

                                    @foreach($widget['result'] as $result)
                                        <tr>
                                            @foreach($widget['fields'] as $field => $fieldSettings)
                                                <td>{!! clean($result->{$field.'_custom'}) !!}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach

                                </table>
                              </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endif

                @if(in_array($widget['type'], \Modules\Base\Support\Widgets\ChartType::lists()))
                    <div class="{{$widget['column_class']}}  col-md-6 col-sm-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $widget['title'] }}</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="{{ $widget['selector'] }}" class="chart" data-attributes='{{ json_encode($widget) }}'></canvas>
                            </div>
                        </div>
                    </div>
                @endif

            @endforeach


            @if (Module::has('Wallet') && setting('allow_wallet') === 'enable' && isset($admin_wallet_overview))
                @foreach ($admin_wallet_overview['deposit'] as $currency => $overview)
                    @foreach ($overview as $item)
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1 card-statistic-colored-bg bg-{{$item['color']}}">
                                <div class="card-icon bg-{{$item['color']}} text-white">
                                    <i class="{{$item['icon']}}"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4 class="text-xs">{{ trans($item['label']) }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{$item['value']}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach

                @if (setting('allow_withdrawal') === 'enable')
                    @foreach ($admin_wallet_overview['withdrawal'] as $currency => $overview)
                        @foreach ($overview as $item)
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-{{$item['color']}} text-white">
                                        <i class="{{$item['icon']}}"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4 class="text-xs">{{ trans($item['label']) }}</h4>
                                        </div>
                                        <div class="card-body">
                                            {{$item['value']}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @endif

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>@lang('Latest Transactions')</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.transactions.reports.index') }}" class="btn btn-primary">@lang('View More') <i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th>@lang('Date')</th>
                                            <th>@lang('User')</th>
                                            <th>@lang('Description')</th>
                                            <th>@lang('Initial Balance')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Charge')</th>
                                            <th>@lang('Total')</th>
                                        </tr>
                                        @forelse ($admin_wallet_overview['transactions'] as $item)
                                            <tr>
                                                <td>{{ $item->date }}</td>
                                                <td>{{ $item->user }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->initial_balance }}</td>
                                                <td>{{ $item->amount }}</td>
                                                <td>{{ $item->charge }}</td>
                                                <td>{{ $item->total }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <span>@lang('No transactions yet.')</span>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            @if (Module::has('Subscriptions'))
                @include('subscriptions::admin.widgets.statistics')
                @include('subscriptions::admin.widgets.sales-overview')
            @endif
        @else
            @if (setting('allow_wallet') === 'enable' && isset($user_wallet_overview))
                @foreach ($user_wallet_overview['deposit'] as $currency => $overview)
                    @foreach ($overview as $item)
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1 card-statistic-colored-bg bg-{{$item['color']}}">
                                <div class="card-icon bg-{{$item['color']}} text-white">
                                    <i class="{{$item['icon']}}"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4 class="text-xs">{{ trans($item['label']) }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{$item['value']}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach

                @if (setting('allow_withdrawal') === 'enable')
                    @foreach ($user_wallet_overview['withdrawal'] as $currency => $overview)
                        @foreach ($overview as $item)
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-{{$item['color']}} text-white">
                                        <i class="{{$item['icon']}}"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4 class="text-xs">{{ trans($item['label']) }}</h4>
                                        </div>
                                        <div class="card-body">
                                            {{$item['value']}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @endif

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>@lang('Latest Transactions')</h4>
                            <div class="card-header-action">
                                <a href="{{ route('user.transactions.index') }}" class="btn btn-primary">View More <i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th>@lang('Date')</th>
                                            <th>@lang('Description')</th>
                                            <th>@lang('Initial Balance')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Charge')</th>
                                            <th>@lang('Total')</th>
                                        </tr>
                                        @forelse ($user_wallet_overview['transactions'] as $item)
                                            <tr>
                                                <td>{{ $item->date }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->initial_balance }}</td>
                                                <td>{{ $item->amount }}</td>
                                                <td>{{ $item->charge }}</td>
                                                <td>{{ $item->total }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <span>@lang('No transactions yet.')</span>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection

@section('modals')
    @if(auth()->user()->isAdmin() && (setting('last_cron_checked')===null || (new \Carbon\Carbon(setting('last_cron_checked')))->diffInMinutes(now()) > 60))
        @include('base::partials.modals.cron-not-running-modal')
    @endif
@endsection
