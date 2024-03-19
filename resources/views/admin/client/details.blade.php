@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@section('content')
    <!-- Content -->
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <div class="row rg-20">
                <div class="col-xl-4 col-md-5">
                    <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-sm-25 p-15">
                        <!--  -->
                        <div class="w-105 h-105 rounded-circle overflow-hidden"><img
                                src="{{asset(getFileUrl($clientDetails->image))}}" alt=""/></div>
                        <!--  -->
                        <div class="bd-t-one bd-c-stroke pt-22 mt-30">
                            <ul class="zList-pb-16">
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4 class="fs-12 fw-500 lh-19 text-title-black">{{__('Name')}}
                                            :</h4></div>
                                    <div class="col-6"><p
                                            class="fs-12 fw-500 lh-19 text-para-text">{{$clientDetails->name ?? 'N/A'}}</p>
                                    </div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-title-black">{{__('Email Address')}} :</h4>
                                    </div>
                                    <div class="col-6"><p
                                            class="fs-12 fw-500 lh-19 text-para-text">{{$clientDetails->email ?? 'N/A'}}</p>
                                    </div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-title-black">{{__('Phone Number')}} :</h4>
                                    </div>
                                    <div class="col-6">
                                        @if($clientDetails && $clientDetails->mobile)
                                            <p class="fs-12 fw-500 lh-19 text-para-text">{{$clientDetails->mobile}}</p>
                                        @else
                                            <p class="fs-12 fw-500 lh-19 text-para-text">{{__('No Data Found')}}</p>
                                        @endif
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!--  -->

                        <!--  -->

                    </div>
                </div>
                <div class="col-xl-8 col-md-7">
                    <div class="bd-one bd-c-stroke bd-ra-8 bg-white py-sm-25 pt-25">
                        <!--  -->
                        <ul class="nav nav-tabs zTab-reset zTab-two flex-wrap pl-sm-20" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-sm-15 px-13 active bg-transparent orderStatusTab" id="orderHistory-tab"
                                        data-bs-toggle="tab" data-bs-target="#orderHistory-tab-pane" type="button"
                                        role="tab" aria-controls="orderHistory-tab-pane"
                                        aria-selected="true">{{__('Order History')}}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-sm-15 px-13 bg-transparent orderStatusTab" id="invoice-tab"
                                        data-bs-toggle="tab" data-bs-target="#invoice-tab-pane" type="button" role="tab"
                                        aria-controls="invoice-tab-pane"
                                        aria-selected="false">{{__('Invoice')}}</button>
                            </li>
                        </ul>
                        <!--  -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Order History -->
                            <div class="tab-pane fade show active" id="orderHistory-tab-pane" role="tabpanel"
                                 aria-labelledby="orderHistory-tab" tabindex="0">
                                <div class="bd-t-one bd-c-stroke p-sm-30 p-15 pt-25">
                                    <table class="table zTable zTable-last-item-right" id="clientOrderHistoryTable">
                                        <thead>
                                        <tr>
                                            <th>
                                                <div class="text-nowrap">{{__('Order ID')}}</div>
                                            </th>
                                            <th>
                                                <div class="text-nowrap">{{__('Amount')}}</div>
                                            </th>
                                            <th>
                                                <div>{{__('Paid Amount')}}</div>
                                            </th>
                                            <th>
                                                <div>{{__('Working Status')}}</div>
                                            </th>
                                            <th>
                                                <div>{{__('Status')}}</div>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <!-- Invoice -->
                            <div class="tab-pane fade" id="invoice-tab-pane" role="tabpanel"
                                 aria-labelledby="invoice-tab" tabindex="0">
                                <div class="bd-t-one bd-c-stroke p-sm-30 p-15">
                                    <table class="table zTable zTable-last-item-right" id="clientInvoiceHistoryDatatable">
                                        <thead>
                                        <tr>
                                            <th>
                                                <div class="text-nowrap">{{__('Invoice Id')}}</div>
                                            </th>
                                            <th>
                                                <div class="text-nowrap">{{__('Order Id')}}</div>
                                            </th>
                                            <th>
                                                <div class="text-nowrap">{{__('Gateway')}}</div>
                                            </th>

                                            <th>
                                                <div class="text-nowrap">{{__('Amount')}}</div>
                                            </th>
                                            <th>
                                                <div class="text-nowrap">{{__('Date')}}</div>
                                            </th>
                                            <th>
                                                <div class="text-nowrap">{{__('Status')}}</div>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="client-order-history-route" value="{{route('admin.client.details',['id' => $clientDetails->id])}}">
    <input type="hidden" id="client-invoice-history-route" value="{{route('admin.client.invoice',['id' => $clientDetails->id])}}">
@endsection
@push('script')
    <script src="{{ asset('admin/custom/js/client.js') }}"></script>
@endpush

