@extends('user.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="home-section">
            <!--  -->
            <div class="d-flex align-items-center cg-5 pb-26">
                <h4 class="fs-24 fw-600 lh-29 text-title-black">Hey Anderson!</h4>
                <span class="d-flex"><img src="{{asset('assets/images/icon/hand-wave.svg')}}" alt=""/></span>
            </div>
            <!--  -->
            <div class="mb-30 bd-one bd-c-stroke bd-ra-10 p-30 bg-white">
                <div class="count-item-one">
                    <div class="row justify-content-xl-between rg-13">
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                            <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                                <div
                                    class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-purple-10">
                                    <img src="{{asset('assets/images/icon/bag-dollar.svg')}}" alt=""/>
                                </div>
                                <div class="content">
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{__("Payment Pending")}}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black">{{$paymentPending}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                            <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                                <div
                                    class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-main-color-10">
                                    <img src="{{asset('assets/images/icon/user-multiple.svg')}}" alt=""/>
                                </div>
                                <div class="content">
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{__("Open Ticket")}}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black">{{$openTicket}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                            <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                                <div
                                    class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-color2-10">
                                    <img src="{{asset('assets/images/icon/user-multiple-2.svg')}}" alt=""/>
                                </div>
                                <div class="content">
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{__("Completed Ticket")}}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black">{{$completedTicket}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                            <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                                <div
                                    class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-green-10">
                                    <img src="{{asset('assets/images/icon/orders.svg')}}" alt=""/>
                                </div>
                                <div class="content">
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{__("Open Orders")}}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black">{{$openOrders}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-auto">
                            <div class="item d-flex flex-column flex-sm-row cg-13 rg-13">
                                <div
                                    class="icon w-48 h-48 bd-ra-8 flex-shrink-0 d-flex justify-content-center align-items-center bg-color1-10">
                                    <img src="{{asset('assets/images/icon/receipt-check.svg')}}" alt=""/>
                                </div>
                                <div class="content">
                                    <h4 class="fs-15 fw-500 lh-18 text-para-text pb-5">{{__("Complete Orders")}}</h4>
                                    <p class="fs-18 fw-500 lh-21 text-title-black">{{$completedOrders}}</p>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <!--  -->
            <!--  -->
            <div class="row rg-20">
                <div class="col-lg-6">
                    <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white">
                        <!-- Title -->
                        <div class="d-flex justify-content-between align-items-center g-10 pb-20">
                            <h4 class="fs-18 fw-500 lh-22 text-title-black">{{__("Order Summery")}}</h4>
                        </div>
                        <!-- Table -->
                        <table class="table zTable zTable-last-item-right" id="orderSummery">
                            <thead>
                            <tr>
                                <th>
                                    <div>{{__("Order ID")}}</div>
                                </th>
                                <th>
                                    <div>{{__("Working Status")}}</div>
                                </th>
                                <th>
                                    <div>{{__("Payment Status")}}</div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-25 bd-one bd-c-stroke bd-ra-10 bg-white">
                        <!-- Title -->
                        <div class="d-flex justify-content-between align-items-center g-10 pb-20">
                            <h4 class="fs-18 fw-500 lh-22 text-title-black">{{__('Ticket Summery')}}</h4>
                        </div>
                        <!-- Table -->
                        <table class="table zTable zTable-last-item-right" id="ticketSummery">
                            <thead>
                            <tr>
                                <th>
                                    <div>{{__('Ticket Id')}}</div>
                                </th>
                                <th>
                                    <div>{{__('Order ID')}}</div>
                                </th>
                                <th>
                                    <div>{{__('Status')}}</div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="ticket-summery-route" value="{{route('user.dashboard')}}">
    <input type="hidden" id="order-summery-route" value="{{route('user.order-summery')}}">
@endsection

@push('script')
    <script src="{{ asset('user/custom/js/user-dashboard.js') }}"></script>
@endpush

