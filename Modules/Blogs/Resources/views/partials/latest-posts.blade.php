<div class="card page-sidebar">
    <div class="card-header">
        <h4>@lang('Latest posts')</h4>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-custom">
            @foreach ($latest as $item)
                @include('blogs::partials.post-item')
            @endforeach
        </div>
    </div>
</div>
