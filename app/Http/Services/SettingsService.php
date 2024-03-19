<?php

namespace App\Http\Services;

use App\Mail\CustomizeMail;
use App\Models\EmailTemplate;
use App\Models\FileManager;
use App\Models\Gateway;
use App\Models\InvoiceSetting;
use App\Models\License;
use App\Models\MailHistory;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserDetails;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SettingsService
{
    use ResponseTrait;


    public function cookieSettingUpdated($request)
    {

        $inputs = Arr::except($request->all(), ['_token']);

        foreach ($inputs as $key => $value) {


            $option = Setting::firstOrCreate(['option_key' => $key]);

            if ($request->hasFile('cookie_image') && $key == 'cookie_image') {
                $upload = settingImageStoreUpdate($value, $request->cookie_image);
                $option->option_value = $upload;
                $option->save();
            } else {
                $option->option_value = $value;
                $option->save();
            }
        }

        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    public function commonSettingUpdate($request)
    {
        $inputs = Arr::except($request->all(), ['_token']);

        foreach ($inputs as $key => $value) {


            $option = Setting::firstOrCreate(['option_key' => $key]);

            if ($request->hasFile('cookie_image') && $key == 'cookie_image') {
                $upload = settingImageStoreUpdate($value, $request->cookie_image);
                $option->option_value = $upload;
                $option->save();
            } else {
                $option->option_value = $value;
                $option->save();
            }
        }

        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }
    public function smsConfigurationStore($request)
    {
        $inputs = Arr::except($request->all(), ['_token']);

        foreach ($inputs as $key => $value) {

            $option = Setting::firstOrCreate(['option_key' => $key]);
            $option->option_value = $value;
            $option->save();
        }

        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    public function getEmailTemplate($tenant_id = null)
    {
        return EmailTemplate::where('tenant_id', $tenant_id)->get();
    }

    public function emailTemplateConfig($request)
    {
        try {
            $data['template'] = EmailTemplate::find($request->id);
            $data['fields'] = customEmailTempFields($data['template']->category);
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function emailTemplateConfigUpdate($request)
    {
        DB::beginTransaction();
        try {
            $emailTemplate = EmailTemplate::findOrFail($request->id);
            $emailTemplate->title = $request->title;
            $emailTemplate->subject = $request->subject;
            $emailTemplate->body = $request->body;
            $emailTemplate->save();

            DB::commit();
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function emailTemplateStatus($request)
    {
        DB::beginTransaction();
        try {
            $template = EmailTemplate::where('category', $request->category)->where('user_id', auth()->id())->first();
            if ($template && $template->subject) {
                $status = $template->status == ACTIVE ? DEACTIVATE : ACTIVE;
                $template->status = $status;
                $template->save();
            } else {
                throw new Exception(__('Please Config Email Template'));
            }
            DB::commit();
            return $this->success([], __(STATUS_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public static function sentMailHistoryStore($user_id, $email, $subject, $content, $status, $error = null)
    {
        $history = new MailHistory();
        $history->user_id = $user_id;
        $history->host = env('MAIL_HOST');
        $history->email = $email;
        $history->subject = $subject;
        $history->content = $content;
        $history->status = $status;
        $history->date = now();
        $history->error = $error;
        $history->save();
    }

    public function invoiceUpdate($request)
    {
        DB::beginTransaction();
        try {
            $invoice = InvoiceSetting::where('user_id', auth()->id())->first();
            if (is_null($invoice)) {
                $invoice = new InvoiceSetting();
            }
            $invoice->user_id = auth()->id();
            $invoice->type = INVOICE_SETTING_TYPE_ORDER;
            $invoice->title = $request->title;
            $invoice->company_info = $request->company_info;
            $invoice->prefix = $request->prefix;
            $invoice->info_one = $request->info_one;
            $invoice->info_two = $request->info_two;
            $invoice->info_three = $request->info_three;
            $invoice->footer_text = $request->footer_text;
            $invoice->column = json_encode($request->column);

            if ($request->hasFile('logo')) {
                $existFile = FileManager::where('id', $invoice->logo)->first();
                if ($existFile) {
                    $existFile->removeFile();
                    $uploaded = $existFile->upload('InvoiceSetting', $request->logo, 'invoice-setting', $existFile->id);
                } else {
                    $newFile = new FileManager();
                    $uploaded = $newFile->upload('InvoiceSetting', $request->logo, 'invoice-setting');
                }
                if ($uploaded) {
                    $invoice->logo = $uploaded->id;
                } else {
                    throw new Exception(__('Image Not Uploaded.'));
                }
            }
            $invoice->save();
            DB::commit();

            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }


    public function invoiceSettingInfo()
    {
        return InvoiceSetting::where('user_id', auth()->id())->first();
    }
}
