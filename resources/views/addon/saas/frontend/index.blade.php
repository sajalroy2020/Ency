@extends('addon.saas.frontend.layouts.app')
@push('title')
{{ __(@$pageTitle) }}
@endpush
@section('content')
@if (isset($section['hero_area']) && $section['hero_area']->status == STATUS_ACTIVE)
<!-- Start Banner -->
<section class="pb-sm-150 pt-sm-64 py-30 landing-banner-wrap position-relative z-1">
    <div class="item-1"></div>
    <div class="item-2"></div>
    <div class="item-3"></div>
    <div class="item-4"></div>
    <div class="item-5"></div>
    <div class="container">
        <div class="row justify-content-center pb-30">
            <div class="col-lg-10">
                <div class="text-center">

                    <p class="landing-section-subtitle">{{ __($section['hero_area']->page_title) }}</p>
                    <h4 class="landing-section-title pb-15">{{ __($section['hero_area']->title) }}
                    </h4>
                    <p class="max-w-640 m-auto fs-18 fw-400 lh-28 text-para-text pb-24">
                        {{ __($section['hero_area']->description) }}</p>
                    <a href="{{ route('login') }}"
                        class="d-inline-flex py-17 px-33 bd-ra-48 bg-main-color fs-18 fw-600 lh-22 text-white">{{
                        __('Request a Demo') }}</a>
                </div>
            </div>
        </div>
        <div class="landing-hero-content">
            <div class="img"><img src="{{ getFileUrl($section['hero_area']->banner_image) }}"
                    alt="{{ getOption('app_name') }}" /></div>
        </div>
    </div>
