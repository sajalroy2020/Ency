<div class="row rg-20 justify-content-center">
    @foreach ($packages as $package)
        <div class="col-xl-4">
            <form class="ajax" action="{{ route('admin.subscription.get.gateway') }}" method="post"
                enctype="multipart/form-data" data-handler="setPaymentModal">
                @csrf

                <input type="hidden" name="id" value="{{ $package->id }}">
                <input type="hidden" class="plan_type" name="duration_type" value="1">

                <div class="price-plan-one shadow-lg price-plan-mySubscription {{ $package->is_default == ACTIVE ? 'price-plan-popular active' : '' }}">
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
                                    <div class="flex-shrink-0 d-flex max-w-22">
                                        <img src="{{ asset('assets/images/icon/price-check-icon.svg') }}" />
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
                                    <div class="flex-shrink-0 d-flex max-w-22">
                                        <img src="{{ asset('assets/images/icon/price-check-icon.svg') }}" />
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
                                            <img src="{{ asset('assets/images/icon/price-check-icon.svg') }}" />
                                        </div>
                                        <p class="fs-18 fw-400 lh-22 text-para-text">
                                            {{ __($other) }} </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @if ($currentPackage?->package_id == $package->id)
                            <button type="submit" class="btn link"
                                title="{{ __('Current Package') }}">{{ __('Current Package') }}</button>
                        @else
                            <button type="submit" class="btn link"
                                title="{{ __('Subscribe Now') }}">{{ __('Subscribe Now') }}</button>
                        @endif

                    </div>
                </div>
            </form>
        </div>
{{--        <div class="col-xl-4">--}}
{{--            <form class="ajax" action="{{ route('admin.subscription.get.gateway') }}" method="post"--}}
{{--                enctype="multipart/form-data" data-handler="setPaymentModal">--}}
{{--                @csrf--}}

{{--                <input type="hidden" name="id" value="{{ $package->id }}">--}}
{{--                <input type="hidden" class="plan_type" name="duration_type" value="1">--}}

