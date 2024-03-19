<?php

namespace App\Http\Controllers;

use App\Http\Services\Payment\Payment;
use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\Gateway;
use App\Models\Quotation;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    use ResponseTrait;

    public function verify(Request $request)
    {
        $invoice_id = $request->get('id', '');
        $payerId = $request->get('PayerID', NULL);
        $payment_id = $request->get('payment_id', NULL);
        $clientInvoice = ClientInvoice::findOrFail($invoice_id);
        $clientOrder = ClientOrder::findOrFail($clientInvoice->order_id);
        if ($clientInvoice->payment_status == PAYMENT_STATUS_PAID) {
            return redirect()->route('thankyou');
        }

        $gateway = Gateway::find($clientInvoice->gateway_id);
        DB::beginTransaction();
        try {
            if ($clientInvoice->gateway_id == $gateway->id && $gateway->slug == MERCADOPAGO) {
                $clientInvoice->payment_id = $payment_id;
                $clientInvoice->save();
            }

            $gatewayBasePayment = new Payment($gateway->slug, ['currency' => $clientInvoice->gateway_currency, 'tenant_id' => $clientInvoice->tenant_id]);

            $payment_data = $gatewayBasePayment->paymentConfirmation($clientInvoice->payment_id, $payerId);
            if ($payment_data['success']) {
                if ($payment_data['data']['payment_status'] == 'success') {
                    // invoice update
                    $clientInvoice->payment_status = PAYMENT_STATUS_PAID;
                    $clientInvoice->transaction_id = uniqid();
                    $clientInvoice->save();

                    $clientOrder->increment('transaction_amount', $clientInvoice->total);
                    if ($clientOrder->transaction_amount >= $clientOrder->total) {
                        $clientOrder->payment_status = PAYMENT_STATUS_PAID;
                    }
                    $clientOrder->save();
                    if (!is_null($clientOrder->quotation_id)) {
                        $quotation = Quotation::find($clientOrder->quotation_id);
                        if (!is_null($quotation)) {
                            $quotation->status = QUOTATION_STATUS_PAID;
                            $quotation->save();
                        }
                    }
                    DB::commit();

                    //notification call start
                    setCommonNotification($clientInvoice->client_id, __('Have a new checkout'), __('Invoice Id: ') . $clientInvoice->invoice_id, '');
                    // send success mail
                    orderMailNotify($clientInvoice->id, INVOICE_MAIL_TYPE_PAID);

                    return redirect()->route('thankyou');
                }
            } else {
                return redirect()->route('failed');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('failed');
        }
    }

    public function thankyou()
    {
        return view('frontend.thankyou');
    }

    public function waiting()
    {
        return view('frontend.waiting');
    }

    public function failed()
    {
        return view('frontend.failed');
    }
}
