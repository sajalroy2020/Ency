<?php

namespace App\Http\Services;

use App\Models\FileManager;
use App\Models\Service;
use App\Models\ServiceAssignee;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ServiceManagerService
{
    use ResponseTrait;

    public function getAll()
    {
        return Service::query()
            ->where('user_id', auth()->id())
            ->get();
    }

    public function store($request)
    {

        DB::beginTransaction();
        try {
            if ($request->id) {

                $data = Service::find($request->id);
                if ($data->assign_member !=null && count(json_decode($data->assign_member)) > 0) {
                    ServiceAssignee::where('service_id', $request->id)->update(['is_active' => DEACTIVATE]);
                }
                $msg = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                $data = new Service();
                $msg = getMessage(CREATED_SUCCESSFULLY);
            }
            $data->service_name = $request->service_name;

            $data->service_description = $request->service_description;
            $data->duration = isset($request->duration) ? $request->duration : '';

            if ($request->assign_member != null && count($request->assign_member) > 0) {
                $data->assign_member = json_encode($request->assign_member);
            }

            if ($request->image) {
                if ($request->id) {
                    $file = FileManager::where('id', $data->image)->first();
                    if ($file) {
                        $file->removeFile();
                        $uploaded = $file->upload('Service', $request->image, '', $file->id);
                    } else {
                        $file = new FileManager();
                        $uploaded = $file->upload('Service', $request->image);
                    }
                } else {
                    $file = new FileManager();
                    $uploaded = $file->upload('Service', $request->image);
                }
                $data->image = $uploaded->id;
            }

            $data->payment_type = $request->payment_type;
            if ($request->payment_type == PAYMENT_TYPE_ONETIME) {
                $data->price = $request->onetime_price;
            } else {
                $data->price = $request->recurring_price;
                $data->recurring_type = $request->recurring_type;
            }
            $data->user_id = auth()->id();
            $data->tenant_id = auth()->user()->tenant_id;
            $data->save();

            if ($request->assign_member != null && count($request->assign_member) > 0) {
                foreach ($request->assign_member as $key => $assignee) {
                    $dataObj = new ServiceAssignee();
                    $dataObj->service_id = $data->id;
                    $dataObj->assign_to = $assignee;
                    $dataObj->assign_by = auth()->id();
                    $dataObj->is_active = ACTIVE;
                    $dataObj->save();
                }
                $data->assign_member = json_encode($request->assign_member);
            }

            DB::commit();
            return $this->success([], $msg);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function list($dataShowLimit = null)
    {
        $service = Service::query()
            ->where(['user_id' => auth()->id(), 'status' => ACTIVE])
            ->orderBy('id', 'DESC')
            ->paginate($dataShowLimit ?? 10);
        return $service;
    }

    public function clientListShow($dataShowLimit = null)
    {
        $service =  Service::query()
            ->leftJoin('client_order_items', ['services.id' => 'client_order_items.service_id'])
            ->leftJoin('client_orders', function ($q) {
                $q->on('client_order_items.order_id', 'client_orders.id')->where('client_id', auth()->id());
            })
            ->selectRaw('services.*, COUNT(CASE WHEN client_orders.payment_status = ? THEN client_order_items.id END) as buy_service_count', [PAYMENT_STATUS_PAID])
            ->where('services.tenant_id', auth()->user()->tenant_id)
            ->where('services.status', STATUS_ACTIVE)
            ->orderBy('services.id', 'DESC')
            ->groupBy('services.id')
            ->paginate($dataShowLimit ?? 10);

        return $service;
    }
}
