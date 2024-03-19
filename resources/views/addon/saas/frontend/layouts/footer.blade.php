<!-- Start Footer -->
<section class="landing-footer">
    <div class="container">
        <!-- Top -->
        <div class="landing-footer-top">
            <div class="container">
                <div class="row rg-20">
                    <div class="col-lg-4 col-md-6">
                        <div class="max-w-366">
                            <div class="max-w-194 pb-22"><img src="{{ getSettingImage('app_logo') }}"
                                    alt="{{ getOption('app_name') }}" /></div>
                            <p class="pb-32 fs-18 fw-400 lh-28 text-title-black">{{ getOption('app_footer_text') }}</p>
                            <ul class="d-flex align-items-center g-12 landing-footer-social">
                                @if (getOption('social_media_facebook'))
                                    <li>
                                        <a target="__blank" href="{{ getOption('social_media_facebook') }}"
                                            class="w-40 h-40 rounded-circle bd-one bd-c-para-text d-flex justify-content-center align-items-center text-title-black"><i
                                                class="fa-brands fa-facebook-f"></i></a>
                                    </li>
                                @endif
                                @if (getOption('social_media_twitter'))
                                    <li>
                                        <a target="__blank" href="{{ getOption('social_media_twitter') }}"
                                            class="w-40 h-40 rounded-circle bd-one bd-c-para-text d-flex justify-content-center align-items-center text-title-black"><i
                                                class="fa-brands fa-twitter"></i></a>
                                    </li>
                                @endif
                                @if (getOption('social_media_linkedin'))
                                    <li>
                                        <a target="__blank" href="{{ getOption('social_media_linkedin') }}"
                                            class="w-40 h-40 rounded-circle bd-one bd-c-para-text d-flex justify-content-center align-items-center text-title-black"><i
                                                class="fa-brands fa-linkedin-in"></i></a>
                                    </li>
                                @endif
                                @if (getOption('social_media_skype'))
                                    <li>
                                        <a target="__blank" href="{{ getOption('social_media_skype') }}"
                                            class="w-40 h-40 rounded-circle bd-one bd-c-para-text d-flex justify-content-center align-items-center text-title-black"><i
                                                class="fa-brands fa-skype"></i></a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="pl-xl-70">
                            <h4 class="pb-37 fs-24 fw-500 lh-30 text-title-black">{{ __('Company') }}</h4>
                            <ul class="zList-pb-21">
                                <li><a href="#"
                                        class="fs-18 fw-400 lh-27 text-title-black hover-color-main-color">{{ __('Home') }}</a>
                                </li>
                                @if ($section['features']->status == STATUS_ACTIVE)
                                    <li><a href="#features"
                                            class="fs-18 fw-400 lh-27 text-title-black hover-color-main-color">{{ __('Features') }}</a>
                                    </li>
                                @endif
                                @if ($section['pricing']->status == STATUS_ACTIVE)
                                    <li><a href="#price"
                                            class="fs-18 fw-400 lh-27 text-title-black hover-color-main-color">{{ __('Pricing') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="pl-xl-70">
                            <h4 class="pb-37 fs-24 fw-500 lh-30 text-title-black">{{ __('Info') }}</h4>
                            <ul class="zList-pb-21">
                                @if ($section['faqs_area']->status == STATUS_ACTIVE)
                                    <li><a href="#faq"
                                            class="fs-18 fw-400 lh-27 text-title-black hover-color-main-color">{{ __('Faqs') }}</a>
                                    </li>
                                @endif
                                @if ($section['testimonials_area']->status == STATUS_ACTIVE)
                                    <li><a href="#testimonial"
                                            class="fs-18 fw-400 lh-27 text-title-black hover-color-main-color">{{ __('Testimonials') }}</a>
                                    </li>
                                @endif
                                @if ($section['services']->status == STATUS_ACTIVE)
                                    <li><a href="#services"
                                            class="fs-18 fw-400 lh-27 text-title-black hover-color-main-color">{{ __('Services') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="max-w-131 ms-lg-auto">
                            <h4 class="pb-37 fs-24 fw-500 lh-30 text-title-black">{{ __('Support') }}</h4>
                            <ul class="zList-pb-21">
                                <li>
                                    <a href="{{ route('register') }}"
                                        class="fs-18 fw-400 lh-27 text-title-black hover-color-main-color">{{ __('Sign Up') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('login') }}"
                                        class="fs-18 fw-400 lh-27 text-title-black hover-color-main-color">{{ __('Sign In') }}</a>
                                </li>
                                @auth
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            class="fs-18 fw-400 lh-27 text-title-black hover-color-main-color">{{ __('Logout') }}</a>
                                    </li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bottom -->
        @if (getOption('app_copyright'))
            <div class="landing-footer-bottom">
                <p class="fs-16 fw-400 lh-22 text-title-black">{{ getOption('app_copyright') }}
                    @if (getOption('develop_by'))
                        {{ __('By') }}
                        <a href="{{ route('frontend') }}"
                            class="text-purple text-decoration-underline">{{ getOption('develop_by') }}</a>
                    @endif
                </p>
            </div>
        @endif
    </div>
</section>
<!-- End Footer -->
