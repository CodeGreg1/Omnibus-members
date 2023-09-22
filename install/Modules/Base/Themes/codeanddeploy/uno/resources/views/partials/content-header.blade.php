<!-- Start Breadcrumbs Section -->
@section('content-header')
	@hasSection('breadcrumbs')
		@if (setting('frontend_breadcrumb_background_style') === 'image')
			<div class="page-header one-column has-background-image @yield('breadcrumbs-position') bg-image bg-fixed bg-overlay default-overlay" style="background-image: url({{ setting('frontend_breadcrumb_bg_image') }});">
				<div class="container">
					@yield('breadcrumbs')
				</div>
			</div>
		@else
			<div class="page-header one-column @yield('breadcrumbs-position')">
				<div class="container">
					@yield('breadcrumbs')
				</div>
			</div>
		@endif
    @endif
@show
<!-- End Breadcrumbs Section -->