@extends('frontend::layouts.master')

@section('module-styles')

@endsection

@section('module-scripts')
    <script src="{{ url('plugins/bootbox/js/bootbox.all.min.js') }}"></script>
    <script src="{{ url('plugins/dropzone/js/dropzone.min.js') }}"></script>
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}"> @lang('Dashboard')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.frontends.settings.index') }}"> @lang('Frontend')</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="javascript:void(0)"> {{ $pageTitle }}</a>
        </li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-3 col-xm-12">
            @include('frontend::admin.partials.menus')
        </div>
        <div class="col-lg-9 col-md-9 col-xm-12">
            <div class="card card-form">
                <div class="card-header">
                    <h4>{{ $pageTitle }}</h4>
                </div>

                <form id="admin-frontend-settings-form" method="post" data-route="{{ route('admin.frontends.settings.social-links.update') }}" data-type="social_link">
                    <input type="hidden" name="redirect" value="{{ route('admin.frontends.settings.social-links.index') }}">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="frontend_facebook_url">@lang('Facebook') <span class="text-muted">(@lang('Optional'))</span></label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fab fa-facebook" style="font-size: 18px;"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="frontend_facebook_url" name="frontend_facebook_url" placeholder="https://facebook.com/" value="{{ setting('frontend_facebook_url') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="frontend_twitter_url">@lang('Twitter') <span class="text-muted">(@lang('Optional'))</span></label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fab fa-twitter" style="font-size: 18px;"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="frontend_twitter_url" name="frontend_twitter_url" placeholder="https://twitter.com/" value="{{ setting('frontend_twitter_url') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="frontend_linkedin_url">@lang('LinkedIn') <span class="text-muted">(@lang('Optional'))</span></label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fab fa-linkedin" style="font-size: 18px;"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="frontend_linkedin_url" name="frontend_linkedin_url" placeholder="https://linkedin.com/" value="{{ setting('frontend_linkedin_url') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="frontend_instagram_url">@lang('Instagram') <span class="text-muted">(@lang('Optional'))</span></label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fab fa-instagram" style="font-size: 18px;"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="frontend_instagram_url" name="frontend_instagram_url" placeholder="https://instagram.com/" value="{{ setting('frontend_instagram_url') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="frontend_pinterest_url">@lang('Pinterest') <span class="text-muted">(@lang('Optional'))</span></label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fab fa-pinterest" style="font-size: 18px;"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="frontend_pinterest_url" name="frontend_pinterest_url" placeholder="https://pinterest.com/" value="{{ setting('frontend_pinterest_url') }}">
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label for="frontend_youtube_url">@lang('Youtube') <span class="text-muted">(@lang('Optional'))</span></label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fab fa-youtube" style="font-size: 18px;"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="frontend_youtube_url" name="frontend_youtube_url" placeholder="https://youtube.com/" value="{{ setting('frontend_youtube_url') }}">
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-whitesmoke">
                        <button class="btn btn-primary btn-lg float-right" type="submit">@lang('Save')</button>
                        <a href="{{ route('admin.frontends.settings.social-links.index') }}" class="btn btn-default btn-lg float-right mr-3">@lang('Cancel')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
