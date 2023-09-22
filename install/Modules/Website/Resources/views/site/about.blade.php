@extends('website::layouts.site')

@section('page-class', 'unsticky-navbar')

@section('breadcrumbs-position', 'centered')

@section('breadcrumbs')
    <ol class="breadcrumb link-accent">
        <li><a href="{{ route('site.website.index') }}">@lang('Home')</a></li>
        <li>@lang('Contact Us')</li>
    </ol>
    <h1 class="page-title">CONTACT US</h1>
@endsection

@section('content')
    <!-- START CONTACT US SECTION -->
    <!-- <section class="contact-section bg-image bg-fixed bg-overlay default-overlay" id="contact" style="background-image: url({{ asset('upload/theme_test/section-test.jpg')  }});"> -->
    <section class="contact-section" id="contact">
        <div class="container heading-center">
            <!-- <p class="sub-heading">Contact Us</p> -->
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
@endsection