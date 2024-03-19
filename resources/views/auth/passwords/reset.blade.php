@extends('auth.layouts.app')
@push('title')
    {{ __('Reset Password') }}
@endpush
@section('content')
    <div class="signLog-section">
        <div class="left">
            <div class="wrap">
                <div class="zMain-signLog-content">
                    <a href="{{ route('frontend') }}" class="d-inline-flex mb-30">
                        <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" />
                    </a>
                    <h4 class="fs-32 fw-600 lh-48 text-title-black pb-24">{{ __('Forgot Password') }}?</h4>
                    <form method="POST" action="{{ route('password.update', $token) }}">
                        @csrf
                        <div class="pb-20">
                            <label for="inputEmailAddress" class="zForm-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control zForm-control" id="inputEmailAddress"
                                value="{{ $email }}" name="email" placeholder="{{ __('Enter email address') }}"
                                readonly />
                            @error('email')
                                <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="pb-20">
                            <label for="inputNewPassword" class="zForm-label">{{ __('New Password') }}</label>
                            <input type="password" class="form-control zForm-control" id="inputNewPassword"
                                placeholder="{{ __('Enter new password') }}" value="{{ old('password') }}"
                                name="password" />
                            @error('password')
                                <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="pb-30">
                            <label for="inputConfirmPassword" class="zForm-label">{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control zForm-control" id="inputConfirmPassword"
                                placeholder="{{ __('Enter confirm password') }}" name="password_confirmation" />
                        </div>
                        <button type="submit"
                            class="d-flex justify-content-center align-items-center w-100 p-15 bd-ra-10 bg-main-color fs-14 fw-500 lh-20 text-white">{{ __('Update') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="right" data-background="{{ getSettingImage('login_right_image') }}"></div>
    </div>
@endsection