</section>
<!-- End Banner -->
@endif
@if (isset($section['features']) && $section['features']->status == STATUS_ACTIVE)
<!-- Start Features List -->
<section class="pb-sm-150 pb-30 landing-feature-wrap" id="features">
    <div class="item-1"></div>
    <div class="item-2"></div>
    <div class="item-3"></div>
    <div class="item-4"></div>
    <div class="item-5"></div>
    <div class="item-6"></div>
    <div class="item-7"></div>
    <div class="item-8"></div>
    <div class="item-9"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle">{{ $section['features']->page_title }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title">{{ $section['features']->title }}</h4>
                </div>
            </div>
        </div>
        <div class="row rg-30">
            @foreach ($features as $feature)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="features-list-item">
                    <div class="icon bg-purple-10"><img src="{{ getFileUrl($feature->image) }}"
                            alt="{{ getOption('app_name') }}" />
                    </div>
                    <div class="title">{{ $feature->title }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End Features List -->
@endif
@if (isset($section['services']) && $section['services']->status == STATUS_ACTIVE)
<!-- Start Features Block -->
<section class="bg-color6 py-sm-150 py-30 position-relative z-1" id="services">
    <div class="container">
        <div class="features-block">
            @foreach ($services as $service)
            <div class="features-block-item">
                <div class="row align-items-center rg-20">
                    <div class="col-lg-5">
                        <div class="item-content">
                            <p class="landing-section-subtitle">{{ $service->name }}</p>
                            <h4 class="lh-sm-57 lh-44 landing-section-title">{{ $service->title }}</h4>
                            <ul class="lists">
                                <li>
                                    <div class="icon"><img
                                            src="{{ asset('assets/images/icon/features-check-icon.svg') }}"
                                            alt="{{ getOption('app_name') }}" /></div>
                                    <div class="content">
                                        <h4 class="title">{{ $service->sub_title }}</h4>
                                        <p class="info">{{ $service->description }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="image"><img src="{{ getFileUrl($service->image) }}"
                                alt="{{ getOption('app_name') }}" />
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End Features Block -->
@endif
@if (isset($section['core_features']) && $section['core_features']->status == STATUS_ACTIVE)
<!-- Start Core Features -->
<section class="py-sm-150 py-30 landing-coreFeature-wrap">
    <div class="item-1"></div>
    <div class="item-2"></div>
    <div class="item-3"></div>
    <div class="item-4"></div>
    <div class="item-5"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle">{{ $section['core_features']->page_title }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title">{{ $section['core_features']->title }}</h4>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-start flex-column flex-lg-row g-20">
            <div class="nav flex-row flex-lg-column flex-wrap flex-lg-nowrap justify-content-center nav-pills zTab-reset zTab-vertical-one g-20"
                id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @foreach ($coreFeatures as $key => $coreFeature)
                <button class="nav-link {{ $key == 0 ? 'active' : '' }}" id="v-pills-ChattingSystem-tab-{{ $key }}"
                    data-bs-toggle="pill" data-bs-target="#v-pills-ChattingSystem-{{ $key }}" type="button" role="tab"
                    aria-controls="v-pills-ChattingSystem-{{ $key }}" aria-selected="true">{{ __($coreFeature->title)
                    }}</button>
                @endforeach
            </div>
            <div class="tab-content flex-grow-1" id="v-pills-tabContent">
                @foreach ($coreFeatures as $key => $coreFeature)
                <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="v-pills-ChattingSystem-{{ $key }}"
                    role="tabpanel" aria-labelledby="v-pills-ChattingSystem-tab-{{ $key }}" tabindex="0">
                    <div class="landing-coreFeatures-tabContent">
                        <div class="img">
                            <img src="{{ getFileUrl($coreFeature->image) }}" alt="{{ __($coreFeature->title) }}" />
                        </div>
                        <div class="content">
                            <h4 class="title">{{ $coreFeature->title }}</h4>
                            <p class="info">{{ $coreFeature->description }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- End Core Features -->
@endif
@if (isset($section['pricing']) && $section['pricing']->status == STATUS_ACTIVE)
<!-- Start Pricing -->
<section class="pb-sm-150 pb-30 landing-pricing-wrap position-relative z-1" id="price">
    <div class="item-1"></div>
    <div class="item-2"></div>
    <div class="item-3"></div>
    <div class="item-4"></div>
    <div class="item-5"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle">{{ $section['pricing']->page_title }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title">{{ $section['pricing']->title }}</h4>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center align-items-center g-20 pb-50">
            <h4 class="fs-20 fw-500 lh-26 text-title-black">{{ __('Monthly') }}</h4>
            <div class="price-plan-tab">
                <div class="zCheck form-check form-switch zPrice-plan-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="zPrice-plan-switch" />
                </div>
            </div>
            <h4 class="fs-20 fw-500 lh-26 text-title-black">{{ __('Yearly') }}</h4>
        </div>
        <div class="row rg-20 justify-content-center">
            @foreach ($packages as $key => $package)
            <div class="col-xl-4 col-lg-6">
                <div
                    class="price-plan-one {{ $key > 0 ? ($key == 1 ? 'price-plan-standard' : 'price-plan-enterprise') : '' }} {{ $package->is_popular == STATUS_ACTIVE ? 'price-plan-popular' : '' }} ">
                    <div class="price-head">
                        <h4 class="title">{{ $package->name }}</h4>
                        <h4 class="plan-price zPrice-plan-monthly">
                            <span>{{ showPrice($package->monthly_price) }}</span>/{{ __('Monthly') }}
                        </h4>
                        <h4 class="plan-price zPrice-plan-yearly">
                            <span>{{ showPrice($package->yearly_price) }}</span>/{{ __('Yearly') }}
                        </h4>
                    </div>
                    <div class="price-body">
                        <ul class="zList-pb-27 mb-50">
                            <li>
                                <div class="d-flex align-items-start g-10">
                                    <div class="flex-shrink-0 d-flex max-w-22"><img
                                            src="{{ asset('assets/images/icon/price-check-icon.svg') }}"
                                            alt="{{ $package->name }}" />
                                    </div>
                                    <p class="fs-18 fw-400 lh-22 text-para-text">
                                        @if ($package->number_of_client == -1)
                                        {{ __('Add Unlimited Clients') }}
                                        @else
                                        {{ __('Add ' . $package->number_of_client . ' Clients') }}
                                        @endif
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex align-items-start g-10">
                                    <div class="flex-shrink-0 d-flex max-w-22"><img
                                            src="{{ asset('assets/images/icon/price-check-icon.svg') }}"
                                            alt="{{ $package->name }}" />
                                    </div>
                                    <p class="fs-18 fw-400 lh-22 text-para-text">
                                        @if ($package->number_of_order == -1)
                                        {{ __('Add Unlimited Orders') }}
                                        @else
                                        {{ __('Add ' . $package->number_of_order . ' Orders') }}
                                        @endif
                                    </p>
                                </div>
                            </li>
                            @foreach (json_decode($package->others) ?? [] as $other)
                            <li>
                                <div class="d-flex align-items-start g-10">
                                    <div class="flex-shrink-0 d-flex max-w-22">
                                        <img src="{{ asset('assets/images/icon/price-check-icon.svg') }}"
                                            alt="{{ $package->name }}" />
                                    </div>
                                    <p class="fs-18 fw-400 lh-22 text-para-text">
                                        {{ __($other) }} </p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('admin.subscription.index', [' id' => $package->id]) }}" class="link">{{
                            __('Subscribe Now') }}</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End Pricing -->
