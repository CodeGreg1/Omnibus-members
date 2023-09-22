<!-- Start App Scripts Section -->
<script>
	"use strict";

	var app = {};

	app.translations = {!! Translations::all() !!};

	@section('policies')
		
	@show
</script>
<!-- App Script -->
<script src="{{ url(mix('js/app.min.js')) }}"></script>

<!-- Custom Scripts -->
@section('scripts')

@show
<!-- End App Scripts Section -->