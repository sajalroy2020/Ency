@extends('auth.layouts.app')

@push('title')
    {{ __('Login') }}
@endpush

@section('content')
    <div class="signLog-section">
        <div class="left" data-aos="fade-right" data-aos-duration="1000">
            <div class="wrap">
                <div class="zMain-signLog-content">
                    <a href="{{ route('frontend') }}" class="d-flex max-w-167 mb-30">
                        <img src="{{ getSettingImage('app_logo') }}" class="w-100" alt="{{ getOption('app_name') }}" />
                    </a>
                    @if (isAddonInstalled('ENCYSAAS') > 0)
                        @if (getOption('registration_status', 0) == ACTIVE)
                            <div class="pb-30">
                                <h4 class="fs-32 fw-600 lh-48 text-title-black pb-5">{{ __('Sign in') }}</h4>
                                <p class="fs-14 fw-400 lh-22 text-para-text">{{ __('Don’t have an account?') }} <a
                                        href="{{ route('register') }}"
                                        class="fw-500 text-main-color text-decoration-underline">{{ __('Sign Up') }}</a></p>
                            </div>
                        @endif
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="pb-20">
                            <label for="inputPhoneEmail" class="zForm-label">{{ __('Email') }}</label>
                            <input type="email" name="email" class="form-control zForm-control" id="inputPhoneEmail"
                                placeholder="{{ __('Enter email address') }}" />
                            @error('email')
                                <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="pb-14">
                            <label for="inputPassword" class="zForm-label">{{ __('Password') }}</label>
                            <div class="passShowHide">
                                <input type="password" name="password" class="form-control zForm-control passShowHideInput"
                                    id="inputPassword" placeholder="{{ __('Enter your password') }}" />
                                <button type="button" toggle=".passShowHideInput"
                                    class="toggle-password fa-solid fa-eye"></button>
                                @error('password')
                                    <span class="fs-12 text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="pb-30 d-flex justify-content-between align-items-center flex-wrap g-10">
                            <div class="zForm-wrap-checkbox">
                                <input type="checkbox" class="form-check-input" id="authRemember" name="remember"
                                    value="1" />
                                <label for="authRemember">{{ __('Remember me') }}</label>
                            </div>
                            <a href="{{ route('password.request') }}"
                                class="fs-14 fw-400 lh-22 text-main-color">{{ __('Forgot Password?') }}</a>
                        </div>
                        <button type="submit"
                            class="border-0 d-flex justify-content-center align-items-center w-100 p-15 bd-ra-10 bg-main-color fs-14 fw-500 lh-20 text-white">{{ __('Log in') }}</button>
                    </form>
                    @if (env('LOGIN_HELP') == 'active')
                        <div class="row pt-12 fs-14">
                            <div class="col-md-12 mb-25">
                                <div class="table-responsive login-info-table mt-3">
                                    <table class="table table-bordered">
                                        <tbody>
                                            @if (isAddonInstalled('ENCYSAAS') > 0)
                                                <tr>
                                                    <td colspan="2" id="sadminCredentialShow" class="login-info">
                                                        <b>{{ __('Super Admin') }} :</b> {{ __('sadmin@gmail.com') }} |
                                                        123456
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td colspan="2" id="adminCredentialShow" class="login-info">
                                                    <b>{{ __('Admin ') }}:</b> {{ __('admin@gmail.com') }} | 123456
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" id="tmemberCredentialShow" class="login-info">
                                                    <b>{{ __('Team Member ') }}:</b> {{ __('team-member@gmail.com') }} |
                                                    123456
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" id="clientCredentialShow" class="login-info">
                                                    <b>{{ __('Client ') }}:</b> {{ __('client@gmail.com') }} | 123456
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="right" data-background="{{ getSettingImage('login_right_image') }}" data-aos="fade-left"
            data-aos-duration="1000"></div>
    </div>
@endsection

@push('script')
    <script>
        "use strict"
        $('#sadminCredentialShow').on('click', function() {
            $('#inputPhoneEmail').val('sadmin@gmail.com');
            $('#inputPassword').val('123456');
        });
        $('#adminCredentialShow').on('click', function() {
            $('#inputPhoneEmail').val('admin@gmail.com');
            $('#inputPassword').val('123456');
        });
        $('#tmemberCredentialShow').on('click', function() {
            $('#inputPhoneEmail').val('team-member@gmail.com');
            $('#inputPassword').val('123456');
        });
        $('#clientCredentialShow').on('click', function() {
            $('#inputPhoneEmail').val('client@gmail.com');
            $('#inputPassword').val('123456');
        });
    </script>
@endpush
