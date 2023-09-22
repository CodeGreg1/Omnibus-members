<div class="col-12 col-md-6">
    <div class="single-blog">
        <div class="blog-img">
            <a href="{{ route('blogs.show', $blog->slug) }}">
                @if ($blog->thumbnail)
                    <img src="{{ $blog->thumbnail->original_url }}" alt="{{$blog->title}}">
                @else
                    <img src="/upload/media/default/preview.jpg" alt="{{$blog->title}}">
                @endif
            </a>
            <span class="date-meta">{{ $blog->created_at->format('M d, Y') }}</span>
        </div>
        <div class="blog-content">
            <h4><a href="{{ route('blogs.show', $blog->slug) }}" title="{{$blog->title}}">{{ Str::words($blog->title, 8, '...') }}</a></h4>
            <p>{{ Str::words($blog->description, 30, '...') }}</p>
        </div>
    </div>
</div>