@endif
@if (isset($section['testimonials_area']) && $section['testimonials_area']->status == STATUS_ACTIVE)
<!-- Start Testimonial -->
<section class="py-sm-150 py-30 bg-color6 position-relative z-1 overflow-hidden" id="testimonial">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle bg-white-8">{{ $section['testimonials_area']->page_title }}
                    </p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title text-white">
                        {{ $section['testimonials_area']->title }}</h4>
                </div>
            </div>
        </div>
        <div class="landing-testimonial-wrap">
            <div class="swiper ldTestiItems">
                <div class="swiper-wrapper">
                    @foreach ($testimonials as $testimonial)
                    <div class="swiper-slide">
                        <div class="landing-testimonial-item">
                            <div class="top">
                                <div class="user">
                                    <div class="img">
                                        <img src="{{ getFileUrl($testimonial->image) }}"
                                            alt="{{ getOption('app_name') }}" />
                                    </div>
                                    <div class="content">
                                        <h4 class="name">{{ $testimonial->name }}</h4>
                                        <p class="userUrl">{{ $testimonial->designation }}</p>
                                    </div>
                                </div>
                                <div class="icon">
                                    <img src="{{ asset('assets/images/icon/quote-ld.svg') }}"
                                        alt="{{ getOption('app_name') }}" />
                                </div>
                            </div>
                            <p class="text">{{ $testimonial->comment }}</p>
                            <div class="bottom cg-10">
                                <div class="content">
                                    <h3 class="testi-company">{{ $testimonial->company_name }}</h3>
                                    <p class="date">{{ $testimonial->created_at?->format('Y-m-d') }}</p>
                                </div>
                                <ul class="ld-testi-rating">
                                    {!! reviewStar($testimonial->rating) !!}
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="arrowControl">
                    <div class="swiper-button-next"><i class="fa-solid fa-angle-right"></i></div>
                    <div class="swiper-button-prev"><i class="fa-solid fa-angle-left"></i></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Testimonial -->
@endif
@if (isset($section['faqs_area']) && $section['faqs_area']->status == STATUS_ACTIVE)
<!-- Start FAQ's -->
<section class="py-sm-150 py-30 landing-faq-wrap" id="faq">
    <div class="item-1"></div>
    <div class="item-2"></div>
    <div class="item-3"></div>
    <div class="item-4"></div>
    <div class="item-5"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle">{{ $section['faqs_area']->page_title }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title">{{ $section['faqs_area']->title }}</h4>
                </div>
            </div>
        </div>
        <div class="accordion zAccordion-reset zAccordion-one" id="accordionExample">
            <div class="row rg-24">
                @php
                $i = 1;
                $take = count($faqs);
                @endphp
                @if ($take > 1)
                <div class="col-lg-6">
                    @foreach ($faqs->take($take / 2) as $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne{{ $i }}" aria-controls="collapseOne{{ $i }}">{{ $i }}.
                                {{ $faq->title }}</button>
                        </h2>
                        <div id="collapseOne{{ $i }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p class="">{{ $faq->description }}</p>
                            </div>
                        </div>
                    </div>
                    @php $i++; @endphp
                    @endforeach
                </div>
                @endif
                <div class="col-lg-6">
                    @foreach ($faqs->skip($take / 2) as $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne{{ $i }}" aria-controls="collapseOne{{ $i }}">{{ $i }}.
                                {{ $faq->title }}</button>
                        </h2>
                        <div id="collapseOne{{ $i }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p class="">{{ $faq->description }}</p>
                            </div>
                        </div>
                    </div>
                    @php $i++; @endphp
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End FAQ's -->
@endif
@endsection