{{--                <div class="price-plan-one shadow-lg price-plan-mySubscription {{ $package->is_default == ACTIVE ? 'price-plan-popular active' : '' }}">--}}
{{--                    <div class="price-head">--}}
{{--                        <h4 class="title">{{ $package->name }}</h4>--}}
{{--                        <h4 class="plan-price zPrice-plan-monthly">--}}
{{--                            <span>{{ showPrice($package->monthly_price) }}</span>/{{ __('Monthly') }}--}}
{{--                        </h4>--}}
{{--                        <h4 class="plan-price zPrice-plan-yearly">--}}
{{--                            <span>{{ showPrice($package->yearly_price) }}</span>/{{ __('Yearly') }}--}}
{{--                        </h4>--}}
{{--                    </div>--}}
{{--                    <div class="price-body">--}}
{{--                        <ul class="zList-pb-27 mb-50">--}}
{{--                            <li>--}}
{{--                                <div class="d-flex align-items-start g-10">--}}
{{--                                    <div class="flex-shrink-0 d-flex max-w-22">--}}
{{--                                        <img src="{{ asset('assets/images/icon/price-check-icon.svg') }}" />--}}
{{--                                    </div>--}}
{{--                                    <p class="fs-18 fw-400 lh-22 text-para-text">--}}
{{--                                        @if ($package->number_of_client == -1)--}}
{{--                                            {{ __('Add Unlimited Clients') }}--}}
{{--                                        @else--}}
{{--                                            {{ __('Add ' . $package->number_of_client . ' Clients') }}--}}
{{--                                        @endif--}}
{{--                                    </p>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <div class="d-flex align-items-start g-10">--}}
{{--                                    <div class="flex-shrink-0 d-flex max-w-22">--}}
{{--                                        <img src="{{ asset('assets/images/icon/price-check-icon.svg') }}" />--}}
{{--                                    </div>--}}
{{--                                    <p class="fs-18 fw-400 lh-22 text-para-text">--}}
{{--                                        @if ($package->number_of_order == -1)--}}
{{--                                            {{ __('Add Unlimited Orders') }}--}}
{{--                                        @else--}}
{{--                                            {{ __('Add ' . $package->number_of_order . ' Orders') }}--}}
{{--                                        @endif--}}
{{--                                    </p>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            @foreach (json_decode($package->others) ?? [] as $other)--}}
{{--                                <li>--}}
{{--                                    <div class="d-flex align-items-start g-10">--}}
{{--                                        <div class="flex-shrink-0 d-flex max-w-22">--}}
{{--                                            <img src="{{ asset('assets/images/icon/price-check-icon.svg') }}" />--}}
{{--                                        </div>--}}
{{--                                        <p class="fs-18 fw-400 lh-22 text-para-text">--}}
{{--                                            {{ __($other) }} </p>--}}
{{--                                    </div>--}}
{{--                                </li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}
{{--                        @if ($currentPackage?->package_id == $package->id)--}}
{{--                            <button type="submit" class="btn link"--}}
{{--                                title="{{ __('Current Package') }}">{{ __('Current Package') }}</button>--}}
{{--                        @else--}}
{{--                            <button type="submit" class="btn link"--}}
{{--                                title="{{ __('Subscribe Now') }}">{{ __('Subscribe Now') }}</button>--}}
{{--                        @endif--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--        <div class="col-xl-4">--}}
{{--            <form class="ajax" action="{{ route('admin.subscription.get.gateway') }}" method="post"--}}
{{--                enctype="multipart/form-data" data-handler="setPaymentModal">--}}
{{--                @csrf--}}

{{--                <input type="hidden" name="id" value="{{ $package->id }}">--}}
{{--                <input type="hidden" class="plan_type" name="duration_type" value="1">--}}

{{--                <div class="price-plan-one shadow-lg price-plan-mySubscription {{ $package->is_default == ACTIVE ? 'price-plan-popular active' : '' }}">--}}
{{--                    <div class="price-head">--}}
{{--                        <h4 class="title">{{ $package->name }}</h4>--}}
{{--                        <h4 class="plan-price zPrice-plan-monthly">--}}
{{--                            <span>{{ showPrice($package->monthly_price) }}</span>/{{ __('Monthly') }}--}}
{{--                        </h4>--}}
{{--                        <h4 class="plan-price zPrice-plan-yearly">--}}
{{--                            <span>{{ showPrice($package->yearly_price) }}</span>/{{ __('Yearly') }}--}}
{{--                        </h4>--}}
{{--                    </div>--}}
{{--                    <div class="price-body">--}}
{{--                        <ul class="zList-pb-27 mb-50">--}}
{{--                            <li>--}}
{{--                                <div class="d-flex align-items-start g-10">--}}
{{--                                    <div class="flex-shrink-0 d-flex max-w-22">--}}
{{--                                        <img src="{{ asset('assets/images/icon/price-check-icon.svg') }}" />--}}
{{--                                    </div>--}}
{{--                                    <p class="fs-18 fw-400 lh-22 text-para-text">--}}
{{--                                        @if ($package->number_of_client == -1)--}}
{{--                                            {{ __('Add Unlimited Clients') }}--}}
{{--                                        @else--}}
{{--                                            {{ __('Add ' . $package->number_of_client . ' Clients') }}--}}
{{--                                        @endif--}}
{{--                                    </p>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <div class="d-flex align-items-start g-10">--}}
{{--                                    <div class="flex-shrink-0 d-flex max-w-22">--}}
{{--                                        <img src="{{ asset('assets/images/icon/price-check-icon.svg') }}" />--}}
{{--                                    </div>--}}
{{--                                    <p class="fs-18 fw-400 lh-22 text-para-text">--}}
{{--                                        @if ($package->number_of_order == -1)--}}
{{--                                            {{ __('Add Unlimited Orders') }}--}}
{{--                                        @else--}}
{{--                                            {{ __('Add ' . $package->number_of_order . ' Orders') }}--}}
{{--                                        @endif--}}
{{--                                    </p>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            @foreach (json_decode($package->others) ?? [] as $other)--}}
{{--                                <li>--}}
{{--                                    <div class="d-flex align-items-start g-10">--}}
{{--                                        <div class="flex-shrink-0 d-flex max-w-22">--}}
{{--                                            <img src="{{ asset('assets/images/icon/price-check-icon.svg') }}" />--}}
{{--                                        </div>--}}
{{--                                        <p class="fs-18 fw-400 lh-22 text-para-text">--}}
{{--                                            {{ __($other) }} </p>--}}
{{--                                    </div>--}}
{{--                                </li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}
{{--                        @if ($currentPackage?->package_id == $package->id)--}}
{{--                            <button type="submit" class="btn link"--}}
{{--                                title="{{ __('Current Package') }}">{{ __('Current Package') }}</button>--}}
{{--                        @else--}}
{{--                            <button type="submit" class="btn link"--}}
{{--                                title="{{ __('Subscribe Now') }}">{{ __('Subscribe Now') }}</button>--}}
{{--                        @endif--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
    @endforeach
</div>
