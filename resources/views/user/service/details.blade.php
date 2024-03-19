@extends('user.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="">
        <!--  -->
        <div
            class="py-19 px-sm-30 px-15 bd-b-one bd-c-stroke d-flex justify-content-center justify-content-md-between align-items-center flex-wrap g-10">
            <!-- Right -->
            <h4 class="fs-18 fw-600 lh-22 text-title-black text-center">{{$serviceDetails->service_name}}</h4>
            <!-- Left -->
            <form class="ajax" action="{{ route('user.gateway.list') }}" method="post"
                  enctype="multipart/form-data" data-handler="setPaymentModal">
                @csrf
                <input type="hidden" name="id" value="{{ $serviceDetails->id }}">
                <input type="hidden" name="type" value="service">
                <button
                    class="bd-one bd-c-main-color bd-ra-8 py-10 px-26 bg-main-color d-flex align-items-center cg-12 fs-15 fw-600 lh-25 text-white">{{__("Buy Now")}}</button>
            </form>
        </div>
        <!--  -->
        <div class="p-sm-30 p-15">
            <div class="row rg-20">
                <div class="col-lg-5 col-md-6">
                    <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-25 p-15">
                        <div class="mb-25 bd-ra-8 overflow-hidden"><img src="{{getFileUrl($serviceDetails->image)}}"
                                                                        class="w-100" alt=""/></div>
                        <ul class="zList-pb-18">
                            <li class="d-flex justify-content-between align-items-center flex-wrap g-10">
                                <!-- Left -->
                                <div class="d-flex align-items-center g-10">
                                    <div class="d-flex"><img src="{{ asset('assets/images/icon/date.svg') }}" alt=""/>
                                    </div>
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text">{{__("Date")}}</h4>
                                </div>
                                <!-- Right -->
                                <p class="fs-15 fw-500 lh-18 text-title-black">{{date('d/m/Y', strtotime($serviceDetails->created_at))}}</p>
                            </li>
                            <li class="d-flex justify-content-between align-items-center flex-wrap g-10">
                                <!-- Left -->
                                <div class="d-flex align-items-center g-10">
                                    <div class="d-flex"><img src="{{ asset('assets/images/icon/dollar-bag.svg') }}"
                                                             alt=""/></div>
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text">{{__("Types")}}</h4>
                                </div>
                                <!-- Right -->
                                <p class="fs-15 fw-500 lh-18 text-title-black">{{$serviceDetails->payment_type == PAYMENT_TYPE_ONETIME? __("One - Time Payment"):__("Recurring Payment")}}</p>
                            </li>
                            <li class="d-flex justify-content-between align-items-center flex-wrap g-10">
                                <!-- Left -->
                                <div class="d-flex align-items-center g-10">
                                    <div class="d-flex"><img src="{{ asset('assets/images/icon/dollar-circle.svg') }}"
                                                             alt=""/></div>
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text">{{__("Price")}}</h4>
                                </div>
                                <!-- Right -->
                                <p class="fs-15 fw-500 lh-18 text-title-black">{{currentCurrency('symbol')}}{{$serviceDetails->price}}</p>
                            </li>
                            @if($serviceDetails->deadline != null)
                                <li class="d-flex justify-content-between align-items-center flex-wrap g-10">
                                    <!-- Left -->
                                    <div class="d-flex align-items-center g-10">
                                        <div class="d-flex"><img src="{{ asset('assets/images/icon/stopwatch.svg') }}"
                                                                 alt=""/></div>
                                        <h4 class="fs-15 fw-500 lh-18 text-para-text">{{__("Duration")}}</h4>
                                    </div>
                                    <!-- Right -->
                                    <p class="fs-15 fw-500 lh-18 text-title-black">{{ \Illuminate\Support\Carbon::createFromTimeStamp(strtotime($serviceDetails->deadline))->diffInDays(now())}}</p>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-7 col-md-6">
                    <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-25 p-15">
                        <h4 class="fs-18 fw-500 lh-22 text-title-black pb-12">{{__("Details")}}</h4>
                        {{$serviceDetails->service_description}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Buy Modal -->
    <div class="modal fade" id="buyNowModal" tabindex="-1" aria-labelledby="buyNowModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-25">
                <!--  -->
                <div class="d-flex justify-content-between align-items-center g-10 flex-wrap pb-20">
                    <h4 class="fs-18 fw-500 lh-22 text-title-black">{{__("Select Payment Method")}}</h4>
                    <button type="button"
                            class="w-30 h-30 bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center p-0 bg-white"
                            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <!--  -->
                <form class="ajax" action="{{route('user.checkout.order.place')}}" method="POST" data-handler="checkoutOrderResponse">
                    @csrf
                    <input type="hidden" id="checkoutType" name="checkout_type" value="{{ CHECKOUT_TYPE_USER_SERVICE }}">
                    <input type="hidden" id="selectGateway" name="gateway">
                    <input type="hidden" id="selectedGatewayId" value="0" name="gateway_id">
                    <input type="hidden" id="currencyId" value="0" name="currency">
                    <input type="hidden" id="coupon" name="coupon">
                    <input type="hidden" id="itemId" name="item_id">
                    <span id="gatewayListBlock"></span>
                    <!--  -->
                    <button type="submit"
                            class="w-75 m-auto p-12 d-flex justify-content-center align-items-center border-0 bd-ra-8 bg-main-color fs-15 fw-600 lh-20 text-white">
                        {{__("Pay Now")}} <span id="orderPlaceSubmitBtnAmountBlock" class="d-none"> {{" ("}}{{currentCurrency('symbol')}}<span id="orderPlaceSubmitBtnAmount"></span>{{")"}}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="gotoRoute" value="{{ route('user.services.list') }}">
@endsection

@push('script')
    <script src="{{ asset('user/custom/js/checkout.js') }}"></script>
@endpush

