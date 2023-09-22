<!-- Start Alert Notifications Section -->
@if(isset ($errors) && count($errors) > 0)
    <div class="alert-notification">
        {{ $errors->first() }}
    </div>
@endif

@if(Session::get('success', false))
    <?php $data = Session::get('success'); ?>
    <div class="alert-notification">
        {{ $data }}
    </div>
@endif
<!-- End Alert Notifications Section -->