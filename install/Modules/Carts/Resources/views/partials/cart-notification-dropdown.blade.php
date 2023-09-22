<li class="dropdown dropdown-list-toggle keepopen" id="cart-notification-dopdown">
    <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
        <i class="fas fa-shopping-cart"></i>
    </a>
    <div class="dropdown-menu dropdown-list dropdown-menu-right">
        <div class="dropdown-header">
            @lang('My cart') (<span class="cart-items-count">0</span>)
            <div class="float-right">
                <a href="/user/carts" class="cart-view-all d-none">@lang('View all')</a>
            </div>
        </div>
        <div class="dropdown-list-content dropdown-list-icons">
            <div class="dropdown-item d-block">
                <div class="text-center pt-4 text-muted">
                    @lang('Your cart is empty.')
                </div>
            </div>
            <div class="dropdown-item">
                <div class="d-flex align-items-center justify-content-center mb-2 w-100">
                    <a href="#" class="btn btn-primary">@lang('Shop now')</a>
                </div>
            </div>
        </div>
        <div class="dropdown-footer text-center d-none">
            <a href="/user/carts/checkout">@lang('Proceed to checkout')<i class="fas fa-chevron-right"></i></a>
        </div>
    </div>
</li>
