<!-- Start Footer Section -->
<footer class="footer pt-70">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="footer-widget mb-60">
                    <a href="{{ route('dashboard.index') }}" class="logo mb-30">
                        @if(footer_color_scheme(isset($page) ? $page : null) === 'dark-scheme')
                            <img src="{{ setting('frontend_white_logo') }}" alt="Footer logo">
                        @else
                            <img src="{{ setting('frontend_dark_logo') }}" alt="Footer logo">
                        @endif
                    </a>
                    <p class="mb-30 footer-desc">{{ setting('frontend_footer_about_us') }}</p>
                </div>
            </div>
            <div class="col-xl-2 offset-xl-1 col-lg-2 col-md-6">
                @php
                    $menu1 = null;
                    if (setting('frontend_footer_menu1')) {
                        $menu1 = \Modules\Menus\Models\Menu::find(setting('frontend_footer_menu1'));
                    }
                @endphp

                @if ($menu1)
                    <div class="footer-widget mb-60">
                        <h3>{{ setting('frontend_footer_menu1_title') }}</h3>
                        <ul class="footer-links">
                            @foreach ($menu1->frontend_links as $link)
                                <li>
                                    <a href="{{ $link->getRoute() }}" class="{{ $link->class }}" target="{{ $link->target === '_blank' ? '_blank' : '_self' }}">{{ $link->label }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6">
                @php
                    $menu2 = null;
                    if (setting('frontend_footer_menu1')) {
                        $menu2 = \Modules\Menus\Models\Menu::find(setting('frontend_footer_menu2'));
                    }
                @endphp

                @if ($menu2)
                    <div class="footer-widget mb-60">
                        <h3>{{ setting('frontend_footer_menu2_title') }}</h3>
                        <ul class="footer-links">
                            @foreach ($menu2->frontend_links as $link)
                                <li>
                                    <a href="{{ $link->getRoute() }}" class="{{ $link->class }}" target="{{ $link->target === '_blank' ? '_blank' : '_self' }}">{{ $link->label }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6">
                <div class="footer-widget mb-60">
                    <h3>Contact</h3>
                    <ul class="footer-contact">
                        @if (setting('app_address'))
                            <li>
                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                    {{ setting('app_address') }}
                                </span>
                            </li>
                        @endif
                        @if (setting('app_phone'))
                            <li>
                                <span>
                                    <i class="fa-solid fa-phone"></i>
                                    {{ setting('app_phone') }}
                                </span>
                            </li>
                        @endif
                        @if (setting('app_email'))
                            <li>
                                <span>
                                    <i class="fa-solid fa-envelope"></i>
                                    <a href="mailto:{{ setting('app_email') }}">{{ setting('app_email') }}</a>
                                </span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="copyright-area">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="footer-social-links">
                        <ul class="d-flex">
                            @if (setting('frontend_facebook_url'))
                                <li><a href="{{ setting('frontend_facebook_url') }}"><i class="lni lni-facebook-filled"></i></a></li>
                            @endif
                            @if (setting('frontend_twitter_url'))
                                <li><a href="{{ setting('frontend_twitter_url') }}"><i class="lni lni-twitter-filled"></i></a></li>
                            @endif
                            @if (setting('frontend_linkedin_url'))
                                <li><a href="{{ setting('frontend_linkedin_url') }}"><i class="lni lni-linkedin-original"></i></a></li>
                            @endif
                            @if (setting('frontend_instagram_url'))
                                <li><a href="{{ setting('frontend_instagram_url') }}"><i class="lni lni-instagram-filled"></i></a></li>
                            @endif
                            @if (setting('frontend_pinterest_url'))
                                <li><a href="{{ setting('frontend_pinterest_url') }}"><i class="lni lni-pinterest"></i></a></li>
                            @endif
                            @if (setting('frontend_youtube_url'))
                                <li><a href="{{ setting('frontend_youtube_url') }}"><i class="lni lni-youtube"></i></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <p>{!! setting('frontend_footer_copyright_text') !!}</p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer Section -->