@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    @include("admin.setting.sidebar")
                </div>
            </div>
            <div class="col-xl-9">
                <!--  -->
                <div class="text-end pb-19">
                    <a href="{{route('admin.setting.role-permission.add-new')}}" class="border-0 bg-main-color py-8 px-26 bd-ra-8 fs-15 fw-600 lh-25 text-white">+ {{__("Add Role")}}</a>
                </div>
                <!--  -->
                <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white">
                    <table class="table zTable zTable-last-item-right" id="roleListTable">
                        <thead>
                        <tr>
                            <th><div>{{__("Role")}}</div></th>
                            <th><div>{{__("Action")}}</div></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="roleListRoute" value="{{route('admin.setting.role-permission.list')}}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/role_permission.js') }}"></script>
@endpush

