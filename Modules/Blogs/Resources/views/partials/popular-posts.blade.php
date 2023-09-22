<div class="card page-sidebar">
    <div class="card-header">
        <h4>@lang('Popular posts')</h4>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-custom">
            @foreach ($popular as $item)
                @include('blogs::partials.post-item')
            @endforeach
        </div>
    </div>
</div>
