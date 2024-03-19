@extends('admin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

    <div class="row rg-20">
        <div class="col-md-6">
            @if (!is_null($userPackage))
            <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white h-100">
                <div
                    class="current-plan d-flex justify-content-between align-items-center flex-wrap g-10 bd-b-one bd-c-stroke pb-20 mb-20">
                    <div class="">
                        <p class="fs-14 fw-400 lh-18 text-para-text">{{ __('Current Package') }}</p>
                        <h4 class="fs-18 fw-700 lh-28 text-title-black">
                            {{ $userPackage->name }}
                            <small class="small">/{{ $userPackage->duration_type == DURATION_MONTH ? __('Monthly') :
                                __('Yearly') }}</small>
                        </h4>
                    </div>
                    <button type="button"
                        class="border-0 bd-ra-12 bg-main-color py-13 px-25 fs-16 fw-600 lh-19 text-white"
                        id="chooseAPlan" title="{{ __('Upgrade Plan') }}">{{ __('Upgrade Plan') }}</button>

                </div>

                <div class="">
                    <p class="fs-14 fw-400 lh-18 text-para-text pb-12">{{ __('Usage') }}</p>
                    <ul class="zList-pb-12">
                        <li>
                            <div class="d-flex align-items-center cg-10">
                                <div class="text-title-black">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                                <h4 class="flex-grow-1 fs-18 fw-400 lh-28 text-title-black">
                                    {{ getExistingClients(auth()->id()) }} /
                                    @if ($userPackage->number_of_client == -1)
                                    {{ __('Add Unlimited Clients') }}
                                    @else
                                    {{ __($userPackage->number_of_client . ' Clients') }}
                                    @endif
                                </h4>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center cg-10">
                                <div class="text-title-black">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                                <h4 class="flex-grow-1 fs-18 fw-400 lh-28 text-title-black">
                                    {{ getExistingOrders(auth()->id()) }}/
                                    @if ($userPackage->number_of_order == -1)
                                    {{ __('Unlimited Ordes') }}
                                    @else
                                    {{ __($userPackage->number_of_order . ' Orders') }}
                                    @endif
                                </h4>
                            </div>
                        </li>
                        @foreach (getPackageOtherFields(auth()->id()) ?? [] as $field)
                        <li>
                            <div class="d-flex align-items-center cg-10">
                                <div class="text-title-black">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                                <h4 class="flex-grow-1 fs-18 fw-400 lh-28 text-title-black">
                                    {{ $field }}
                                </h4>
                            </div>
                        </li>
                        @endforeach
                        <li>
                            <div class="d-flex align-items-center cg-10">
                                <div class="text-title-black">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                                <h4 class="flex-grow-1 fs-18 fw-400 lh-28 text-title-black">
                                    <span>
                                        {{ __('Started at ') }}
                                    </span>
                                    {{ $userPackage->start_date }}
                                </h4>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center cg-10">
                                <div class="text-title-black">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </div>
                                <h4 class="flex-grow-1 fs-18 fw-400 lh-28 text-title-black">
                                    <span>{{ __('End in ') }}</span>
                                    {{ $userPackage->end_date }}
                                </h4>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            @else
            <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white h-100">
                <h4 class="fs-18 fw-700 lh-28 text-title-black pb-20">{{ __("Currently you doesn't have any
                    subscription") }}</h4>
                <button type="button" class="border-0 bd-ra-12 bg-main-color py-13 px-25 fs-16 fw-600 lh-19 text-white"
                    id="chooseAPlan" title="{{ __('Choose a plan') }}">{{ __('Choose a plan') }}</button>

            </div>
            @endif
        </div>
        <div class="col-md-6">
            @if (!is_null($userPackage))
            <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white h-100">
                <form action="{{ route('admin.subscription.cancel') }}" method="post">
                    @csrf
                    <button type="button"
                        class="theme-btn-red subscriptionCancel border-0 bg-red bd-ra-10 fs-16 fw-600 lh-19 text-white p-13 mb-20"
                        title="{{ __('Cancel your subscription') }}">{{ __('Cancel your subscription') }}</button>
                </form>
                <p class="fs-16 fw-400 lh-18 text-para-text">
                    {{ __('Please be aware that cancelling your subscription will cause you to lose all your saved
                    content and earned words on your subscription.') }}
                </p>
            </div>
            @endif
        </div>
        <div class="col-lg-6">
            <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                <div class="d-flex justify-content-between align-items-center g-10 pb-20">
                    <h4 class="fs-18 fw-500 lh-22 text-title-black">{{ __('Package History') }}</h4>
                </div>
                <table class="table zTable zTable-last-item-right" id="recentOrderHistoryDashboard">
                    <thead>
                        <tr>
                            <th>
                                <div>{{ __('Package') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Total') }}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{ __('Start Date') }}</div>
                            </th>
                            <th>
                                <div class="text-nowrap">{{ __('End Date') }}</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packageHistories as $package)
                        <tr>
                            <td>{{ $package->packageName }}</td>
                            <td>{{ $package->total }}</td>
                            <td>{{ date('Y-m-d', strtotime($package->start_date)) }}</td>
                            <td>{{ date('Y-m-d', strtotime($package->end_date)) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="4">{{ __('No Data Found') }}</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                <div class="d-flex justify-content-between align-items-center g-10 pb-20">
                    <h4 class="fs-18 fw-500 lh-22 text-title-black">{{ __('Order History') }}</h4>
                </div>
                <table class="table zTable zTable-last-item-right" id="recentOpenTicketHistoryList">
                    <thead>
                        <tr>
                            <th>
                                <div>{{ __('Package') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Total') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Gateway') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Status') }}</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orderHistories as $order)
                        <tr>
                            <td>{{ $order->packageName }}</td>
                            <td>{{ $order->total }}</td>
                            <td>{{ $order->gatewayTitle }}</td>
                            <td>
                                @if ($order->payment_status == PAYMENT_STATUS_PAID)
                                <p class="zBadge zBadge-active">{{ __('Paid') }}</p>
                                @elseif ($order->payment_status == PAYMENT_STATUS_PENDING)
                                <p class="zBadge zBadge-fuilure">{{ __('Unpaid') }}</p>
                                @else
                                <p class="zBadge zBadge-pending">{{ __('Cancelled') }} </p>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center" colspan="4">{{ __('No Data Found') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- Choose a plan Modal Start -->

@if (isAddonInstalled('ENCYSAAS') > 0)
<div class="modal fade" id="choosePackageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bd-c-stroke-color bd-ra-12 py-25 px-20">
            <div class="d-flex justify-content-between align-items-center g-10 pb-20 mb-20 bd-b-one bd-c-stroke">
                <h4 class="fs-18 fw-500 lh-28 text-title-black">
                    {{ __('Choose A Plan') }}</h4>
                <button type="button"
                    class="w-30 h-30 rounded-circle d-flex justify-content-center align-items-center bd-one bd-c-stroke-color bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <div class="d-flex justify-content-center align-items-center g-20 pb-50">
                <h4 class="fs-20 fw-500 lh-26 text-textBlack">{{ __('Monthly') }}</h4>
                <div class="price-plan-tab">
                    <div class="zCheck form-check form-switch zPrice-plan-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="zPrice-plan-switch" />
                    </div>
                </div>
                <h4 class="fs-20 fw-500 lh-26 text-textBlack">{{ __('Yearly') }}</h4>
            </div>

            <div class="row rg-20" id="planListBlock">

            </div>

        </div>
    </div>
</div>
<!-- Choose a plan Modal End -->

<!-- Payment Method Modal Start -->
<div class="modal fade" id="paymentMethodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bd-c-stroke-color bd-ra-12 py-25 px-20">
            <div class="d-flex justify-content-end align-items-center cg-10 pb-24">
                <button type="button"
                    class="w-30 h-30 rounded-circle d-flex justify-content-center align-items-center bd-one bd-c-stroke-color bg-transparent"
                    data-bs-dismiss="modal" aria-label="Close">
                    <img src="{{ asset('assets/images/icon/close.svg') }}" alt="" />
                </button>
            </div>
            <div class="modal-body">
                <!-- Choose a plan content Start -->
                <div class="payment-method-area">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="text-center pb-35">
                                <h4 class="fs-sm-52 fw-700 lh-44 lh-sm-76 text-textBlack">
                                    {{ __('Select Payment Method') }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="payment-method-wrap px-5">
                        <form class="" action="{{ route('payment.subscription.checkout') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="package_id" name="package_id">
                            <input type="hidden" id="selectGateway" name="gateway">
                            <input type="hidden" id="selectCurrency" name="currency">
                            <input type="hidden" id="duration_type" name="duration_type">
                            <div class="row" id="gatewayListBlock">
                            </div>
                            <div class="row">
                                <div class="cg-10 d-flex justify-content-end mt-18">
                                    <button type="button"
                                        class="border-0 bd-ra-12 py-13 px-25 bg-main-color fs-16 fw-600 lh-19 text-white"
                                        id="payBtn">{{ __('Pay Now') }}<span class="ms-1"
                                            id="gatewayCurrencyAmount"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Choose a plan content End -->
            </div>
        </div>
    </div>
</div>
<!-- Payment Method Modal End -->
@endif
@if (!is_null(request()->id))
<input type="hidden" id="requestPlanId" value="{{ request()->id }}">
<input type="hidden" id="gatewayResponse" value="{{ $gateways }}">
@endif
<input type="hidden" id="requestCurrentPlan" value="{{ request()->current_plan }}">
<input type="hidden" id="chooseAPlanRoute" value="{{ route('admin.subscription.get.package') }}">
<input type="hidden" id="getCurrencyByGatewayRoute" value="{{ route('admin.subscription.get.currency') }}">
@endsection

@push('script')
<script src="{{ asset('user/custom/js/subscription-admin.js') }}"></script>
@endpush