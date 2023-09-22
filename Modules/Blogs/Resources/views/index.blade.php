@extends('blogs::layouts.site')

@section('page-class', 'unsticky-navbar')

@section('policies')
    app.policies = {!! json_encode($policies) !!};
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb link-accent">
        <li><a href="{{ route('site.website.index') }}">@lang('Home')</a></li>
        <li>Blogs</li>
    </ol>
    <h1 class="page-title">@lang('Blogs')</h1>
@endsection

@section('content')
    <!-- ========================= content start ========================= -->
    <div class="container blog-container">
        <div class="section">
            <div class="row">
                <!-- Start of blog content -->
                <div class="col-12 col-lg-8">

                    <section class="blog-section pt-0 pb-0">

                        <div class="row">
                            @forelse ($blogs as $blog)
                                @include('blogs::partials.blog-item')
                            @empty
                                <div class="py-5 text-center text-muted">
                                    @lang('No blogs were found!')
                                </div>
                            @endforelse
                        </div>

                        @if($blogs->hasPages())
                        <div class="post-pagination mb-25">
                            {{ $blogs->links() }}
                        </div>
                        @endif
                    </section>
                </div>
                <!-- End of blog content -->

                <!-- Start of blog sidebar -->
                <div class="col-12 col-lg-4">
                    @if ($popular->count())
                        @include('blogs::partials.popular-posts')
                    @endif

                    <div class="card page-sidebar">
                        <div class="card-header">
                            <h4>@lang('Categories')</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list list-fadeIn">
                            @foreach ($categories as $category)
                                <li>
                                    <a href="{{ route('blogs.categories.index', \Illuminate\Support\Str::slug($category->name)) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                            </ul>
                        </div>
                    </div>

                    @if ($latest->count())
                        @include('blogs::partials.latest-posts')
                    @endif
                </div>
                <!-- End of blog sidebar -->
            </div>
        </div>
    </div>
    <!-- ========================= content end ========================= -->
@endsection
