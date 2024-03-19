<?php

namespace App\Http\Controllers\Addon\Saas\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageRequest;
use App\Http\Services\PackageService;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    use ResponseTrait;
    public $packageService;

    public function __construct()
    {
        $this->packageService = new PackageService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->packageService->getAllData($request);
        } else {
            $data['title'] = __('All Packages');
            $data['activePackageIndex'] = 'active';
            return view('sadmin.package.index', $data);
        }
    }

    public function store(PackageRequest $request)
    {
        return $this->packageService->store($request);
    }

    public function edit($id)
    {
        $data['package'] =$this->packageService->getInfo($id);
        return view('sadmin.package.edit-form', $data);
    }

    public function getInfo(Request $request)
    {
        $data = $this->packageService->getInfo($request->id);
        return $this->success($data);
    }

    public function destroy($id)
    {
        return $this->packageService->destroy($id);
    }

     public function userPackage(Request $request)
     {
         if ($request->ajax()) {
             return $this->packageService->getUserPackagesData($request);
         } else {
             $data['title'] = __('User Packages');
             $data['subNavUserPackagesActiveClass'] = 'mm-active';
             $data['navSubscriptionActiveClass'] = 'active';
             $data['users'] = User::where('role', USER_ROLE_ADMIN)->get();
             $data['packages'] = $this->packageService->getAll();
             return view('sadmin.package.user', $data);
         }
     }

    public function assignPackage(Request $request)
    {
        return $this->packageService->assignPackage($request);
    }

}
