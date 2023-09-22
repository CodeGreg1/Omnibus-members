<!-- Start Site Scripts Section -->
<script>
	"use strict";

	var app = {};

	app.translations = {!! Translations::all() !!};

	@section('policies')
		
	@show
</script>

<script src="{{ url('js/site/base.min.js') }}"></script>

{!! theme_script('js/theme.min.js') !!}

<!-- Module Scripts -->
@section('scripts')

@show

@if(setting('frontend_custom_js'))
<script>
    {!! setting('frontend_custom_js') !!}
</script>
@endif
<!-- End Site Scripts Section -->