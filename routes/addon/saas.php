
<?php

use App\Http\Controllers\Addon\Saas\Admin\PackageController;
use App\Http\Controllers\Addon\Saas\Admin\SubscriptionController;
use App\Http\Controllers\Addon\Saas\PaymentSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'admin']], function () {
    Route::group(['prefix' => 'subscription', 'as' => 'subscription.'], function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::get('get-package', [SubscriptionController::class, 'getPackage'])->name('get.package');
        Route::post('get-gateway', [SubscriptionController::class, 'getGateway'])->name('get.gateway');
        Route::get('get-currency-by-gateway', [SubscriptionController::class, 'getCurrencyByGateway'])->name('get.currency');
        Route::post('cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
    });
});

Route::group(['prefix' => 'super-admin', 'as' => 'super-admin.', 'middleware' => ['auth', 'sadmin']], function () {
    // packages
    Route::group(['prefix' => 'packages', 'as' => 'packages.'], function () {
        Route::get('/', [PackageController::class, 'index'])->name('index');
        Route::get('edit/{id}', [PackageController::class, 'edit'])->name('edit');
        Route::post('store', [PackageController::class, 'store'])->name('store');
        Route::post('destroy/{id}', [PackageController::class, 'destroy'])->name('destroy');
        Route::get('user', [PackageController::class, 'userPackage'])->name('user');
        Route::post('assign', [PackageController::class, 'assignPackage'])->name('assign');
        Route::get('get-info', [PackageController::class, 'getInfo'])->name('get.info');
    });

    Route::group(['prefix' => 'subscriptions', 'as' => 'subscriptions.'], function () {
        Route::get('orders', [SubscriptionController::class, 'orders'])->name('orders');
        Route::get('orders/get-info', [SubscriptionController::class, 'orderGetInfo'])->name('orders.get.info'); // ajax
        Route::post('orders/payment-status-change', [SubscriptionController::class, 'orderPaymentStatusChange'])->name('order.payment.status.change');
        Route::get('orders-payment-status', [SubscriptionController::class, 'ordersStatus'])->name('orders.payment.status'); // ajax
        Route::get('order-details/{id}', [SubscriptionController::class, 'orderDetails'])->name('order-details');
    });


});

Route::group(['prefix' => 'payment'], function () {
    Route::post('/subscription/checkout', [PaymentSubscriptionController::class, 'checkout'])->name('payment.subscription.checkout');
    Route::match(array('GET', 'POST'), 'subscription/verify', [PaymentSubscriptionController::class, 'verify'])->name('payment.subscription.verify');
});

