<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClientOrderRequest;
use App\Http\Requests\User\ClientOrderConversationRequest;
use App\Http\Services\ClientInvoiceServices;
use App\Http\Services\ClientOrderServices;
use App\Models\ClientOrder;
use App\Models\ClientOrderAssignee;
use App\Models\ClientOrderConversation;
use App\Models\ClientOrderConversationSeen;
use App\Models\ClientOrderItem;
use App\Models\ClientOrderNote;
use App\Models\OrderAssignee;
use App\Models\Service;
use App\Models\TicketAssignee;
use App\Models\TicketSeenUnseen;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ClientOrderController extends Controller
{
    use ResponseTrait;

    public $clientOrderService;

    public function __construct()
    {
        $this->clientOrderService = new ClientOrderServices();
    }

    public function list(Request $request)
    {
        $data['pageTitle'] = __('Client Order list');
        $data['activeClientOrderIndex'] = 'active';
        if ($request->ajax()) {
            return $this->clientOrderService->getClientOrderListData($request);
        }
        return view('admin.orders.list', $data);
    }

    public function add()
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Client Order Add List');
        $data['activeClientOrderIndex'] = 'active';
        $data['allClient'] = User::where('role', USER_ROLE_CLIENT)->where('tenant_id', auth()->user()->tenant_id)->get();
        $data['allService'] = Service::where(['tenant_id'=> auth()->user()->tenant_id, 'status'=> ACTIVE])->get();

        return view('admin.orders.add',$data);
    }

    public function getService()
    {
        $data = $this->clientOrderService->getInvoice();
        return $this->success($data);
    }

    public function store(ClientOrderRequest $request)
    {
        return $this->clientOrderService->store($request);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Edit Order');
        $data['activeClientOrderIndex'] = 'active';
        $data['order'] = ClientOrder::with('client')->find($id);
        $data['orderItem'] = ClientOrderItem::where('order_id', $id)->get();
        $data['allClient'] = User::where('role', USER_ROLE_CLIENT)->where('tenant_id', auth()->user()->tenant_id)->get();
        $data['allService'] = Service::where(['tenant_id'=> auth()->user()->tenant_id, 'status'=> ACTIVE])->get();
        return view('admin.orders.edit', $data);
    }

    public function delete($id)
    {
        return $this->clientOrderService->delete($id);
    }

    public function details($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Order Details');
        $data['activeClientOrderIndex'] = 'active';
        $data['orderDetails'] = ClientOrder::where('id', decrypt($id))->with(['client_order_items','assignee','notes'])->first();
        $data['conversationClientTypeData'] = ClientOrderConversation::where(['order_id'=> decrypt($id), 'type'=> CONVERSATION_TYPE_CLIENT])->get();
        $data['conversationTeamTypeData'] = ClientOrderConversation::where(['order_id'=> decrypt($id), 'type'=> CONVERSATION_TYPE_TEAM])->get();
        $data['teamMemberList'] = User::where(['role' => USER_ROLE_TEAM_MEMBER, 'tenant_id' => auth()->user()->tenant_id])->get();

        $assigneeList = [];
        if($data['orderDetails'] != null) {
            foreach ($data['orderDetails']->assignee as $key => $assignee) {
                $assigneeList[$key] = $assignee->assigned_to;
            }
        }
        $data['orderAssignee'] = $assigneeList;

        $seenUneenData = ClientOrderConversationSeen::where(['order_id'=>decrypt($id), 'created_by'=>auth()->id()])->first();
        if(is_null($seenUneenData)){
            $seenUneenData = new ClientOrderConversationSeen();
            $seenUneenData->order_id = decrypt($id);
            $seenUneenData->created_by = auth()->id();
            $seenUneenData->is_seen = 1;
            $seenUneenData->tenant_id = auth()->user()->tenant_id;
        }else{
            $seenUneenData->is_seen = 1;
        }
        $seenUneenData->save();

        return view('admin.orders.details',$data);
    }
    public function conversationStore(ClientOrderConversationRequest $request)
    {
        return $this->clientOrderService->conversationStore($request);
    }

    public function statusChange($order_id, $status){
        DB::beginTransaction();
        try {
            $data = ClientOrder::find(decrypt($order_id));
            $data->working_status = $status;
            $data->save();

            DB::commit();
            return redirect()->back()->with(['success' => 'Status Change successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => SOMETHING_WENT_WRONG]);
        }
    }

    public function assignMember(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->checked_status == 1) {
                $data = new ClientOrderAssignee();
                $data->order_id = $request->order_id;
                $data->assigned_to = $request->member_id;
                $data->assigned_by = auth()->id();
                $data->is_active = ACTIVE;
                $data->save();
            } else {
                $data = ClientOrderAssignee::where(['order_id' => $request->order_id, 'assigned_to' => $request->member_id])->first();
                $data->delete();
            }
            DB::commit();
            return $this->success($data, 'Assignee Update');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function noteStore(Request $request){

        $request->validate([
            'order_id' => 'required',
            'details' => 'required'
        ]);

        DB::beginTransaction();
        try {
            if ($request->id) {
                $data = ClientOrderNote::find(decrypt($request->id));
                $msg = __("Note Updated Successfully");
            }else {
                $data = new ClientOrderNote();
                $msg = __("Note Created Successfully");
            }
                $data->order_id = decrypt($request->order_id);
                $data->details = $request->details;
                $data->user_id = auth()->id();
                $data->save();

            DB::commit();
            return $this->success([], $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

    public function noteDelete($id){
        try {
            DB::beginTransaction();
            $data = ClientOrderNote::where('id', decrypt($id))->first();
            $data->delete();
            DB::commit();
            return $this->success([], getMessage(DELETED_SUCCESSFULLY));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], getErrorMessage($e, $e->getMessage()));
        }
    }

}






