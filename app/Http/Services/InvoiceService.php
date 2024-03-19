<?php

namespace App\Http\Services;

use App\Models\EmailTemplate;
use App\Models\Invoice;
use App\Models\InvoiceSetting;
use App\Models\Subscription;
use App\Models\User;
use App\Traits\ResponseTrait;
use Doctrine\DBAL\Driver\Exception;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    use ResponseTrait;

    public function makeInvoice($recurring = 0, $subscription_id)
    {
        try {
            $subscriptionData = Subscription::find($subscription_id);
            $dataObj = new Invoice();
            $dataObj->user_id = $subscriptionData->user_id;
            $dataObj->customer_id = $subscriptionData->customer_id;
            $dataObj->product_id = $subscriptionData->product_id;
            $dataObj->plan_id = $subscriptionData->plan_id;
            $dataObj->subscription_id = $subscriptionData->id;
            $dataObj->due_date = now()->addDays($subscriptionData->due_day)->format('Y-m-d');
            $dataObj->amount = $subscriptionData->amount;
            $dataObj->setup_fees = $subscriptionData->setup_fee ?? 0;
            $dataObj->shipping_charge = $subscriptionData->shipping_charge;
            $dataObj->is_recurring = $recurring;
            $dataObj->save();
            if ($dataObj->id) {
                $invoiceSettingData = InvoiceSetting::where('user_id', $dataObj->user_id)->first();
                $invoiceId = (isset($invoiceSettingData->prefix) && $invoiceSettingData->prefix != null) ? $invoiceSettingData->prefix . sprintf('%06d', $dataObj->id) : 'INV' . sprintf('%06d', $dataObj->id);
                Invoice::where('id', $dataObj->id)->update(['invoice_id' => $invoiceId]);
            }
            Log::info('Invoice Created Successfully. Id - ' . $dataObj->id);
            return $dataObj;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }
    }

    public function invoiceMailToCustomer($invoiceId)
    {
        try {
            if (getOption('app_mail_status') == 1) {

                $newInvoiceData = Invoice::find($invoiceId);
                if ($newInvoiceData) {
                    $template = EmailTemplate::where('category', EMAIL_TEMPLATE_INVOICE)->where('user_id', $newInvoiceData->user_id)->first();
                    $user = User::find($newInvoiceData->user_id);
                    $customer = User::find($newInvoiceData->customer_id);
                    if ($template) {
                        $customizedFieldsArray = [
                            '{{app_name}}' => getOption('app_name'),
                            '{{app_email}}' => getOption('app_email'),
                            '{{username}}' => $user->name,
                            '{{view_link}}' => '',
                        ];
                        $content = getEmailTemplateContent($template->body, $template->category, $customizedFieldsArray);
                        if ($content) {
                            $this->sendCustomizeMail($customer->email, $template->subject, $content);
                            Log::info('Invoice mail send successfully');
                        }
                    }
                }
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }
    }
}
