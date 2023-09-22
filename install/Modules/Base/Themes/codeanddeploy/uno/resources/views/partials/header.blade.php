<!-- Start Header Section -->
<header class="header navbar-area @yield('header-class')">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="{{ route('site.website.index') }}">
                        @if(navbar_color_scheme(isset($page) ? $page : null) === 'dark-scheme')
                            <img src="{{ setting('frontend_white_logo') }}" alt="Header logo">
                        @else
                            <img src="{{ setting('frontend_dark_logo') }}" alt="Header logo">
                        @endif
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="toggler-icon"></span>
                        <span class="toggler-icon"></span>
                        <span class="toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                        @php
                            $menus = collect([]);
                            if (setting('frontend_primary_menu')) {
                                $activeMenu = \Modules\Menus\Models\Menu::find(setting('frontend_primary_menu'));

                                if ($activeMenu) {
                                    $menus = $activeMenu->frontend_links
                                        ->where('parent_id', null)
                                        ->map(function($link) use ($activeMenu) {
                                            $link->frontend_links = $activeMenu->frontend_links->where('parent_id', $link->id);
                                            return $link;
                                        });
                                }
                            }

                        @endphp
                        @if ($menus->count())
                            <ul id="nav" class="navbar-nav">
                                @foreach ($menus as $link)
                                    @if ($link->frontend_links->count())
                                    @php
                                        $routeLinks = $link->frontend_links->map(function($item) {
                                            return $item->getRoute();
                                        })->toArray();
                                    @endphp
                                        <li class="nav-item {{ (Str::contains(url()->current(), $routeLinks)) ? 'active' : '' }}">
                                            <a class="page-scroll dd-menu collapsed {{ (Str::contains(url()->current(), $routeLinks)) ? 'active' : '' }} {{ $link->class }}" href="javascript:void(0)" data-bs-toggle="collapse"
                                            data-bs-target="#submenu-{{$link->id}}" aria-controls="navbarSupportedContent"
                                            aria-expanded="false" aria-label="Toggle navigation">{{ $link->label }}</a>

                                            <ul class="sub-menu collapse" id="submenu-{{$link->id}}">
                                                @foreach ($link->frontend_links as $relatedLink)
                                                    <li class="nav-item">
                                                        <a href="{{ $relatedLink->getRoute() }}"  class="{{ (url()->current() == $relatedLink->getRoute()) ? 'active' : '' }} {{ $link->class }}" target="{{ $relatedLink->target === '_blank' ? '_blank' : '_self' }}">{{ $relatedLink->label }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        <li class="nav-item {{ (url()->current() === $link->getRoute()) ? 'active' : '' }}">
                                            <a class="page-scroll collapsed {{ (url()->current() === $link->getRoute()) ? 'active' : '' }} {{ $link->class }}" href="{{ $link->getRoute() }}" target="{{ $link->target === '_blank' ? '_blank' : '_self' }}">{{ $link->label }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                        <ul id="nav-right" class="navbar-nav ms-auto navbar-cta">
                            @guest
                            <li class="nav-item d-grid gap-2">
                                <a href="{{ route('auth.login.show') }}" class="btn btn-outline-secondary btn-outline-2px btn-lg btn-login">Login</a>
                            </li>
                            @if(setting('allow_registration'))
                            <li class="nav-item d-grid gap-2">
                                <a href="{{ route('auth.register.show') }}" class="btn btn-primary btn-lg btn-register">Register</a>
                            </li>
                            @endif
                            @endguest

                            @auth
                            <li class="nav-item d-grid gap-2">
                                <a href="{{ route('dashboard.index') }}" class="btn btn-primary btn-outline-2px btn-lg">Go To Dashboard <i class="lni lni-arrow-right"></i></a>
                            </li>
                            @endauth
                        </ul>
                    </div> <!-- navbar collapse -->
                </nav> <!-- navbar -->
            </div>
        </div> <!-- row -->
    </div> <!-- container -->

</header>
<!-- End Header Section -->