@extends('website::layouts.site')

@section('page-class', 'home-1')

@section('header-class', 'with-background')

@section('policies')
    app.policies = {!! json_encode($policies) !!};
@endsection

@section('module-styles')
    
@endsection

@section('module-scripts')
    
@endsection

@section('module-actions')
    
@endsection

@section('content')
    
	<!-- ========================= hero-section start ========================= -->
    <section id="home" class="hero-section d-flex align-items-center min-vh-100 bg-overlay default-overlay cover-background" style="background-image: url(https://techno.websitelayout.net/img/banner/banner-04.jpg);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="hero-content-wrapper text-center">
                        <h2 class="mb-25 wow fadeInDown text-white text-capitalize" data-wow-delay=".2s">Grow With Us</h2>
                        <h1 class="mb-25 wow fadeInDown text-white text-uppercase" data-wow-delay=".2s">MAKING PROJECT FASTER</h1>
                        <p class="mb-35 wow fadeInLeft text-white text-uppercase" data-wow-delay=".4s">Making faster the Laravel Application development and Save 1000s of money developing it from scratch.</p>
                        <a href="javascript:void(0)" class="btn btn-hover-arrow flex-shrink-0 btn-warning py-lg-3 px-lg-5 btn-outline-3px">
                            <span>BUY NOW</span>
                        </a>
                        <a href="javascript:void(0)" class="btn btn-hover-arrow flex-shrink-0 btn-outline-white btn-outline-3px py-lg-3 px-lg-5">
                            <span>VIEW DEMO</span>
                        </a>
                    </div>
                </div>
                <!-- <div class="col-lg-6">
                    <div class="hero-img">
                        <img src="{{ Theme::url('site/hero.png') }}" alt="" class="image wow fadeInRight" data-wow-delay=".5s">
                    </div>
                </div> -->
            </div>
        </div>
    </section>
    <!-- ========================= hero-section end ========================= -->

    <section id="about">
        <div class="container heading-center">
            <p class="sub-heading">Know us better</p>
            <h2 class="section-heading">WHO WE ARE</h2>
        </div>
        <div class="container text-center">
            <p class="lead">Compellingly actualize excellent users and distinctive leadership skills. Interactively productivate cross functional methodologies with visionary e-business. Appropriately generate diverse "outside the box" thinking whereas cutting-edge deliverables. </p>
            <a href="#" class="btn btn-hover-arrow flex-shrink-0 btn-outline-primary btn-outline-3px py-lg-3 px-lg-5">
                <span>READ MORE</span>
            </a>
        </div>
    </section>




    <!-- NUMBER INFO SECTION -->
    <section class="number-info-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url({{ asset('upload/theme_test/section-test.jpg')  }});">
    <!-- <section class="number-info-section"> -->
        <div class="container heading-center">
            <p class="sub-heading">What our partners say about us</p>
            <h2 class="section-heading">OUR STATISTICS</h2>
            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum purus nec nulla auctor cursus sit amet a justo. Vivamus sed massa urna.</p>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-sm-6 text-center">
                    <div class="number-info vertical boxed">
                        <i class="fa fa-cubes"></i>
                        <p>153<span>PROJECTS</span></p>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 text-center">
                    <div class="number-info vertical boxed">
                        <i class="fa fa-cubes"></i>
                        <p>153<span>PROJECTS</span></p>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 text-center">
                    <div class="number-info vertical boxed">
                        <i class="fa fa-star"></i>
                        <p>#1<span>TOP PROVIDER</span></p>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 text-center">
                    <div class="number-info vertical boxed">
                        <i class="fa fa-users"></i>
                        <p>132<span>CLIENTS</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END TESTIMONIAL SECTION -->




    <!-- CAROUSEL -->
    <!-- <section class="recent-works-section slick-carousel bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(https://script.viserlab.com/matrixlab/assets/images/frontend/client/60ae212735ab01622024487.jpg);"> -->
    <section class="recent-works-section slick-carousel">
        <div class="container heading-center">
            <p class="sub-heading">What we have accomplished</p>
            <h2 class="section-heading">RECENT WORKS</h2>
            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum purus nec nulla auctor cursus sit amet a justo. Vivamus sed massa urna.</p>
        </div>
        <div class="container">
            <div class="portfolio-container">
                <div class="portfolio-item">
                    <div class="overlay"></div>
                    <div class="info">
                        <h4 class="title">Raining</h4>
                        <p class="brief-description">Photography</p>
                        <a href="#" class="btn btn-sm btn-hover-arrow flex-shrink-0 btn-outline-white btn-outline-2px"><span>read more</span></a>
                    </div>
                    <div class="media-wrapper">
                        <img src="{{ asset('upload/theme_test/work5.png')  }}" alt="Item Thumbnail" />
                    </div>
                </div>
                <div class="portfolio-item">
                    <div class="overlay"></div>
                    <div class="info">
                        <h4 class="title">Perfect Edge</h4>
                        <p class="brief-description">Product Design</p>
                        <a href="#" class="btn btn-sm btn-hover-arrow flex-shrink-0 btn-outline-white btn-outline-2px"><span>read more</span></a>
                    </div>
                    <div class="media-wrapper">
                        <img src="{{ asset('upload/theme_test/work6.png')  }}" alt="Item Thumbnail" />
                    </div>
                </div>
                <div class="portfolio-item">
                    <div class="overlay"></div>
                    <div class="info">
                        <h4 class="title">Sunny Day</h4>
                        <p class="brief-description">Story</p>
                        <a href="#" class="btn btn-sm btn-hover-arrow flex-shrink-0 btn-outline-white btn-outline-2px"><span>read more</span></a>
                    </div>
                    <div class="media-wrapper">
                        <img src="{{ asset('upload/theme_test/work7.png')  }}" alt="Item Thumbnail" />
                    </div>
                </div>
                <div class="portfolio-item">
                    <div class="overlay"></div>
                    <div class="info">
                        <h4 class="title">Rainy Day</h4>
                        <p class="brief-description">Photography</p>
                        <a href="#" class="btn btn-sm btn-hover-arrow flex-shrink-0 btn-outline-white btn-outline-2px"><span>read more</span></a>
                    </div>
                    <div class="media-wrapper">
                        <img src="{{ asset('upload/theme_test/work2.png')  }}" alt="Item Thumbnail" />
                    </div>
                </div>
                <div class="portfolio-item">
                    <div class="overlay"></div>
                    <div class="info">
                        <h4 class="title">Water Everywhere</h4>
                        <p class="brief-description">Story</p>
                        <a href="#" class="btn btn-sm btn-hover-arrow flex-shrink-0 btn-outline-white btn-outline-2px"><span>read more</span></a>
                    </div>
                    <div class="media-wrapper">
                        <img src="{{ asset('upload/theme_test/work4.png')  }}" alt="Item Thumbnail" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END CAROUSEL -->

    <!-- TESTIMONIAL SECTION -->
    <section class="testimonial-section testimonial-fancy slick-carousel bg-image bg-fixed bg-overlay default-overlay" style="background-image: url({{ asset('upload/theme_test/section-test.jpg')  }});">
    <!-- <section class="testimonial-section testimonial-fancy slick-carousel"> -->
        <div class="container heading-center position-relative z-index-1">
            <p class="sub-heading">What our partners say about us</p>
            <h2 class="section-heading">TESTIMONIAL</h2>
            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum purus nec nulla auctor cursus sit amet a justo. Vivamus sed massa urna.</p>
        </div>
        <div class="container testimonial-container position-relative z-index-1">
            <div class="testimonial-body">
                <h2 class="sr-only">TESTIMOMIAL</h2>
                <i class="fa fa-quote-left text-warning"></i>
                <p class="testimonial-bubble">
                    Credibly extend parallel relationships after clicks-and-mortar content. Credibly pontificate team building alignments rather than diverse quality vectors. Monotonectally benchmark business communities for distinctive mindshare.
                </p>
                <div class="testimonial-author">
                    <img src="{{ asset('upload/theme_test/user2.png')  }}" alt="Author" class="pull-left">
                    <span><span class="author-name">Antonius</span> <em>CEO of TheCompany</em></span>
                </div>
            </div>
            <div class="testimonial-body">
                <h2 class="sr-only">TESTIMOMIAL</h2>
                <i class="fa fa-quote-left text-warning"></i>
                <p class="testimonial-bubble">
                    Credibly extend parallel relationships after clicks-and-mortar content. Credibly pontificate team building alignments rather than diverse quality vectors. Monotonectally benchmark business communities for distinctive mindshare.
                </p>
                <div class="testimonial-author">
                    <img src="{{ asset('upload/theme_test/user1.png')  }}" alt="Author" class="pull-left">
                    <span><span class="author-name">Antonius</span> <em>CEO of TheCompany</em></span>
                </div>
            </div>
            <div class="testimonial-body">
                <h2 class="sr-only">TESTIMOMIAL</h2>
                <i class="fa fa-quote-left text-warning"></i>
                <p class="testimonial-bubble">
                    Credibly extend parallel relationships after clicks-and-mortar content. Credibly pontificate team building alignments rather than diverse quality vectors. Monotonectally benchmark business communities for distinctive mindshare.
                </p>
                <div class="testimonial-author">
                    <img src="{{ asset('upload/theme_test/user5.png')  }}" alt="Author" class="pull-left">
                    <span><span class="author-name">Antonius</span> <em>CEO of TheCompany</em></span>
                </div>
            </div>
        </div>
    </section>
    <!-- END TESTIMONIAL SECTION -->



    <!-- BOXED CONTENT SECTION -->
    <!-- <section class="boxed-content-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(https://script.viserlab.com/matrixlab/assets/images/frontend/client/60ae212735ab01622024487.jpg);"> -->
    <section class="boxed-content-section">
        <div class="container heading-center">
            <p class="sub-heading">This is what you need</p>
            <h2 class="section-heading">OUR FEATURES</h2>
            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum purus nec nulla auctor cursus sit amet a justo. Vivamus sed massa urna.</p>
        </div>
        <div class="container position-relative z-index-1">
            <div class="row">
                <div class="col-md-4">
                    <div class="boxed-content">
                        <i class="fa fa-flag"></i>
                        <h2 class="boxed-content-title">GOAL ORIENTED</h2>
                        <p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="boxed-content">
                        <i class="fa fa-globe"></i>
                        <h2 class="boxed-content-title">GLOBAL SERVICE</h2>
                        <p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="boxed-content">
                        <i class="fa fa-cog"></i>
                        <h2 class="boxed-content-title">DYNAMIC CHANGE</h2>
                        <p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="boxed-content">
                        <i class="fa fa-flag"></i>
                        <h2 class="boxed-content-title">GOAL ORIENTED</h2>
                        <p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="boxed-content">
                        <i class="fa fa-globe"></i>
                        <h2 class="boxed-content-title">GLOBAL SERVICE</h2>
                        <p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="boxed-content">
                        <i class="fa fa-cog"></i>
                        <h2 class="boxed-content-title">DYNAMIC CHANGE</h2>
                        <p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END BOXED CONTENT SECTION -->



    <!-- BOXED CONTENT SECTION -->
    <section class="boxed-content-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url({{ asset('upload/theme_test/section-test.jpg')  }});">
    <!-- <section class="boxed-content-section"> -->
        <div class="container heading-center position-relative z-index-1">
            <p class="sub-heading">Follow the steps below to start</p>
            <h2 class="section-heading">HOW TO GET STARTED</h2>
            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum purus nec nulla auctor cursus sit amet a justo. Vivamus sed massa urna.</p>
        </div>
        <div class="container position-relative z-index-1">
            <div class="row">
                <div class="col-md-6">
                    <div class="boxed-content left-aligned left-boxed-icon">
                        <i class="fa fa-flag"></i>
                        <h2 class="boxed-content-title">GOAL ORIENTED</h2>
                        <p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="boxed-content left-aligned left-boxed-icon">
                        <i class="fa fa-globe"></i>
                        <h2 class="boxed-content-title">GLOBAL SERVICE</h2>
                        <p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="boxed-content left-aligned left-boxed-icon">
                        <i class="fa fa-cog"></i>
                        <h2 class="boxed-content-title">DYNAMIC CHANGE</h2>
                        <p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="boxed-content left-aligned left-boxed-icon">
                        <i class="fa fa-users"></i>
                        <h2 class="boxed-content-title">PROFESSIONAL SUPPORT</h2>
                        <p>Holisticly harness just in time technologies via corporate scenarios. Intrinsicly predominate ubiquitous outsourcing through an expanded array of functionalities.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END BOXED CONTENT SECTION -->



    <!-- START TEAM SECTION -->
    <!-- <section class="team-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(https://script.viserlab.com/matrixlab/assets/images/frontend/client/60ae212735ab01622024487.jpg);"> -->
    <section class="team-section">
        <div class="container heading-center position-relative z-index-1">
            <p class="sub-heading">Our Creative Team That Help Our Company</p>
            <h2 class="section-heading">MEET OUR TEAM</h2>
            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum purus nec nulla auctor cursus sit amet a justo. Vivamus sed massa urna.</p>
        </div>
        <div class="container position-relative z-index-1">
            <div class="row">
                <div class="col-md-6">
                    <div class="team-member d-flex">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('upload/theme_test/user1.png')  }}" class="rounded-circle" alt="Sample Image">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="team-name">Michael Summer</h5>
                            <strong>Executive Director</strong>
                            <div class="team-content">
                                <p>Excellent feature! I love it. One day I'm definitely going to put this Bootstrap component into use and I'll let you know once I do.</p>
                            </div>
                            <ul class="list-inline social-icon">
                                <li><a href="#"><i class="lni lni-facebook-filled"></i></a></li>
                                <li><a href="#"><i class="lni lni-twitter-filled"></i></a></li>
                                <li><a href="#"><i class="lni lni-linkedin-original"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-2">
                    <div class="team-member d-flex">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('upload/theme_test/user2.png')  }}" class="rounded-circle" alt="Sample Image">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="team-name">Michael Summer</h5>
                            <strong>Executive Director</strong>
                            <div class="team-content">
                                <p>Excellent feature! I love it. One day I'm definitely going to put this Bootstrap component into use and I'll let you know once I do.</p>
                            </div>
                            <ul class="list-inline social-icon">
                                <li><a href="#"><i class="lni lni-facebook-filled"></i></a></li>
                                <li><a href="#"><i class="lni lni-twitter-filled"></i></a></li>
                                <li><a href="#"><i class="lni lni-linkedin-original"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="team-member d-flex">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('upload/theme_test/user1.png')  }}" class="rounded-circle" alt="Sample Image">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="team-name">Michael Summer</h5>
                            <strong>Executive Director</strong>
                            <div class="team-content">
                                <p>Excellent feature! I love it. One day I'm definitely going to put this Bootstrap component into use and I'll let you know once I do.</p>
                            </div>
                            <ul class="list-inline social-icon">
                                <li><a href="#"><i class="lni lni-facebook-filled"></i></a></li>
                                <li><a href="#"><i class="lni lni-twitter-filled"></i></a></li>
                                <li><a href="#"><i class="lni lni-linkedin-original"></i></a></li>
                                <li><a href="#"><i class="lni lni-dribbble"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-2">
                    <div class="team-member d-flex">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('upload/theme_test/user1.png')  }}" class="rounded-circle" alt="Sample Image">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="team-name">Michael Summer</h5>
                            <strong>Executive Director</strong>
                            <div class="team-content">
                                <p>Excellent feature! I love it. One day I'm definitely going to put this Bootstrap component into use and I'll let you know once I do.</p>
                            </div>
                            <ul class="list-inline social-icon">
                                <li><a href="#"><i class="lni lni-facebook-filled"></i></a></li>
                                <li><a href="#"><i class="lni lni-twitter-filled"></i></a></li>
                                <li><a href="#"><i class="lni lni-linkedin-original"></i></a></li>
                                <li><a href="#"><i class="lni lni-dribbble"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END TEAM SECTION -->




    <!-- START CLIENT SECTION -->
    <section class="client-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url({{ asset('upload/theme_test/section-test.jpg')  }});">
    <!-- <section class="client-section"> -->
        <div class="container heading-center position-relative z-index-1">
            <p class="sub-heading">See Our Happy Clients</p>
            <h2 class="section-heading">OUR CLIENTS</h2>
            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum purus nec nulla auctor cursus sit amet a justo. Vivamus sed massa urna.</p>
        </div>
        <div class="container position-relative z-index-1">
            <div class="row">
                <div class="col-md-12">
                    <ul class="list-inline list-client-logo">
                        <li>
                            <a href="#"><img src="{{ asset('upload/theme_test/logo1.png')  }}" alt="logo" /></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{ asset('upload/theme_test/logo2.png')  }}" alt="logo" /></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{ asset('upload/theme_test/logo3.png')  }}" alt="logo" /></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{ asset('upload/theme_test/logo4.png')  }}" alt="logo" /></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{ asset('upload/theme_test/logo5.png')  }}" alt="logo" /></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- END TEAM SECTION -->



    <!-- START PRICING SECTION -->
    <!-- <section class="pricing-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(https://script.viserlab.com/matrixlab/assets/images/frontend/client/60ae212735ab01622024487.jpg);"> -->
    <section class="pricing-section">
        <div class="container heading-center position-relative z-index-1">
            <p class="sub-heading">Select Our Affordable Pricing</p>
            <h2 class="section-heading">OUR PRICING</h2>
            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum purus nec nulla auctor cursus sit amet a justo. Vivamus sed massa urna.</p>
        </div>
        <div class="container position-relative z-index-1">
            <ul class="nav nav-pills d-flex align-items-center justify-content-center mb-25" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="monthly-pricing-tab" data-bs-toggle="pill" data-bs-target="#monthly-pricing" type="button" role="tab" aria-controls="monthly-pricing" aria-selected="true">Monthly</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="yearly-pricing-tab" data-bs-toggle="pill" data-bs-target="#yearly-pricing" type="button" role="tab" aria-controls="yearly-pricing" aria-selected="false">Yearly</button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="monthly-pricing" role="tabpanel" aria-labelledby="monthly-pricing-tab">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="single-pricing">
                                <h4>Free Licence</h4>
                                <h3>$0.00 <span class="pricing-frequency">/&nbsp;month</span></h3>
                                <ul>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">All limited links</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Own analytics platform</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon bg-danger text-white">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="pricing-item-details">Chat support</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon bg-danger text-white">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="pricing-item-details">Optimize hasttags</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon bg-danger text-white">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="pricing-item-details">Unlimited users</div>
                                    </li>
                                </ul>
                                <a href="javascript:void(0)" class="btn btn-outline-primary btn-lg btn-outline-3px btn-pricing">Open Demo</a>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="single-pricing active">
                                <h4>Rugalar Licence</h4>
                                <h3>$29.00 <span class="pricing-frequency">/&nbsp;month</span></h3>
                                <ul>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">All limited links</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Own analytics platform</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Chat support</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Optimize hasttags</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon bg-danger text-white">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="pricing-item-details">Unlimited users</div>
                                    </li>
                                </ul>
                                <a href="javascript:void(0)" class="btn btn-primary btn-lg btn-pricing">Dowload Now</a>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="single-pricing">
                                <h4>Extended License</h4>
                                <h3>$79.00 <span class="pricing-frequency">/&nbsp;month</span></h3>
                                <ul>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">All limited links</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Own analytics platform</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Chat support</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Optimize hasttags</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Unlimited users</div>
                                    </li>
                                </ul>
                                <a href="javascript:void(0)" class="btn btn-outline-primary btn-lg btn-outline-3px btn-pricing">Download Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade show" id="yearly-pricing" role="tabpanel" aria-labelledby="monthly-pricing-tab">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="single-pricing">
                                <h4>Free Licence</h4>
                                <h3>$0.00 <span class="pricing-frequency">/&nbsp;year</span></h3>
                                <ul>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">All limited links</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Own analytics platform</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon bg-danger text-white">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="pricing-item-details">Chat support</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon bg-danger text-white">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="pricing-item-details">Optimize hasttags</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon bg-danger text-white">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="pricing-item-details">Unlimited users</div>
                                    </li>
                                </ul>
                                <a href="javascript:void(0)" class="btn btn-outline-primary btn-lg btn-outline-3px btn-pricing">Open Demo</a>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="single-pricing active">
                                <h4>Rugalar Licence</h4>
                                <h3>$29.00 <span class="pricing-frequency">/&nbsp;year</span></h3>
                                <ul>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">All limited links</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Own analytics platform</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Chat support</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Optimize hasttags</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon bg-danger text-white">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="pricing-item-details">Unlimited users</div>
                                    </li>
                                </ul>
                                <a href="javascript:void(0)" class="btn btn-primary btn-lg btn-pricing">Dowload Now</a>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="single-pricing">
                                <h4>Extended License</h4>
                                <h3>$79.00 <span class="pricing-frequency">/&nbsp;year</span></h3>
                                <ul>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">All limited links</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Own analytics platform</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Chat support</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Optimize hasttags</div>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <div class="pricing-item-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="pricing-item-details">Unlimited users</div>
                                    </li>
                                </ul>
                                <a href="javascript:void(0)" class="btn btn-outline-primary btn-lg btn-outline-3px btn-pricing">Download Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END PRICING SECTION -->



    <!-- START TEAM SECTION -->
    <section class="faq-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url({{ asset('upload/theme_test/section-test.jpg')  }});">
    <!-- <section class="faq-section"> -->
        <div class="container heading-center position-relative z-index-1">
            <p class="sub-heading">Learn More About Our Services</p>
            <h2 class="section-heading">FAQS</h2>
            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum purus nec nulla auctor cursus sit amet a justo. Vivamus sed massa urna.</p>
        </div>
        <div class="container position-relative z-index-1">
            <div class="row">
                <div class="col-md-6">
                    <div class="accordion-style">
                        <div class="accordion" id="accordionExampleOne">
                            <div class="single-accordion mb-20 wow fadeInUp" data-wow-delay=".2s">
                                <div class="accordion-btn">
                                    <button class="d-block text-start w-100 collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                        aria-controls="collapseOne">
                                        <span>What People say about us</span>
                                    </button>
                                </div>

                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                    data-parent="#accordionExampleOne">
                                    <div class="accordion-content">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                    </div>
                                </div>
                            </div>
                            <div class="single-accordion mb-20 wow fadeInUp" data-wow-delay=".2s">
                                <div class="accordion-btn">
                                    <button class="d-block text-start w-100 collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true"
                                        aria-controls="collapseTwo">
                                        <span>Know more about us</span>
                                    </button>
                                </div>

                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                    data-parent="#accordionExampleOne">
                                    <div class="accordion-content">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                    </div>
                                </div>
                            </div>
                            <div class="single-accordion mb-20 wow fadeInUp" data-wow-delay=".2s">
                                <div class="accordion-btn">
                                    <button class="d-block text-start w-100 collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true"
                                        aria-controls="collapseThree">
                                        <span>Make a user Profile</span>
                                    </button>
                                </div>

                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                    data-parent="#accordionExampleOne">
                                    <div class="accordion-content">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="accordion-style">
                        <div class="accordion" id="accordionExampleTwo">
                            <div class="single-accordion mb-20 wow fadeInUp" data-wow-delay=".2s">
                                <div class="accordion-btn">
                                    <button class="d-block text-start w-100 collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#accordionExampleTwoCollapseOne" aria-expanded="true"
                                        aria-controls="accordionExampleTwoCollapseOne">
                                        <span>What People say about us</span>
                                    </button>
                                </div>

                                <div id="accordionExampleTwoCollapseOne" class="collapse" aria-labelledby="headingOne"
                                    data-parent="#accordionExampleTwo">
                                    <div class="accordion-content">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                    </div>
                                </div>
                            </div>
                            <div class="single-accordion mb-20 wow fadeInUp" data-wow-delay=".2s">
                                <div class="accordion-btn">
                                    <button class="d-block text-start w-100 collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#accordionExampleTwoCollapseTwo" aria-expanded="true"
                                        aria-controls="accordionExampleTwoCollapseTwo">
                                        <span>Know more about us</span>
                                    </button>
                                </div>

                                <div id="accordionExampleTwoCollapseTwo" class="collapse" aria-labelledby="headingTwo"
                                    data-parent="#accordionExampleTwo">
                                    <div class="accordion-content">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                    </div>
                                </div>
                            </div>
                            <div class="single-accordion mb-20 wow fadeInUp" data-wow-delay=".2s">
                                <div class="accordion-btn">
                                    <button class="d-block text-start w-100 collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#accordionExampleTwoCollapseThree" aria-expanded="true"
                                        aria-controls="accordionExampleTwoCollapseThree">
                                        <span>Make a user Profile</span>
                                    </button>
                                </div>

                                <div id="accordionExampleTwoCollapseThree" class="collapse" aria-labelledby="headingThree"
                                    data-parent="#accordionExampleTwo">
                                    <div class="accordion-content">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END TEAM SECTION -->


    <!-- START BLOG SECTION -->
    <!-- <section class="blog-section bg-image bg-fixed bg-overlay default-overlay" style="background-image: url(https://script.viserlab.com/matrixlab/assets/images/frontend/client/60ae212735ab01622024487.jpg);"> -->
    <section class="blog-section">
        <div class="container heading-center position-relative z-index-1">
            <p class="sub-heading">Blog</p>
            <h2 class="section-heading">Latest Blogs</h2>
            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum purus nec nulla auctor cursus sit amet a justo. Vivamus sed massa urna.</p>
        </div>

        <div class="container position-relative z-index-1">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="single-blog">
                        <div class="blog-img">
                            <a href="blog-single.html"><img src="{{ asset('upload/theme_test/blog-1.jpg')  }}" alt=""></a>
                            <span class="date-meta">15 June, 2025</span>
                        </div>
                        <div class="blog-content">
                            <h4><a href="blog-single.html">Start a Business Guide</a></h4>
                            <p>Lorem ipsum dolor sit amet, adipscing elitr, sed diam nonumy eirmod tempor ividunt dolore
                            magna.</p>
                            <a href="#" class="btn btn-hover-arrow flex-shrink-0 btn-outline-primary btn-outline-3px py-lg-2 btn-block br-10">
                                <span>READ MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="single-blog">
                        <div class="blog-img">
                            <a href="blog-single.html"><img src="{{ asset('upload/theme_test/blog-2.jpg')  }}" alt=""></a>
                            <span class="date-meta">15 June, 2025</span>
                        </div>
                        <div class="blog-content">
                            <h4><a href="blog-single.html">Plan for what is difficult</a></h4>
                            <p>Lorem ipsum dolor sit amet, adipscing elitr, sed diam nonumy eirmod tempor ividunt dolore
                            magna.</p>
                            <a href="#" class="btn btn-hover-arrow flex-shrink-0 btn-outline-primary btn-outline-3px py-lg-2 btn-block br-10">
                                <span>READ MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="single-blog">
                        <div class="blog-img">
                            <a href="blog-single.html"><img src="{{ asset('upload/theme_test/blog-3.jpg')  }}" alt=""></a>
                            <span class="date-meta">15 June, 2025</span>
                        </div>
                        <div class="blog-content">
                            <h4><a href="blog-single.html">Colorful Easter Eggs</a></h4>
                            <p>Lorem ipsum dolor sit amet, adipscing elitr, sed diam nonumy eirmod tempor ividunt dolore
                            magna.</p>
                            <a href="#" class="btn btn-hover-arrow flex-shrink-0 btn-outline-primary btn-outline-3px py-lg-2 btn-block br-10">
                                <span>READ MORE</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- END BLOG SECTION -->

    <!-- START CONTACT US SECTION -->
    <section class="contact-section bg-image bg-fixed bg-overlay default-overlay" id="contact" style="background-image: url({{ asset('upload/theme_test/section-test.jpg')  }});">
    <!-- <section class="contact-section" id="contact"> -->
        <div class="container heading-center">
            <p class="sub-heading">Contact Us</p>
            <h2 class="section-heading">Get in touch with us</h2>
            <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur interdum purus nec nulla auctor cursus sit amet a justo. Vivamus sed massa urna.</p>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <ul class="contact-info contact-half mb-50 fa-ul">
                        <li><i class="fa fa-li fa-location-pin"></i>
                            <h3 class="title">Address</h3>
                            <p class="description">76 Ninth Ave, New York, USA</p>
                        </li>
                        <li><i class="fa fa-li fa-phone"></i>
                            <h3 class="title">Phone</h3>
                            <p class="description">+621 234 4567</p>
                        </li>
                        <li><i class="fa fa-li fa-envelope"></i>
                            <h3 class="title">Email</h3>
                            <p class="description"><a href="mailto:hello@yourcompany.com">hello@yourcompany.com</a></p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-7">
                    <!-- CONCTACT FORM -->
                    <div class="contact-form-wrapper">
                        <form id="contact-form" class="form-horizontal mb-30" role="form" novalidate>
                            <div class="row mb-3 mt-3">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="contact-name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="contact-name" name="name" placeholder="Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="contact-name" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="contact-email" name="email" placeholder="Email" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 mt-3 form-group">
                                <label for="contact-subject" class="form-label">Subject</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="contact-subject" name="subject" placeholder="Subject">
                                </div>
                            </div>

                            <div class="mb-3 mt-3 form-group">
                                <label for="contact-message" class="form-label">Message</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" id="contact-message" name="message" rows="5" cols="30" placeholder="Message" required></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button id="submit-button" type="button" class="btn btn-lg btn-primary pull-right">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- END CONCTACT FORM -->
                </div>
            </div>
        </div>
    </section>
    <!-- END CONTACT US SECTION -->



    <!-- ========================= call-to-action-section start ========================= -->
    <section class="cta-section pt-70 pb-70 bg-primary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="section-title mb-30">
                        <h2 class="text-white mb-40 wow fadeInUp" data-wow-delay=".4s">Build Web Application Faster Than Ever</h2>
                        
                        <p class="lead text-white wow fadeInUp" data-wow-delay=".4s">Saved 1000s of dollars when developing web applications from scratch.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <a href="javascript:void(0)" class="btn btn-hover-arrow flex-shrink-0 btn-warning py-lg-3 px-lg-5 wow fadeInUp" data-wow-delay=".4s">
                        <span>Purchase Now</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- ========================= subscribe-section end ========================= -->


@endsection
