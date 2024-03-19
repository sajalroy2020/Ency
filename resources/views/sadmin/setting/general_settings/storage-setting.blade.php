@extends('sadmin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <div class="">
        <h4 class="fs-24 fw-500 lh-34 text-title-black pb-16">{{ __($title) }}</h4>
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    @include('sadmin.setting.partials.general-sidebar')
                </div>
            </div>
            <div class="col-xl-9">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    <h4 class="fs-18 fw-600 lh-22 text-title-black pb-25">{{ $title }}</h4>
                    <div class="">
                        <h5 class="fs-16 fw-600 lh-22 text-title-black pb-10">{{ __('Instructions') }}: </h5>
                        <p class="fs-12 fw-400 lh-22 text-para-text pb-10">{{ __('You need to click on') }}
                            <strong>{{ __(' "Storage Link"') }}</strong> {{ __(' button, after change ') }}
                            <strong>{{ __('"Storage Driver"') }}</strong>
                        </p>
                        <div class="text-black mt-3">
                            <a href="{{ route('super-admin.setting.storage.link') }}"
                                class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white d-inline-block">
                                {{ __('Storage Link') }}</a>
                        </div>
                    </div>
                    <form class="ajax" action="{{ route('super-admin.setting.storage.update') }}" method="POST"
                        enctype="multipart/form-data" data-handler="settingCommonHandler">
                        @csrf
                        <div class="d-flex flex-column rg-20">
                            <div class="form-group text-black row mb-3">
                                <div class="primary-form-group my-2 pt-3">
                                    <div class="primary-form-group-wrap">
                                        <label for="STORAGE_DRIVER" class="form-label">{{ __('Storage Driver')
                                            }}</label>
                                        <select name="STORAGE_DRIVER" id="storage_driver"
                                            class="form-control sf-select-without-search" required>
                                            <option value="{{ STORAGE_DRIVER_PUBLIC }}" {{
                                                env('STORAGE_DRIVER')==STORAGE_DRIVER_PUBLIC ? 'selected' : '' }}>
                                                {{ __('Public') }}</option>
                                            <option value="{{ STORAGE_DRIVER_AWS }}" {{
                                                env('STORAGE_DRIVER')==STORAGE_DRIVER_AWS ? 'selected' : '' }}>
                                                {{ __('AWS') }}</option>
                                            <option value="{{ STORAGE_DRIVER_WASABI }}" {{
                                                env('STORAGE_DRIVER')==STORAGE_DRIVER_WASABI ? 'selected' : '' }}>
                                                {{ __('Wasabi') }}</option>
                                            <option value="{{ STORAGE_DRIVER_VULTR }}" {{
                                                env('STORAGE_DRIVER')==STORAGE_DRIVER_VULTR ? 'selected' : '' }}>
                                                {{ __('Vultr') }}</option>
                                            <option value="{{ STORAGE_DRIVER_DO }}" {{
                                                env('STORAGE_DRIVER')==STORAGE_DRIVER_DO ? 'selected' : '' }}>
                                                {{ __('Digital Ocean (DO)') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-none storage-driver" id="aws">
                                <div class="row rg-20">
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('AWS Access Key ID') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="AWS_ACCESS_KEY_ID"
                                            value="{{ env('AWS_ACCESS_KEY_ID') }}" class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('AWS Secret Access Key') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="AWS_SECRET_ACCESS_KEY"
                                            value="{{ env('AWS_SECRET_ACCESS_KEY') }}"
                                            class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('AWS Default Region') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="AWS_DEFAULT_REGION"
                                            value="{{ env('AWS_DEFAULT_REGION') }}" class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('AWS Bucket') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="AWS_BUCKET" value="{{ env('AWS_BUCKET') }}"
                                            class="form-control zForm-control">
                                    </div>
                                </div>
                            </div>
                            <div class="d-none storage-driver" id="wasabi">
                                <div class="row rg-20">
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('WAS Access Key ID') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="WASABI_ACCESS_KEY_ID"
                                            value="{{ env('WASABI_ACCESS_KEY_ID') }}"
                                            class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('WAS Secret Access Key') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="WASABI_SECRET_ACCESS_KEY"
                                            value="{{ env('WASABI_SECRET_ACCESS_KEY') }}"
                                            class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('WAS Default Region') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="WASABI_DEFAULT_REGION"
                                            value="{{ env('WASABI_DEFAULT_REGION') }}"
                                            class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('WAS Bucket') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="WASABI_BUCKET" value="{{ env('WASABI_BUCKET') }}"
                                            class="form-control zForm-control">
                                    </div>
                                </div>
                            </div>
                            <div class="d-none storage-driver" id="vultr">
                                <div class="row rg-20">
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('VULTR Access Key') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="VULTR_ACCESS_KEY_ID"
                                            value="{{ env('VULTR_ACCESS_KEY') }}" class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('VULTR Secret Key') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="VULTR_SECRET_ACCESS_KEY"
                                            value="{{ env('VULTR_SECRET_KEY') }}" class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('VULTR Region') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="VULTR_DEFAULT_REGION" value="{{ env('VULTR_REGION') }}"
                                            class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('VULTR Bucket') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="VULTR_BUCKET" value="{{ env('VULTR_BUCKET') }}"
                                            class="form-control zForm-control">
                                    </div>
                                </div>
                            </div>

                            <div class="d-none storage-driver" id="do">
                                <div class="row rg-20">
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('DO Access Key ID') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="DO_ACCESS_KEY_ID" value="{{ env('DO_ACCESS_KEY_ID') }}"
                                            class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('DO Secret Access Key') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="DO_SECRET_ACCESS_KEY"
                                            value="{{ env('DO_SECRET_ACCESS_KEY') }}"
                                            class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('DO Default Region') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="DO_DEFAULT_REGION"
                                            value="{{ env('DO_DEFAULT_REGION') }}" class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('DO Bucket') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="DO_BUCKET" value="{{ env('DO_BUCKET') }}"
                                            class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('DO Folder') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="DO_FOLDER" value="{{ env('DO_FOLDER') }}"
                                            class="form-control zForm-control">
                                    </div>
                                    <div class="col-xxl-4 col-lg-6">
                                        <label class="zForm-label">{{ __('DO CDN ID') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="DO_CDN_ID" value="{{ env('DO_CDN_ID') }}"
                                            class="form-control zForm-control">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex g-12 flex-wrap">
                                <button type="submit"
                                    class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{
                                    __('Update') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{ asset('sadmin/js/storage-settings.js') }}"></script>
@endpush