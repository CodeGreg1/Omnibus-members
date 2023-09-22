@if (setting('cashier_mode', 'sandbox') === 'sandbox')
    <div class="d-flex align-items-center {{ $class ?? '' }}">
        <i class="fas fa-exclamation-circle text-warning mr-1"></i>
        <span class="text-dark text-sm fw-bold">@lang('Test data')</span>
    </div>
@endif
