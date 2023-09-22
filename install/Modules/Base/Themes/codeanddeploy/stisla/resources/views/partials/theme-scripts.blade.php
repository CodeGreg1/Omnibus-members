<!-- Start Theme Scripts Section -->
<script src="{{ url('plugins/jquery/js/jquery-3.6.4.min.js') }}"></script>
<script src="{{ url('plugins/popper/js/popper.min.js') }}"></script>
<script src="{{ url('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ url('plugins/jquery-nicescroll/js/jquery.nicescroll.min.js') }}"></script>
<script src="{{ url('plugins/moment/js/moment.min.js') }}"></script>
<script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>

{!! theme_script('assets/js/stisla.js') !!}

@yield('module-scripts')

<!-- Template JS File -->
{!! theme_script('assets/js/scripts.js') !!}
<!-- End Theme Scripts Section -->