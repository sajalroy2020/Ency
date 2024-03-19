<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Services\InvoiceService;
use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\Invoice;
use App\Models\InvoiceSetting;
use App\Models\Plan;
use App\Models\Product;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    use ResponseTrait;

    protected $invoice;

    public function __construct()
    {
        $this->invoice = new InvoiceService();
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::id();
            $invoice = Invoice::leftJoin('subscriptions', 'invoices.subscription_id', '=', 'subscriptions.id')
                ->leftJoin('products', 'invoices.product_id', '=', 'products.id')
                ->leftJoin('plans', 'invoices.plan_id', '=', 'plans.id')
                ->leftJoin('users', 'invoices.customer_id', '=', 'users.id')
                ->where(['invoices.user_id' => $user])
                ->select(
                    'subscriptions.subscription_id as subscription_id_name',
                    'products.name as product_name',
                    'plans.name as plan_name',
                    'users.email as customer_email',
                    'invoices.*'
                )
                ->orderByDesc('invoices.id');

            if ($request->plan_id != null) {
                $invoice->where('invoices.plan_id', $request->plan_id);
            }

            return datatables($invoice)
                ->addColumn('subscription_id_name', function ($data) {
                    return $data->subscription_id_name;
                })
                ->addColumn('customer_email', function ($data) {
                    return $data->customer_email;
                })
                ->addColumn('product_name', function ($data) {
                    return $data->product_name;
                })
                ->addColumn('plan_name', function ($data) {
                    return $data->plan_name;
                })
                ->addColumn('due_date', function ($data) {
                    return date('d/m/Y', strtotime($data->due_date));
                })
                ->addColumn('created_at', function ($data) {
                    return $data->created_at?->format('d/m/Y');
                })
                ->addColumn('status', function ($data) {
                    if ($data->payment_status == PAYMENT_STATUS_PAID) {
                        return "<p class='zBadge zBadge-active'>" . __('Paid') . "</p>";
                    } else if ($data->payment_status == PAYMENT_STATUS_PENDING) {
                        return "<p class='zBadge zBadge-pending'>" . __('Pending') . "</p>";
                    } else {
                        return "<p class='zBadge zBadge-fuilure'>" . __('Canceled') . "</p>";
                    }
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex justify-content-end">
                            <button class="flex-shrink-0 border-0 p-0 bg-transparent invoice-view-action" data-bs-toggle="modal" data-bs-target="#invoicePreviewModal" data-id="' . $data->id . '">
                              <img src="' . asset('assets/images/icon/eye.svg') . '" alt="invoice">
                             </button>
                        </div>';
                })
                ->rawColumns(['action', 'user', 'status'])
                ->make(true);
        }
        $user = Auth::id();
        $data['pageTitle'] = __('Invoice');
        $data['activeInvoiceList'] = 'active';
        $data['product'] = Product::where('status', STATUS_ACTIVE)
            ->where('user_id', $user)
            ->get();
        return view('user.invoice.index', $data);
    }

    public function invoicePrint(Request $request)
    {
        $data['invoiceInfo'] = Invoice::join('users', 'invoices.user_id', '=', 'users.id')
            ->join('orders', 'invoices.order_id', '=', 'orders.id')
            ->join('user_details', 'invoices.user_id', '=', 'user_details.user_id')
            ->join('gateways', 'invoices.gateway_id', '=', 'gateways.id')
            ->join('products', 'invoices.product_id', '=', 'products.id')
            ->join('plans', 'invoices.plan_id', '=', 'plans.id')
            ->join('coupons', 'invoices.coupon_id', '=', 'coupons.id')
            ->where('invoices.id', $request->id)
            ->select(
                'users.company_name as user_name_company',
                'users.company_address as user_company_address',
                'users.company_city as user_company_city',
                'users.mobile as user_mobile_number',
                'orders.order_number as payment_order_number',
                'orders.created_at as order_date',
                'gateways.title as payment_gateway_name',
                'products.name as products_name',
                'plans.name as plans_name',
                'user_details.*',
                'invoices.*',
                'coupons.*'
            )->first();

        return view('user.invoice.print-invoice', $data);
    }

    public function getPlanData(Request $request)
    {
        $data['plan'] = Plan::where('product_id', $request->id)->get();
        return $this->success(view('user.subscription.plan-render', $data)->render());
    }

    public function invoiceView(Request $request)
    {
        $data['invoiceInfo'] = Invoice::query()
            ->leftJoin('users', 'invoices.customer_id', '=', 'users.id')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('orders', 'invoices.id', '=', 'orders.invoice_id')
            ->leftJoin('products', 'invoices.product_id', '=', 'products.id')
            ->leftJoin('plans', 'invoices.plan_id', '=', 'plans.id')
            ->leftJoin('coupons', 'invoices.coupon_id', '=', 'coupons.id')
            ->where('invoices.id', $request->id)
            ->select(
                'invoices.*',
                'users.name as customer_name',
                'users.company_name as customer_company',
                'users.email as customer_email',
                'users.mobile as customer_phone',
                'users.zip_code as customer_zip',
                'users.address as customer_address',
                'users.country as customer_country',
                'users.state as customer_state',
                'users.city as customer_city',
                'orders.total',
                'orders.shipping_cost as order_shipping_cost',
                'orders.tax_amount as order_tax_amount',
                'orders.discount as plan_discount',
                'products.name as products_name',
                'plans.name as plans_name',
                'user_details.basic_info',
                'user_details.basic_first_name',
                'user_details.basic_last_name',
                'user_details.basic_phone',
                'user_details.basic_email',
                'user_details.basic_company',
                'user_details.billing_info',
                'user_details.billing_first_name',
                'user_details.billing_last_name',
                'user_details.billing_phone',
                'user_details.billing_zip_code',
                'user_details.billing_address',
                'user_details.billing_city',
                'user_details.billing_state',
                'user_details.billing_country',
                'user_details.shipping_info',
                'user_details.shipping_first_name',
                'user_details.shipping_last_name',
                'user_details.shipping_email',
                'user_details.shipping_phone',
                'user_details.shipping_address',
                'user_details.shipping_method',
                'coupons.name',
                'coupons.code',
                'coupons.discount_type',
                'coupons.discount',
                'coupons.redemption_type',
                'coupons.product_plan',
                'coupons.valid_date',
                'coupons.maximum_redemption',
                'coupons.status',
            )
            ->first();

        $invoiceSetting = InvoiceSetting::where('user_id', $data['invoiceInfo']->user_id)->first();
        if ($invoiceSetting) {
            $customizedFieldsArray = $this->customizedFieldsArray($data);
            $data['invoiceSettingCompanyInfo'] = replaceBrackets($invoiceSetting->company_info, $customizedFieldsArray);
            $data['invoiceSettingInfoOne'] = replaceBrackets($invoiceSetting->info_one, $customizedFieldsArray);
            $data['invoiceSettingInfoTwo'] = replaceBrackets($invoiceSetting->info_two, $customizedFieldsArray);
            $data['invoiceSettingInfoThree'] = replaceBrackets($invoiceSetting->info_three, $customizedFieldsArray);
            $data['invoiceSettingFooterText'] = replaceBrackets($invoiceSetting->footer_text, $customizedFieldsArray);
            $data['invoiceSettingTitle'] = $invoiceSetting->title;
            $data['invoiceSettingPrefix'] = $invoiceSetting->prefix;
            $data['invoiceSettingColumn'] = json_decode($invoiceSetting->column);
            $data['invoiceSettingLogo'] = getFileUrl($invoiceSetting->logo);
        }
        $view = view('user.invoice.invoice-view', $data)->render();
        return $this->success($view);
    }

    public function recurringInvoiceMaker()
    {
        $currentDate = date('Y-m-d');
        $clientOrders = ClientOrder::query()
            ->where('payment_status', PAYMENT_STATUS_PAID)
            ->where('recurring_payment_type', PAYMENT_TYPE_RECURRING)
            ->get();

        foreach ($clientOrders as $order) {
            $invoiceData = [
                'order' => $order,
                'due_date' => now()->addDays(5),
                'payable_amount' => $order->total,
                'is_recurring' => ACTIVE,
            ];
            $clientExist = User::where('status', ACTIVE)->find($order->client_id);
            if ($clientExist) {
                if ($order->recurring_type == RECURRING_EVERY_DAY) {
                    $clientInvoiceExist = ClientInvoice::query()
                        ->where('order_id', $order->id)
                        ->whereDate('created_at', $currentDate)
                        ->first();

                    if (!$clientInvoiceExist) {
                        makeClientInvoice($invoiceData, $order->client_id, $order->user_id, $order->tenant_id);
                    }
                } elseif ($order->recurring_type == RECURRING_EVERY_MONTH) {
                    $clientInvoiceExist = ClientInvoice::query()
                        ->where('order_id', $order->id)
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->first();

                    if (!$clientInvoiceExist) {
                        makeClientInvoice($invoiceData, $order->client_id, $order->user_id, $order->tenant_id);
                    }
                } elseif ($order->recurring_type == RECURRING_EVERY_YEAR) {
                    $clientInvoiceExist = ClientInvoice::query()
                        ->where('order_id', $order->id)
                        ->whereYear('created_at', now()->year)
                        ->first();
                    if (!$clientInvoiceExist) {
                        makeClientInvoice($invoiceData, $order->client_id, $order->user_id, $order->tenant_id);
                    }
                }
            }
        }
    }

    public function invoiceDownload($id)
    {

        try {
            $data['invoiceInfo'] = Invoice::query()
                ->join('users', 'invoices.customer_id', '=', 'users.id')
                ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
                ->join('orders', 'invoices.id', '=', 'orders.invoice_id')
                ->join('products', 'invoices.product_id', '=', 'products.id')
                ->join('plans', 'invoices.plan_id', '=', 'plans.id')
                ->leftJoin('coupons', 'invoices.coupon_id', '=', 'coupons.id')
                ->where('invoices.id', decrypt($id))
                ->select(
                    'invoices.*',
                    'users.name as customer_name',
                    'users.company_name as customer_company',
                    'users.email as customer_email',
                    'users.mobile as customer_phone',
                    'users.zip_code as customer_zip',
                    'users.address as customer_address',
                    'users.country as customer_country',
                    'users.state as customer_state',
                    'users.city as customer_city',
                    'orders.total',
                    'orders.shipping_cost as order_shipping_cost',
                    'orders.tax_amount as order_tax_amount',
                    'orders.discount as plan_discount',
                    'products.name as products_name',
                    'plans.name as plans_name',
                    'user_details.basic_info',
                    'user_details.basic_first_name',
                    'user_details.basic_last_name',
                    'user_details.basic_phone',
                    'user_details.basic_email',
                    'user_details.basic_company',
                    'user_details.billing_info',
                    'user_details.billing_first_name',
                    'user_details.billing_last_name',
                    'user_details.billing_phone',
                    'user_details.billing_zip_code',
                    'user_details.billing_address',
                    'user_details.billing_city',
                    'user_details.billing_state',
                    'user_details.billing_country',
                    'user_details.shipping_info',
                    'user_details.shipping_first_name',
                    'user_details.shipping_last_name',
                    'user_details.shipping_email',
                    'user_details.shipping_phone',
                    'user_details.shipping_address',
                    'user_details.shipping_method',
                    'coupons.name',
                    'coupons.code',
                    'coupons.discount_type',
                    'coupons.discount',
                    'coupons.redemption_type',
                    'coupons.product_plan',
                    'coupons.valid_date',
                    'coupons.maximum_redemption',
                    'coupons.status',
                )
                ->first();

            $invoiceSetting = InvoiceSetting::where('user_id', $data['invoiceInfo']->user_id)->first();
            if ($invoiceSetting) {
                $customizedFieldsArray = $this->customizedFieldsArray($data);
                $data['invoiceSettingCompanyInfo'] = replaceBrackets($invoiceSetting->company_info, $customizedFieldsArray);
                $data['invoiceSettingInfoOne'] = replaceBrackets($invoiceSetting->info_one, $customizedFieldsArray);
                $data['invoiceSettingInfoTwo'] = replaceBrackets($invoiceSetting->info_two, $customizedFieldsArray);
                $data['invoiceSettingInfoThree'] = replaceBrackets($invoiceSetting->info_three, $customizedFieldsArray);
                $data['invoiceSettingFooterText'] = replaceBrackets($invoiceSetting->footer_text, $customizedFieldsArray);
                $data['invoiceSettingTitle'] = $invoiceSetting->title;
                $data['invoiceSettingPrefix'] = $invoiceSetting->prefix;
                $data['invoiceSettingColumn'] = json_decode($invoiceSetting->column);
                $data['invoiceSettingLogo'] = getFileUrl($invoiceSetting->logo);
            }

            $data['title'] = $data['invoiceInfo']->invoice_id . '.pdf';
            if ($data['invoiceInfo'] != null) {
                return view('user.invoice.print-invoice', $data);
            } else {
                abort(404);
            }
        } catch (\Exception $exception) {
            abort(404);
        }
    }

    public function customizedFieldsArray($data)
    {
        $user = User::find($data['invoiceInfo']->user_id);
        return [
            'invoice_id' => $data['invoiceInfo']->invoice_id,
            'order_date' => $data['invoiceInfo']->created_at?->format('Y-m-d'),
            'invoice_due_date' => $data['invoiceInfo']->due_date,
            'customer_name' => $data['invoiceInfo']->customer_name,
            'customer_company' => $data['invoiceInfo']->customer_company,
            'customer_email' => $data['invoiceInfo']->customer_email,
            'customer_phone' => $data['invoiceInfo']->customer_phone,
            'customer_zip' => $data['invoiceInfo']->customer_zip,
            'customer_address' => $data['invoiceInfo']->customer_address,
            'customer_city' => $data['invoiceInfo']->customer_city,
            'customer_state' => $data['invoiceInfo']->customer_state,
            'customer_country' => $data['invoiceInfo']->customer_country,
            'billing_zip' => $data['invoiceInfo']->billing_zip_code,
            'billing_address' => $data['invoiceInfo']->billing_address,
            'billing_city' => $data['invoiceInfo']->billing_city,
            'billing_state' => $data['invoiceInfo']->billing_state,
            'billing_country' => $data['invoiceInfo']->billing_country,
            'shipping_zip' => $data['invoiceInfo']->shipping_zip,
            'shipping_address' => $data['invoiceInfo']->shipping_address,
            'shipping_city' => $data['invoiceInfo']->shipping_city,
            'shipping_state' => $data['invoiceInfo']->shipping_state,
            'shipping_country' => $data['invoiceInfo']->shipping_country,
            'total' => $data['invoiceInfo']->total,
            'company_name' => $user->company_name,
            'company_phone' => $user->company_phone,
            'company_zip' => $user->company_zip_code,
            'company_address' => $user->company_address,
            'company_city' => $user->company_city,
            'company_state' => $user->company_state,
            'company_country' => $user->company_country,
            'payment_method' => $data['invoiceInfo']->payment_status == PAYMENT_STATUS_PAID ? $data['invoiceInfo']->gateway_name : '',
            'payment_status' => $data['invoiceInfo']->payment_status == PAYMENT_STATUS_PAID ? __('Paid') : __('Unpaid'),
            'payment_date' => $data['invoiceInfo']->updated_at->format('Y-m-d'),
        ];
    }
}
