<?php

use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\ClientInvoiceController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ServiceController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\InvoiceController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('order-summery', [DashboardController::class, 'orderSummery'])->name('order-summery');

//notification  route start
Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
    Route::get('all', [NotificationController::class, 'allNotification'])->name('all');
    Route::get('mark-as-read', [NotificationController::class, 'notificationMarkAsRead'])->name('mark-as-read');
    Route::get('view/{id}', [NotificationController::class, 'notificationView'])->name('view');
    Route::get('delete/{id}', [NotificationController::class, 'notificationDelete'])->name('delete');

    Route::get('notification-mark-all-as-read', [NotificationController::class, 'notificationMarkAllAsRead'])->name('notification-mark-all-as-read');
    Route::get('notification-mark-as-read/{id}', [NotificationController::class, 'notificationMarkAsRead'])->name('notification-mark-as-read');
});
// notification route end

//invoice route start
Route::group(['prefix' => 'invoice', 'as' => 'invoice.'], function () {
    Route::get('list/{plan_id?}', [InvoiceController::class, 'list'])->name('list');
    Route::get('view', [InvoiceController::class, 'invoiceView'])->name('view');
    Route::get('get-plan-data', [InvoiceController::class, 'getPlanData'])->name('get.plan.data');
    Route::get('print/{id}', [InvoiceController::class, 'invoicePrint'])->name('print');
    Route::get('download/{id}', [InvoiceController::class, 'invoiceDownload'])->name('download');
});
//invoice route end

// order route start
Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
//    Route::get('/', [OrderController::class, 'index'])->name('payment.status');
//    Route::get('payment-show/{id}', [OrderController::class, 'paymentShow'])->name('payment.show');
//    Route::get('payment-edit/{id}', [OrderController::class, 'paymentEdit'])->name('payment.edit');
//    Route::post('payment-status-update', [OrderController::class, 'paymentUpdate'])->name('payment.status.update');
//    Route::get('sales', [OrderController::class, 'sales'])->name('sales');
    Route::get('/', [OrderController::class, 'list'])->name('list');
    Route::get('details/{id}', [OrderController::class, 'details'])->name('details');
    Route::post('conversation', [OrderController::class, 'conversationStore'])->name('conversation.store');
});
// order route end

Route::group(['prefix' => 'services', 'as' => 'services.'], function () {
    Route::get('/', [ServiceController::class, 'list'])->name('list');
    Route::get('details/{id}', [ServiceController::class, 'details'])->name('details');
    Route::get('search', [ServiceController::class, 'search'])->name('search');
});

// client-profile
Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::post('update', [ProfileController::class, 'update'])->name('update')->middleware('isDemo');
    Route::get('password', [ProfileController::class, 'password'])->name('password');
    Route::post('password-update', [ProfileController::class, 'passwordUpdate'])->name('password.update')->middleware('isDemo');
});

Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
    Route::get('/', [TicketController::class, 'list'])->name('list');
    Route::get('add-new', [TicketController::class, 'addNew'])->name('add-new');
    Route::get('edit/{id}', [TicketController::class, 'edit'])->name('edit');
    Route::post('store', [TicketController::class, 'store'])->name('store');
    Route::get('details/{id}', [TicketController::class, 'details'])->name('details');
    Route::post('delete/{id}', [TicketController::class, 'delete'])->name('delete');
    Route::get('assign-member', [TicketController::class, 'assignMember'])->name('assign-member');
    Route::get('priority-change/{ticket_id}/{priority}', [TicketController::class, 'priorityChange'])->name('priority-change');
    Route::post('conversations-store', [TicketController::class, 'conversationsStore'])->name('conversations.store');
    Route::post('conversations-edit', [TicketController::class, 'conversationsEdit'])->name('conversations.edit');
    Route::post('conversations-delete/{id}', [TicketController::class, 'conversationsDelete'])->name('conversations.delete');
    Route::get('status-change', [TicketController::class, 'statusChange'])->name('status.change');
});

Route::post('gateway-list', [CheckoutController::class, 'gatewayList'])->name('gateway.list');
Route::get('currency-list', [CheckoutController::class, 'currencyList'])->name('currency.list');
Route::get('apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon');
Route::post('checkout/order', [CheckoutController::class, 'checkoutOrderPlace'])->name('checkout.order.place');

//client-order-info
Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {

});

// invoice route
Route::group(['prefix' => 'client-invoice', 'as' => 'client-invoice.'], function () {
    Route::get('/', [ClientInvoiceController::class, 'list'])->name('list');
    Route::get('details/{id}', [ClientInvoiceController::class, 'details'])->name('details');
    Route::get('print/{id}', [ClientInvoiceController::class, 'invoicePrint'])->name('print');
});
