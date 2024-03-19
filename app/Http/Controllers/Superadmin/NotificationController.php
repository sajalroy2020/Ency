<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationSeen;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function notificationView($id)
    {
        $data['pageTitle'] = 'Notification View';
        $data['singleNotification'] = Notification::find($id);

        if($data['singleNotification'] !=null){
            $dataArray = [
                'user_id'=> $data['singleNotification']->user_id,
                'notification_id'=> $data['singleNotification']->id,
            ];
            NotificationSeen::firstOrCreate($dataArray);
        }
        return view('sadmin.notification.view', $data);
    }
    public function notificationDelete($id)
    {
        DB::beginTransaction();
        try {
            $data= Notification::where('id', $id)->firstOrFail();
            if (!$data && $data == null) {
                return $this->error([], SOMETHING_WENT_WRONG);
            }
            $data->delete();
            DB::commit();
            return redirect()->back()->with('success', DELETED_SUCCESSFULLY);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', SOMETHING_WENT_WRONG);
        }
    }

    public function allNotification(){
        $data['pageTitle'] = 'All Notification';
        return view('sadmin.notification.all', $data);
    }
    public function notificationMarkAsRead($id){
        DB::beginTransaction();
        try {
            $notificationData = Notification::where('id','=',$id)->first();
            $dataArray = [
                'user_id'=> $notificationData->user_id,
                'notification_id'=> $notificationData->id,
            ];
            NotificationSeen::firstOrCreate($dataArray);

            if($notificationData->link != NULL){
                return redirect()->to($notificationData->link);
            }
            DB::commit();
            return redirect()->back()->with('success', UPDATED_SUCCESSFULLY);
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('error', SOMETHING_WENT_WRONG);

        }

    }
    public function notificationMarkAllAsRead(){
        DB::beginTransaction();
        try {
            foreach (userNotification('unseen') as $item){
                $dataArray = [
                    'user_id'=> auth()->id(),
                    'notification_id'=> $item->id,
                ];
                NotificationSeen::firstOrCreate($dataArray);
            }
            DB::commit();
            return redirect()->back()->with('success', UPDATED_SUCCESSFULLY);
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('error', SOMETHING_WENT_WRONG);

        }
    }



}
