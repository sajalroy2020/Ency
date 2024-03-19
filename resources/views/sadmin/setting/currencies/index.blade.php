@extends('sadmin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <div class="">
        <h4 class="fs-24 fw-500 lh-34 text-title-black pb-16">{{ __($title) }}</h4>

        <input type="hidden" id="currency-route" value="{{ route('super-admin.setting.currencies.index') }}">

        <!-- Search - Add -->
        <div
            class="d-flex flex-column-reverse flex-sm-row justify-content-center justify-content-md-between align-items-center flex-wrap g-10 pb-18">
            <div class="flex-grow-1">
                <div class="search-one flex-grow-1 max-w-282">
                    <input type="text" id="superAdminCurrencySearch" placeholder="Search here..." />
                    <button class="icon"><img src="{{asset('assets/images/icon/search.svg')}}" alt="" /></button>
                </div>
            </div>
            <!--  -->
            <button class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white" type="button"
                data-bs-toggle="modal" data-bs-target="#add-modal">
                {{ __('+ Add Currency') }}
            </button>
        </div>

        <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
            <table class="table zTable zTable-last-item-right" id="commonDataTable">
                <thead>
                    <tr>
                        <th>
                            <div>{{ __('#SL') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Code') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Symbol') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Placemnent') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Action') }}</div>
                        </th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>
<!-- Page content area end -->
<!-- Add Modal section start -->
<div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">
            <div
                class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                <h2 class="fs-18 fw-600 lh-22 text-title-black">{{ __('Add Currency') }}</h2>
                <div class="mClose">
                    <button type="button"
                        class="bd-one bd-c-stroke rounded-circle w-24 h-24 bg-transparent text-para-text fs-13"
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </div>
            <form class="ajax reset" action="{{ route('super-admin.setting.currencies.store') }}" method="post"
                data-handler="settingCommonHandler">
                @csrf

                <div class="row rg-20">
                    <div class="">
                        <label for="currency_code" class="zForm-label">{{ __('Currency ISO Code') }}
                            <span class="text-danger">*</span></label>
                        <select id="sf-select-currency-add" class="primary-form-control" id="currency_code"
                            name="currency_code">
                            @foreach (getCurrency() as $code => $currencyItem)
                            <option value="{{ $code }}">{{ $currencyItem }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="">
                        <label for="symbol" class="zForm-label">{{ __('Symbol') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="symbol" id="symbol" placeholder="{{ __('Type Symbol') }}"
                            class="form-control zForm-control">
                    </div>
                    <div class="">
                        <label for="currency_placement" class="zForm-label">{{ __('Currency Placement') }}
                            <span class="text-danger">*</span></label>
                        <select class="sf-select-without-search" id="eventType" name="currency_placement">
                            <option value="">--{{ __('Select Option') }}--</option>
                            <option value="before">{{ __('Before Amount') }}</option>
                            <option value="after">{{ __('After Amount') }}</option>
                        </select>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="d-flex form-check ps-0">
                            <div class="zCheck form-check form-switch">
                                <input class="form-check-input mt-0" value="1" name="current_currency" type="checkbox"
                                    id="flexCheckChecked">
                            </div>
                            <label class="form-check-label ps-3 d-flex" for="flexCheckChecked">
                                {{ __('Current Currency') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex g-12 flex-wrap mt-25">
                    <button
                        class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white"
                        type="submit">{{
                        __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Modal section end -->
<!-- Edit Modal section start -->
<div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-sm-25 p-15">

        </div>
    </div>
</div>
<!-- Edit Modal section end -->
@endsection
@push('script')
<script src="{{ asset('sadmin/js/currencies.js') }}"></script>
@endpush