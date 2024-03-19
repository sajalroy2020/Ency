<!-- Start Header -->
<header class="landing-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-2 col-6">
                <div class="">
                    <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}"/>
                </div>
            </div>
            <div class="col-lg-8 col-6">
                <nav class="navbar navbar-expand-lg p-0">
                    <button class="navbar-toggler bd-c-main-color text-main-color fs-30 ms-auto" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
                            aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="navbar-collapse landing-menu-navbar-collapse offcanvas offcanvas-start" tabindex="-1"
                         id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                        <button type="button"
                                class="d-lg-none w-30 h-30 p-0 rounded-circle bg-white border-0 position-absolute top-10 right-10"
                                data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-solid fa-times"></i>
                        </button>
                        <ul class="navbar-nav landing-menu-navbar-nav justify-content-md-center flex-wrap w-100">
                            <li class="nav-item"><a class="nav-link" href="#">{{ __('Home') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="#features">{{ __('Features') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="#price">{{ __('Pricing') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="#faq">{{ __('FAQ\'s') }}</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="col-lg-2 d-none d-lg-block">
                <div class="text-end">
                    @if (auth()->check())
                        @if (auth()->user()->role == USER_ROLE_SUPER_ADMIN)
                            <a href="{{ route('super-admin.dashboard') }}"
                               class="py-17 px-33 bd-ra-48 d-inline-flex bg-title-black fs-18 fw-600 lh-22 text-white">{{ __('Dashboard') }}</a>
                        @elseif(auth()->user()->role == USER_ROLE_CLIENT)
                            <a href="{{ route('user.dashboard') }}"
                               class="py-17 px-33 bd-ra-48 d-inline-flex bg-title-black fs-18 fw-600 lh-22 text-white">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('admin.dashboard') }}"
                               class="py-17 px-33 bd-ra-48 d-inline-flex bg-title-black fs-18 fw-600 lh-22 text-white">{{ __('Dashboard') }}</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="py-17 px-33 bd-ra-48 d-inline-flex bg-title-black fs-18 fw-600 lh-22 text-white">{{ __('Login') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>
<!-- End Header -->
