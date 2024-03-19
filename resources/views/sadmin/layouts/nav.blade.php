<div data-aos="fade-down" data-aos-duration="1000" class="main-header">
    <!-- Left -->
    <div class="d-flex align-items-center cg-15">
        <div class="mobileMenu">
            <button
                class="bd-one bd-c-title-color rounded-circle w-30 h-30 d-flex justify-content-center align-items-center text-title-color p-0 bg-transparent">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <!-- Breadcrumb -->
        <ol class="breadcrumb sf-breadcrumb">
            @if(@$isDashboard != true)
            <li class="breadcrumb-item"><a href="{{route('super-admin.dashboard')}}">{{__('Dashboard')}}</a></li>
            @endif
            @if(@$pageTitleParent != null)
            <li class="breadcrumb-item "><a href="{{url()->previous()}}">{{@$pageTitleParent}}</a></li>
            @endif
            <li class="breadcrumb-item active">{{@$title}}</li>
        </ol>
    </div>
    <!-- Right -->
    <div class="right d-flex justify-content-end align-items-center cg-12">
        <!-- Language - Message - Notify -->
        <div class="d-flex align-items-center cg-12">
            <!-- Language -->
            @if (!empty(getOption('show_language_switcher')) && getOption('show_language_switcher') == STATUS_ACTIVE)
            <div class="dropdown lanDropdown">
                <button class="dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center cg-8" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">

                    <img src="{{ asset(selectedLanguage()?->flag) }}" alt="" />

                </button>
                <ul class="dropdown-menu dropdown-menu-end dropdownItem-one">
                    @foreach (appLanguages() as $app_lang)
                    <li>
                        <a class="d-flex align-items-center cg-8" href="{{ url('/local/' . $app_lang->iso_code) }}">
                            <div class="d-flex rounded-circle overflow-hidden flex-shrink-0">
                                <img src="{{ asset($app_lang->flag) }}" alt="" class="max-w-26 w-100" />
                            </div>
                            <p class="fs-13 fw-500 lh-16 text-title-black">{{ $app_lang->language }}</p>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Notify - Message -->
            <div class="d-flex align-items-center cg-12">
                <!-- Notify -->
                <div class="dropdown notifyDropdown">
                    <button
                        class="p-0 w-41 h-41 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center dropdown-toggle"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{asset('assets')}}/images/icon/bell.svg" alt="" />
                        <span class="notify_no">{{count(userNotification('unseen'))}}</span>
                    </button>
                    <div class="dropdown-menu">
                        <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-stroke pb-8 mb-8">
                            <h4 class="fs-15 fw-600 lh-32 text-title-black">
                                @if (count(userNotification('seen-unseen')) > 0)
                                {{ __('Today') }}
                                @else
                                {{ __('Notification Not Found!') }}
                                @endif
                            </h4>
                            @if (count(userNotification('unseen')) > 0)
                            <a href="{{ route('super-admin.notification.notification-mark-all-as-read') }}"
                                class="fs-12 fw-600 lh-20 text-1b1c17 text-decoration-underline border-0 p-0 bg-transparent hover-color-one">{{__('Mark
                                all as read')}}</a>
                            @endif
                        </div>
                        <ul class="notify-list">
                            @foreach (userNotification('seen-unseen') as $key => $item)
                            <li class="d-flex align-items-start cg-15">
                                <div
                                    class="flex-grow-0 flex-shrink-0 w-32 h-32 rounded-circle d-flex justify-content-center align-items-center bg-main-color">
                                    <img src="{{asset('assets/images/icon/bell-white.svg')}}" alt="" />
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center pb-8">
                                        @if($item->seen_id == null)
                                        <p class="fs-13 fw-500 lh-20 text-title-black fw-700">{{$item->title}}</p>
                                        @else
                                        <p class="fs-13 fw-500 lh-20 text-title-black">{{$item->title}}</p>
                                        @endif
                                        <p class="fs-10 fw-400 lh-20 text-707070">
                                            {{ $item->created_at?->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if($item->seen_id == null)
                                    <p class="fs-12 fw-400 lh-17 text-para-text max-w-220 fw-700">{!!
                                        substr($item->body, 0,
                                        60) !!}
                                        <a href="{{route('super-admin.notification.view',$item->id)}}"
                                            class="text-title-black text-decoration-underline hover-color-main-color">{{__('See
                                            More')}}</a>
                                    </p>
                                    @else
                                    <p class="fs-12 fw-400 lh-17 text-para-text max-w-220">{!! substr($item->body, 0,
                                        60)
                                        !!}
                                        <a href="{{route('super-admin.notification.view',$item->id)}}"
                                            class="text-title-black text-decoration-underline hover-color-main-color">{{__('See
                                            More')}}</a>
                                    </p>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Message -->
                <a href="#"
                    class="w-41 h-41 bd-one bd-c-stroke rounded-circle bg-white d-flex justify-content-center align-items-center d-none">
                    <img src="{{asset('assets')}}/images/icon/message.svg" alt="" />
                </a>
            </div>
        </div>
        <!-- User -->
        <div class="dropdown headerUserDropdown">
            <button class="dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center cg-8" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-content">
                    <div class="wrap">
                        <div class="img">
                            <img src="{{ asset(getFileUrl(auth()->user()->image)) }}" alt="{{ auth()->user()->name }}"
                                class="rounded-circle" />
                        </div>
                    </div>
                    <h4 class="text-start d-none d-md-block fs-13 fw-600 lh-16 text-title-color">{{ auth()->user()->name
                        }}
                    </h4>
                </div>
            </button>
            <ul class="dropdown-menu dropdownItem-one">
                <li>
                    <a class="d-flex align-items-center cg-8" href="{{ route('super-admin.setting.profile.index') }}">
                        <div class="d-flex">
                            <svg width="12" height="13" viewBox="0 0 12 13" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.8966 11.6036C11.2651 11.5268 11.4846 11.1411 11.3015 10.8122C10.8978 10.0871 10.2617 9.44993 9.44812 8.96435C8.40026 8.33898 7.11636 8 5.79556 8C4.47475 8 3.19085 8.33897 2.14299 8.96435C1.32936 9.44993 0.693348 10.0871 0.289627 10.8122C0.106496 11.1411 0.325986 11.5268 0.694529 11.6036V11.6036C4.05907 12.3048 7.53204 12.3048 10.8966 11.6036V11.6036Z"
                                    fill="#63647B" />
                                <circle cx="5.79574" cy="3.33333" r="3.33333" fill="#63647B" />
                            </svg>
                        </div>
                        <p class="fs-14 fw-500 lh-17 text-para-text">{{ __('Profile') }}</p>
                    </a>
                </li>
                <li>
                    <a class="d-flex align-items-center cg-8" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <div class="d-flex">
                            <svg width="10" height="14" viewBox="0 0 10 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.69547 0.823345L4.37659 0.301806C2.49791 0.00658503 1.55857 -0.141025 0.945912 0.382878C0.333252 0.906781 0.333252 1.85765 0.333252 3.75938V6.56258H4.75631L2.65829 3.94005L3.34155 3.39345L6.00821 6.72678L6.22686 7.00008L6.00821 7.27339L3.34155 10.6067L2.65829 10.0601L4.75631 7.43758H0.333252V10.2401C0.333252 12.1419 0.333252 13.0927 0.945912 13.6166C1.55857 14.1405 2.49791 13.9929 4.37658 13.6977L7.69547 13.1762C8.63623 13.0283 9.10661 12.9544 9.3866 12.627C9.66658 12.2996 9.66658 11.8234 9.66658 10.8711V3.12839C9.66658 2.17609 9.66658 1.69993 9.3866 1.37251C9.10661 1.0451 8.63623 0.971179 7.69547 0.823345Z"
                                    fill="#5D697A" />
                            </svg>
                        </div>
                        <p class="fs-14 fw-500 lh-17 text-para-text">{{ __('Logout') }}</p>
                    </a>
                </li>
            </ul>
        </div>
        @if (request()->route()->getName() == 'home')
        <!-- Home Right side for Mobile view -->
        <button
            class="d-md-none bd-one bd-c-ededed bd-ra-12 w-30 h-30 d-flex justify-content-center align-items-center text-707070 p-0 bg-transparent"
            type="button" data-bs-toggle="offcanvas" data-bs-target="#homeRightSideView"
            aria-controls="homeRightSideView">
            <img src="{{ asset('assets/images/icon/nav-right-menu.svg') }}" alt="" />
        </button>
        @endif

    </div>
</div>