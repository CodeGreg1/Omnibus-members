<a href="{{ route('blogs.show', $item->slug) }}" class="list-group-item list-group-item-action flex-column align-items-start py-3">
    <div class="d-flex w-100 justify-content-between">
        <strong class="mb-1">{{ $item->title }}</strong>
    </div>
    <p class="mb-1 line-clamp-2">{{ $item->description }}</p>
    <small class="text-muted"><em>{{ $item->created_at->diffForHumans() }}</em></small>
</a>
