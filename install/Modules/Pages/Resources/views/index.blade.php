@extends('pages::layouts.site')

@section('page-theme-scheme')

@section('page-class', 'unsticky-navbar')

@section('breadcrumbs-position', 'centered')

@section('meta')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <link rel="canonical" href="{{ route('pages.show', $page->slug) }}">
    <meta name="author" content="{{ setting('app_name') }}">
    <meta name="title" content="{{ $page->page_title }}">
    <meta name="description" content="{{ $page->page_description }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('pages.show', $page->slug) }}">
    <meta property="og:title" content="{{ $page->page_title }}">
    <meta property="og:description" content="{{ $page->page_description }}">
    <meta property="og:image" content="{{ setting('frontend_social_sharing_image') }}">

    <meta property="twitter:url" content="{{ route('pages.show', $page->slug) }}">
    <meta property="twitter:title" content="{{ $page->page_title }}">
    <meta property="twitter:description" content="{{ $page->page_description }}">
    <meta property="twitter:image" content="{{ setting('frontend_social_sharing_image') }}">

    <meta property="article:handle" content="{{ $page->slug }}">
    <meta property="article:section" content="Page">
    <meta property="article:published_time" content="{{ $page->created_at }}">
    <meta property="article:modified_time" content="{{ $page->updated_at }}">

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
                "@id":"{{ route('pages.show', $page->slug) }}"
            },
            "url":"{{ route('pages.show', $page->slug) }}",
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
            "headline":"{{ $page->title }}",
            "datePublished":"{{ $page->created_at }}",
            "dateModified":"{{ $page->updated_at }}"
        }
    </script>
@endsection

@section('policies')
    app.policies = {!! json_encode($policies) !!};
@endsection

@if ($page->has_breadcrumb)
    @section('breadcrumbs')
        <ol class="breadcrumb link-accent">
            <li><a href="{{ route('site.website.index') }}">@lang('Home')</a></li>
            <li>@lang('Page')</li>
        </ol>
        <h1 class="page-title">{{ $page->name }}</h1>
        @if(setting('frontend_page_policy') == $page->slug || setting('frontend_page_terms') == $page->slug)
            <p class="font-weight-normal mb-0 text-center page-revision">Last revised on {{ $page->updated_at->format('F j, Y') }}</p>
        @endif
    @endsection
@endif

@section('content')
    @if($page->type == 'wysiwyg')
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <section class="page-article">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="page-article-inner">
                                    <article class="article article-style-d article-detail">
                                        <div class="article-description">
                                            {!! PageBuilder::build($page) !!}

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
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @else
        {!! PageBuilder::build($page) !!}
    @endif
@endsection
