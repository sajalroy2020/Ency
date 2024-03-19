<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ClientOrderConversationRequest;
use App\Http\Services\ClientOrderServices;
use App\Models\ClientOrder;
use App\Models\ClientOrderConversation;
use App\Models\ClientOrderConversationSeen;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponseTrait;

    public $clientOrderService;

    public function __construct()
    {
        $this->clientOrderService = new ClientOrderServices();
    }

    public function list(Request $request)
    {
        $data['pageTitleParent'] = __('');
        $data['pageTitle'] = __('Order list');
        $data['activeOrder'] = 'active';
        if ($request->ajax()) {
            return $this->clientOrderService->getClientOrderListData($request);
        }
        $data['orderCount'] = ClientOrder::where(['client_id' => auth()->id()])->count();
        return view('user.orders.list', $data);
    }
    public function getService()
    {
        $data = $this->clientOrderService->getInvoice();
        return $this->success($data);
    }

    public function details($id)
    {
        $data['pageTitleParent'] = __('Order');
        $data['pageTitle'] = __('Order Details');
        $data['activeClientOrderIndex'] = 'active';
        $data['orderDetails'] = ClientOrder::where('id', decrypt($id))->with(['client_order_items'])->first();
        $data['conversationData'] = ClientOrderConversation::where(['order_id'=> decrypt($id), 'type'=> CONVERSATION_TYPE_CLIENT])->get();

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

        return view('user.orders.details',$data);
    }
    public function conversationStore(ClientOrderConversationRequest $request)
    {
        return $this->clientOrderService->conversationStore($request);
    }
}
