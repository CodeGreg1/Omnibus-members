@extends('website::layouts.site')

@section('page-class', 'about-page-1')

@section('content')
    <!-- Start Page Header -->
    <section class="page-header pt-60">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-7 col-md-9 mx-auto">
                    <div class="section-title text-center mb-55">
                        <h2 class="wow fadeInUp" data-wow-delay=".4s">Get in touch with us</h2>
                        <p class="wow fadeInUp" data-wow-delay=".6s">Do you have questions about our products or need a quote? Use the contact form below and we will get back to you.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Page Header -->

    <!-- Start Contact Section -->
    <section class="pb-70">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="mb-5">
                        <h4 class="mb-4">Global Offices</h4>
                        <div>
                            <h5 class="wow fadeInUp" data-wow-delay=".4s">Country</h5>
                            <h6 class="wow fadeInUp" data-wow-delay=".5s">Province</h6>
                            <p class="wow fadeInUp" data-wow-delay=".6s">Street name 21, Ipsum, 12345, City</p>
                            <p class="wow fadeInUp mt-4" data-wow-delay=".6s">Phone: +01 1234 456 678</p>
                            <p class="wow fadeInUp" data-wow-delay=".6s">Fax: +01 1234 567 890</p>
                            <p class="wow fadeInUp" data-wow-delay=".6s">Email: <a href="#">info@yourmail.com</a> </p>
                        </div>

                        <div class="mt-5">
                            <h5 class="wow fadeInUp" data-wow-delay=".4s">Country</h5>
                            <h6 class="wow fadeInUp" data-wow-delay=".5s">Province</h6>
                            <p class="wow fadeInUp" data-wow-delay=".6s">Street name 21, Ipsum, 12345, City</p>
                            <p class="wow fadeInUp mt-4" data-wow-delay=".6s">Phone: +01 1234 456 678</p>
                            <p class="wow fadeInUp" data-wow-delay=".6s">Fax: +01 1234 567 890</p>
                            <p class="wow fadeInUp" data-wow-delay=".6s">Email: <a href="#">info@yourmail.com</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 mb-5 mb-md-0">
                    <h2 class="wow fadeInUp" data-wow-delay=".4s">Contact form</h2>
                    <p class="wow fadeInUp" data-wow-delay=".6s">Use the contact form if you have questions about our products. Our sales team will be happy to help you:</p>
                    
                    <form action="#" method="post" class="mb-5 mt-5 mb-lg-7 wow fadseInUp" data-wow-delay=".6s">
                        <div class="row">
                            <!-- Input -->
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="name">Your name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="John Doe" required="">
                            </div>
                            <!-- End Input -->

                            <!-- Input -->
                            <div class="col-sm-6 mb-3">
                                <label class="form-label" for="email">Your email address</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="john@gmail.com" required="">
                            </div>

                            <div class="w-100"></div>

                            <!-- Input -->

                            <!-- Services -->
                            <div class="col-sm-12 mb-3">
                                <label class="form-label" for="subject">Subject</label>
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="Web Design" required="">
                            </div>
                            <!-- End Input -->
                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" name="message" placeholder="Hi there...." required=""></textarea>
                        </div>

                        <div class="d-md-flex justify-content-between align-items-center">
                            <p class="small mb-4 text-muted mb-md-0">We'll get back to you in 1-2 business days.</p>
                            <input type="submit" name="submit" value="Submit message" id="sendBtn" class="btn btn-lg btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End Contact Section -->

    <!-- ========================= call-to-action-section start ========================= -->
    <section class="cta-section pt-60 pb-70 bg-primary">
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