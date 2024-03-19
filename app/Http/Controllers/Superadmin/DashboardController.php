<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Services\DashboardService;
use App\Models\Package;
use App\Models\SubscriptionOrder;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    use ResponseTrait;

    public $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(Request $request)
    {
        $data['title'] = __('Dashboard');
        $data['activeDashboard'] = 'active';
        $data['totalUser'] = User::where('role', USER_ROLE_ADMIN)->count();
        $data['totalClient'] = User::where('role', USER_ROLE_CLIENT)->count();
        $data['totalPackage'] = Package::count();
        $data['totalRevenue'] = SubscriptionOrder::where('payment_status', PAYMENT_STATUS_PAID)->sum('total');
        return view('sadmin.dashboard', $data);
    }

    public function userOverviewChartData(Request $request)
    {
        return $this->dashboardService->userOverviewChartData($request);
    }
}
