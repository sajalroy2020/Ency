@extends('auth.layouts.app')

@push('title')
{{ __('Registration') }}
@endpush

@section('content')
    <div class="signLog-section">
        <div class="left">
            <div class="wrap">
                <div class="zMain-signLog-content">
                    <a href="{{ route('frontend') }}" class="d-flex max-w-167 mb-30">
                        <img src="{{ getSettingImage('app_logo') }}" class="w-100" alt="{{ getOption('app_name') }}" />
                    </a>
                    <div class="pb-30">
                        <h4 class="fs-32 fw-600 lh-48 text-title-black pb-5">{{ __('Sign up') }}</h4>
                        <p class="fs-14 fw-400 lh-22 text-para-text">{{ __('Already have an account') }}? <a
                                href="{{ route('login') }}" class="fw-500 text-main-color text-decoration-underline">{{
                            __('Sign In') }}</a></p>
                    </div>
                    <form action="{{ route('register') }}" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="pb-20">
                            <label for="inputFullName" class="zForm-label">{{ __('Full Name') }}</label>
                            <input type="text" class="form-control zForm-control" id="inputFullName" name="name"
                                   placeholder="{{ __('Enter full name') }}" value="{{ old('name') }}" />
                            @error('name')
                            <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="pb-20">
                            <label for="inputEmailAddress" class="zForm-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control zForm-control" id="inputEmailAddress"
                                   value="{{ old('email') }}" name="email" placeholder="{{ __('Enter email address') }}" />
                            @error('email')
                            <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="pb-30">
                            <label for="inputPassword" class="zForm-label">{{ __('Password') }}</label>
                            <div class="passShowHide">
                                <input type="password" class="form-control zForm-control passShowHideInput"
                                       id="inputPassword" placeholder="{{ __('Enter your password') }}" name="password" />
                                <button type="button" toggle=".passShowHideInput"
                                        class="toggle-password fa-solid fa-eye"></button>
                                @error('password')
                                <span class="fs-12 text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit"
                                class="border-0 d-flex justify-content-center align-items-center w-100 p-15 bd-ra-10 bg-main-color fs-14 fw-500 lh-20 text-white">{{
                        __('Sign Up') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="right" data-background="{{ getSettingImage('login_right_image') }}"></div>
    </div>
@endsection
