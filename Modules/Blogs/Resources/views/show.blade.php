@extends('blogs::layouts.site')

@section('page-class', 'unsticky-navbar')

@section('meta')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="{{ route('blogs.show', $blog->slug) }}">
    <meta name="author" content="{{ setting('app_name') }}">
    <meta name="title" content="{{ $blog->page_title }}">
    <meta name="description" content="{{ $blog->page_description }}">

    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('blogs.show', $blog->slug) }}">
    <meta property="og:title" content="{{ $blog->page_title }}">
    <meta property="og:description" content="{{ $blog->page_description }}">
    <meta property="og:image" content="{{ $blog->thumbnail->original_url }}">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ route('blogs.show', $blog->slug) }}">
    <meta property="twitter:title" content="{{ $blog->page_title }}">
    <meta property="twitter:description" content="{{ $blog->page_description }}">
    <meta property="twitter:image" content="{{ $blog->thumbnail->original_url }}">

    <meta property="article:handle" content="{{ $blog->slug }}">
    <meta property="article:section" content="Blog">
    <meta property="article:published_time" content="{{ $blog->created_at }}">
    <meta property="article:modified_time" content="{{ $blog->modified_at }}">
    @foreach ($blog->tags as $tag)
        <meta property="article:tag" content="{{ $tag->name }}">
    @endforeach

    <script type="application/ld+json">
        @php
            $logoPath = '';
            $logoUrl = parse_url(setting('colored_logo'));
            if ($logoUrl['path'] && file_exists(public_path($logoUrl['path']))) {
                $logoPath = url($logoUrl['path']);
            }
        @endphp
        {
            "@content":"https://schema.org",
            "@type":"Article",
            "mainEntityOfPage":{
                "@type":"WebPage",
                "@id":"{{ route('blogs.show', $blog->slug) }}"
            },
            "url":"{{ route('blogs.show', $blog->slug) }}",
            "image":[
                "{{ $blog->thumbnail->original_url }}"
            ],
            "publisher":{
                "@content":"https://schema.org",
                "@type":"Organization",
                "name":"{{ setting('app_name') }}",
                "logo":{
                    "@content":"https://schema.org",
                    "@type":"ImageObject",
                    "url":"{{ $logoPath }}"
                }
            },
            "headline":"{{ $blog->title }}",
            "author":{
                "@content":"https://schema.org",
                "@type":"Person",
                "url":"{{ route('blogs.index') }}",
                "name":"{{ $blog->author->full_name }}"
            },
            "datePublished":"{{ $blog->created_at }}",
            "dateModified":"{{ $blog->modified_at }}"
        }
    </script>
@endsection

@section('policies')
    app.policies = {!! json_encode($policies) !!};
@endsection

@section('breadcrumbs')
    <ol class="breadcrumb link-accent">
        <li><a href="{{ route('site.website.index') }}">@lang('Home')</a></li>
        <li>@lang('Blog')</li>
    </ol>
    <h1 class="page-title">{{ $blog->title }}</h1>
@endsection

@section('content')
    <!-- ========================= content start ========================= -->
    <div class="container blog-container">
        <div class="row">
            <!-- Start of blog content -->
            <div class="col-12 col-lg-8">
                <article class="article article-style-d article-detail">
                    @if ($blog->thumbnail)
                        <figure class="article-image">
                            <img src="{{ $blog->thumbnail->original_url }}" alt="image" class="img-fluid">
                        </figure>
                    @endif
                    <div class="article-content">
                        <div class="article-metas">
                            <div class="article-meta">
                                <i class="fas fa-user"></i>
                                <span>{{ $blog->author->full_name }}</span>
                            </div>
                            <div class="article-meta">
                                <i class="fas fa-clock"></i>
                                <span>{{ $blog->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="article-meta">
                                <i class="fas fa-sitemap"></i>
                                <a href="{{ route('blogs.categories.index', \Illuminate\Support\Str::slug($blog->category->name)) }}">
                                    {{ $blog->category->name }}
                                </a>
                            </div>
                            @if ($blog->tags->count())
                                <div class="article-meta article-meta-custom">
                                    <i class="fas fa-tag"></i>
                                    @foreach ($blog->tags as $key => $tag)
                                        <a href="{{ route('blogs.tags.index', \Illuminate\Support\Str::slug($tag->name)) }}">
                                            {{ $tag->name }}
                                        </a>
                                        @if (($blog->tags->count() - 1) !== $key)
                                            <span>,</span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="article-description">{!! $blog->content !!}</div>

                        <div class="footer-social-links colored mt-25">
                            <span class="article-share-blog">@lang('Share On')</span>
                            <ul class="d-flex">
                                @foreach($socialShares as $socialShareKey => $socialShareValue)
                                    <li><a href="{{ $socialShareValue }}" class="social-button"><i class="lni lni-{{$socialShareKey}}"></i></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </article>
                <div class="row article-other-articles">
                    <div class="col-12 col-md-6">
                        @if ($previous)
                            <a href="{{ route('blogs.show', $previous->slug) }}" class="previous-post">
                                <h6 class="post-page-name">@lang('Previous Post')</h6>
                                <div class="post-page-details">
                                    <img src="{{ $previous->thumbnail->preview_url }}" alt="{{ $previous->title }}">
                                    <div class="post-page-desc">
                                        <h5 class="post-page-title">{{ $previous->title }}</h5>
                                        <span class="post-page-date">{{ $previous->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>

                    <div class="col-12 col-md-6">
                        @if ($next)
                            <a href="{{ route('blogs.show', $next->slug) }}" class="next-post">
                                <h6 class="post-page-name">@lang('Next Post')</h6>
                                <div class="post-page-details">
                                    <div class="post-page-desc">
                                        <h5 class="post-page-title">{{ $next->title }}</h5>
                                        <span class="post-page-date">{{ $next->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <img src="{{ $next->thumbnail->preview_url }}" alt="{{ $next->title }}">
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
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
                                    <span>{{ $category->name }}</span>
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
    <!-- ========================= content end ========================= -->
@endsection
