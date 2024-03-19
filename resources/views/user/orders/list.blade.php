@extends('user.layouts.app')
@push('title')
{{$pageTitle}}
@endpush
@section('content')
<!-- Content -->
@if($orderCount > 0)
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <!-- Tab - Create -->
        <div
            class="d-flex flex-column-reverse flex-md-row justify-content-center justify-content-md-between align-items-center align-items-md-start flex-wrap g-10 table-pl">
            <!-- Left -->
            <ul class="nav nav-tabs zTab-reset zTab-two flex-wrap pl-sm-20" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active bg-transparent orderStatusTab" id="allOrder-tab" data-bs-toggle="tab"
                            data-bs-target="#allOrder-tab-pane" type="button" role="tab" aria-controls="allOrder-tab-pane"
                            aria-selected="true" data-status="all">{{__("All")}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link bg-transparent orderStatusTab" id="pendingOrder-tab" data-bs-toggle="tab"
                            data-bs-target="#workingOrder-tab-pane" type="button" role="tab"
                            aria-controls="workingOrder-tab-pane" aria-selected="false" data-status="{{ORDER_PAYMENT_STATUS_PENDING}}">{{__("Working")}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link bg-transparent orderStatusTab" id="completedOrder-tab" data-bs-toggle="tab"
                            data-bs-target="#completedOrder-tab-pane" type="button" role="tab"
                            aria-controls="completedOrder-tab-pane" aria-selected="false" data-status="{{ORDER_PAYMENT_STATUS_PAID}}">{{__("Completed")}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link bg-transparent orderStatusTab" id="cancelledOrder-tab" data-bs-toggle="tab"
                            data-bs-target="#cancelledOrder-tab-pane" type="button" role="tab"
                            aria-controls="cancelledOrder-tab-pane" aria-selected="false" data-status="{{ORDER_PAYMENT_STATUS_CANCELLED}}">{{__("Cancelled")}}</button>
                </li>
            </ul>

        </div>
        <!--  -->
        <div class="tab-content" id="myTabContent">
            <!-- All Order -->
            <div class="tab-pane fade show active" id="allOrder-tab-pane" role="tabpanel" aria-labelledby="allOrder-tab"
                 tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="orderTable-all">
                        <thead>
                        <tr>
                            <th>
                                <div class="text-nowrap">{{__('Order ID')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Service Name')}}</div>
                            </th>
                            <th>
                                <div>{{__('Price')}}</div>
                            </th>
                            <th>
                                <div>{{__('Working Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Payment Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Created')}}</div>
                            </th>
                            <th>
                                <div>{{__('Action')}}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- Working Order -->
            <div class="tab-pane fade" id="workingOrder-tab-pane" role="tabpanel" aria-labelledby="workingOrder-tab"
                 tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="orderTable-{{ORDER_PAYMENT_STATUS_PENDING}}">
                        <thead>
                        <tr>
                            <th>
                                <div class="text-nowrap">{{__('Order ID')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Service Name')}}</div>
                            </th>
                            <th>
                                <div>{{__('Price')}}</div>
                            </th>
                            <th>
                                <div>{{__('Working Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Payment Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Created')}}</div>
                            </th>
                            <th>
                                <div>{{__('Action')}}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- Completed Order -->
            <!-- Cancelled Order -->
            <div class="tab-pane fade" id="completedOrder-tab-pane" role="tabpanel" aria-labelledby="completedOrder-tab"
                 tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="orderTable-{{ORDER_PAYMENT_STATUS_PAID}}">
                        <thead>
                        <tr>
                            <th>
                                <div class="text-nowrap">{{__('Order ID')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Service Name')}}</div>
                            </th>
                            <th>
                                <div>{{__('Price')}}</div>
                            </th>
                            <th>
                                <div>{{__('Working Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Payment Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Created')}}</div>
                            </th>
                            <th>
                                <div>{{__('Action')}}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- Cancelled Order -->
            <div class="tab-pane fade" id="cancelledOrder-tab-pane" role="tabpanel" aria-labelledby="cancelledOrder-tab"
                 tabindex="0">
                <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
                    <table class="table zTable zTable-last-item-right" id="orderTable-{{ORDER_PAYMENT_STATUS_CANCELLED}}">
                        <thead>
                        <tr>
                            <th>
                                <div class="text-nowrap">{{__('Order ID')}}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{__('Service Name')}}</div>
                            </th>
                            <th>
                                <div>{{__('Price')}}</div>
                            </th>
                            <th>
                                <div>{{__('Working Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Payment Status')}}</div>
                            </th>
                            <th>
                                <div>{{__('Created')}}</div>
                            </th>
                            <th>
                                <div>{{__('Action')}}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="client-order-list-route" value="{{ route('user.orders.list') }}">
@else
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="p-sm-30 p-15 bg-white bd-one bd-c-stroke bd-ra-10">
            <div class="create-wrap">
                <div class="mb-22"><img src="{{ asset('assets/images/create-icon.png') }}" alt=""/></div>
                <h4 class="pb-22 fs-24 fw-500 lh-30 text-title-black text-center">{{__("There is no order available here!")}}</h4>
            </div>
        </div>
    </div>
@endif

@endsection

@push('script')
<script src="{{ asset('user/custom/js/client-orders.js') }}"></script>
@endpush
