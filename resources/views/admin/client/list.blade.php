@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@section('content')
    <!-- Content -->
<span id="searchresult">
   @if(count($clientList) > 0)
            <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <!-- Search - Create -->
        <div
            class="d-flex flex-column-reverse flex-sm-row justify-content-center justify-content-md-between align-items-center flex-wrap g-10 pb-18">
            <div class="flex-grow-1">
                <div class="search-one flex-grow-1 max-w-282">
                    <input type="text" placeholder="{{__('Search here')}}..."/>
                    <button class="icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.71401 15.7857C12.6194 15.7857 15.7854 12.6197 15.7854 8.71428C15.7854 4.80884 12.6194 1.64285 8.71401 1.64285C4.80856 1.64285 1.64258 4.80884 1.64258 8.71428C1.64258 12.6197 4.80856 15.7857 8.71401 15.7857Z"
                                stroke="#707070" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18.3574 18.3571L13.8574 13.8571" stroke="#707070" stroke-width="1.35902"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
            <!--  -->
            <a href="{{route('admin.client.add-list')}}"
               class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white">+ {{__('Add Client')}}</a>
        </div>
                <!--  -->
        <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15">
            <table class="table zTable zTable-last-item-right" id="clientListDatatable">
                <thead>
                <tr>
                    <th>
                        <div>{{__('Name')}}</div>
                    </th>
                    <th>
                        <div class="text-nowrap">{{__('Email Address')}}</div>
                    </th>
                    <th>
                        <div>{{__('Company')}}</div>
                    </th>
                    <th>
                        <div>{{__('Action')}}</div>
                    </th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
        @else
            <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
            <div class="p-sm-30 p-15 bg-white bd-one bd-c-stroke bd-ra-10">
                <div class="create-wrap">
                    <div class="mb-22"><img src="{{ asset('assets/images/create-icon.png') }}" alt=""/></div>
                    <h4 class="pb-22 fs-24 fw-500 lh-30 text-title-black text-center">{{__("There is no Client available here!")}}</h4>
                    <a href="{{route('admin.client.add-list')}}"
                       class="d-inline-flex bd-ra-8 bg-main-color py-10 px-26 fs-15 fw-600 lh-25 text-white">+{{__("Add Client")}}</a>
                </div>
            </div>
        </div>
   @endif
</span>
    <input type="hidden" id="client-list-route" value="{{route('admin.client.list')}}">
@endsection
@push('script')
    <script src="{{ asset('admin/custom/js/client.js') }}"></script>
@endpush

