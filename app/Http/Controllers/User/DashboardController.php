<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Services\DashboardService;
use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\Ticket;
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
        if ($request->ajax()) {

            return $this->dashboardService->ticketSummeryForClient($request);

        }
        $data['pageTitleParent'] = __('');
        $data['pageTitle'] = __('Dashboard');
        $data['isDashboard'] = true;
        $data['activeDashboard'] = 'active';
        $data['totalUser'] = 0;
        $data['totalCustomer'] = 0;
        $data['paymentPending'] = ClientInvoice::where(['payment_status' => PAYMENT_STATUS_PENDING, 'client_id' => auth()->id()])->count();
        $data['openTicket'] = Ticket::where(['client_id' => auth()->id()])
            ->whereIn('status',[TICKET_STATUS_OPEN,TICKET_STATUS_IN_PROGRESS])
            ->count();
        $data['completedTicket'] = Ticket::where(['client_id' => auth()->id()])
            ->whereIn('status',[TICKET_STATUS_RESOLVED,TICKET_STATUS_CLOSED])
            ->count();

        $data['openOrders'] = ClientOrder::where(['working_status' => WORKING_STATUS_WORKING, 'client_id' => auth()->id()])->count();
        $data['completedOrders'] = ClientOrder::where(['working_status' => WORKING_STATUS_COMPLETED, 'client_id' => auth()->id()])->count();

        return view('user.dashboard', $data);
    }

    public function orderSummery(Request $request)
    {
        return $this->dashboardService->orderSummeryForClient($request);
    }

}
