<?php

use App\Http\Controllers\AddonUpdateController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ClientInvoiceController;
use App\Http\Controllers\Admin\ClientOrderController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\GatewayController;
use App\Http\Controllers\Admin\OrderFormController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\RolePermisionController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\VersionUpdateController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Superadmin\EmailTemplateController;
use App\Http\Controllers\Superadmin\SettingController;
use App\Models\Language;
use Illuminate\Support\Facades\Route;

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

Route::get('/local/{ln}', function ($ln) {
    $language = Language::where('iso_code', $ln)->first();
    if (!$language) {
        $language = Language::where('default', 1)->first();
        if ($language) {
            $ln = $language->iso_code;
        }
    }
    session()->put('local', $ln);
    return redirect()->back();
})->name('local');


Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('recent-open-order', [DashboardController::class, 'recentOpenOrder'])->name('recent-open-order');
Route::get('revenue-overview-chart-data', [DashboardController::class, 'revenueOverviewChartData'])->name('revenue-overview-chart-data');
Route::get('client-overview-chart-data', [DashboardController::class, 'clientOverviewChartData'])->name('client-overview-chart-data');

Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
    // setting start
    Route::get('application-settings', [SettingController::class, 'applicationSetting'])->name('application-settings');
    Route::post('application-settings-update', [SettingController::class, 'applicationSettingUpdate'])->name('application-settings.update');
    Route::get('configuration-settings', [SettingController::class, 'configurationSetting'])->name('configuration-settings');
    Route::get('configuration-settings/configure', [SettingController::class, 'configurationSettingConfigure'])->name('configuration-settings.configure');
    Route::get('configuration-settings/help', [SettingController::class, 'configurationSettingHelp'])->name('configuration-settings.help');
    Route::post('configuration-settings-update', [SettingController::class, 'configurationSettingUpdate'])->name('configuration-settings.update');
    Route::post('application-env-update', [SettingController::class, 'saveSetting'])->name('settings_env.update');
    Route::get('logo-settings', [SettingController::class, 'logoSettings'])->name('logo-settings');
    Route::get('storage-settings', [SettingController::class, 'storageSetting'])->name('storage.index');
    Route::post('storage-settings', [SettingController::class, 'storageSettingsUpdate'])->name('storage.update');
    Route::get('maintenance-mode-changes', [SettingController::class, 'maintenanceMode'])->name('maintenance');
    Route::post('maintenance-mode-changes', [SettingController::class, 'maintenanceModeChange'])->name('maintenance.change');

    Route::get('mail-configuration', [SettingController::class, 'mailConfiguration'])->name('mail-configuration');
    Route::post('mail-configuration', [SettingController::class, 'mailConfiguration'])->name('mail-configuration');
    Route::post('mail-test', [SettingController::class, 'mailTest'])->name('mail.test');
    // setting end

    Route::group(['prefix' => 'gateway', 'as' => 'gateway.'], function () {
        Route::get('/', [GatewayController::class, 'index'])->name('index');
        Route::post('store', [GatewayController::class, 'store'])->name('store')->middleware('isDemo');
        Route::get('edit/{id}', [GatewayController::class, 'edit'])->name('edit')->middleware('isDemo');
        Route::get('get-info', [GatewayController::class, 'getInfo'])->name('get.info');
        Route::get('get-currency-by-gateway', [GatewayController::class, 'getCurrencyByGateway'])->name('get.currency');
    });

    Route::group(['prefix' => 'role-permission', 'as' => 'role-permission.'], function () {
        Route::get('/', [RolePermisionController::class, 'list'])->name('list');
        Route::get('add-new', [RolePermisionController::class, 'addNew'])->name('add-new');
        Route::get('edit/{id}', [RolePermisionController::class, 'edit'])->name('edit');
        Route::post('store', [RolePermisionController::class, 'store'])->name('store');
        Route::get('details/{id}', [RolePermisionController::class, 'details'])->name('details');
        Route::post('delete/{id}', [RolePermisionController::class, 'delete'])->name('delete');
        Route::get('permission/{id}', [RolePermisionController::class, 'permission'])->name('permission');
        Route::post('permission-update', [RolePermisionController::class, 'permissionUpdate'])->name('permission-update');
    });

    // designation
    Route::group(['prefix' => 'designation', 'as' => 'designation.'], function () {
        Route::get('/', [DesignationController::class, 'index'])->name('index');
        Route::get('add', [DesignationController::class, 'add'])->name('add');
        Route::post('store', [DesignationController::class, 'store'])->name('store');
        Route::get('edit/{id}', [DesignationController::class, 'edit'])->name('edit');
        Route::get('delete/{id}', [DesignationController::class, 'delete'])->name('delete');
    });

    // coupon
    Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('add', [CouponController::class, 'add'])->name('add');
        Route::post('store', [CouponController::class, 'store'])->name('store');
        Route::get('edit/{id}', [CouponController::class, 'edit'])->name('edit');
        Route::get('delete/{id}', [CouponController::class, 'delete'])->name('delete');
    });

    Route::get('email-template', [EmailTemplateController::class, 'emailTemplate'])->name('email-template');
    Route::get('email-template-config', [EmailTemplateController::class, 'emailTemplateConfig'])->name('email.template.config');
    Route::post('email-template-config-update', [EmailTemplateController::class, 'emailTemplateConfigUpdate'])->name('email.template.config.update');


    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('update', [ProfileController::class, 'update'])->name('update')->middleware('isDemo');
        Route::get('password', [ProfileController::class, 'password'])->name('password');
        Route::post('password-update', [ProfileController::class, 'passwordUpdate'])->name('password.update')->middleware('isDemo');
    });
});

Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
    Route::get('notification-mark-all-as-read', [NotificationController::class, 'notificationMarkAllAsRead'])->name('notification-mark-all-as-read');
    Route::get('view/{id}', [NotificationController::class, 'notificationView'])->name('view');
    Route::get('notification-mark-as-read/{id}', [NotificationController::class, 'notificationMarkAsRead'])->name('notification-mark-as-read');
});

Route::group(['prefix' => 'services', 'as' => 'services.'], function () {
    Route::get('/', [ServiceController::class, 'list'])->name('list');
    Route::get('add-new', [ServiceController::class, 'addNew'])->name('add-new');
    Route::get('edit/{id}', [ServiceController::class, 'edit'])->name('edit');
    Route::post('store', [ServiceController::class, 'store'])->name('store');
    Route::get('details/{id}', [ServiceController::class, 'details'])->name('details');
    Route::get('delete', [ServiceController::class, 'delete'])->name('delete');
    Route::get('search', [ServiceController::class, 'search'])->name('search');
});

Route::group(['prefix' => 'team-member', 'as' => 'team-member.'], function () {
    Route::get('/', [TeamMemberController::class, 'index'])->name('index');
    Route::get('add', [TeamMemberController::class, 'add'])->name('add');
    Route::post('store', [TeamMemberController::class, 'store'])->name('store');
    Route::get('edit/{id}', [TeamMemberController::class, 'edit'])->name('edit');
    Route::get('delete/{id}', [TeamMemberController::class, 'delete'])->name('delete');
});

Route::group(['prefix' => 'order-form', 'as' => 'order-form.'], function () {
    Route::get('/', [OrderFormController::class, 'index'])->name('index');
    Route::get('add', [OrderFormController::class, 'add'])->name('add');
    Route::post('store', [OrderFormController::class, 'store'])->name('store');
    Route::get('edit/{id}', [OrderFormController::class, 'edit'])->name('edit');
    Route::get('delete/{id}', [OrderFormController::class, 'delete'])->name('delete');
});

//client
Route::group(['prefix' => 'client', 'as' => 'client.'], function () {
    Route::get('/', [ClientController::class, 'list'])->name('list');
    Route::get('client-add', [ClientController::class, 'add'])->name('add-list');
    Route::post('client-store', [ClientController::class, 'store'])->name('store');
    Route::post('client-delete/{id}', [ClientController::class, 'delete'])->name('delete');
    Route::get('edit/{id}', [ClientController::class, 'edit'])->name('edit');
    Route::get('details/{id}', [ClientController::class, 'details'])->name('details');
    Route::get('invoice/{id}', [ClientController::class, 'clientInvoiceHistory'])->name('invoice');
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

//client invoice
Route::group(['prefix' => 'client-invoice', 'as' => 'client-invoice.'], function () {
    Route::get('/', [ClientInvoiceController::class, 'list'])->name('list');
    Route::get('add-new', [ClientInvoiceController::class, 'addNew'])->name('add-new');
    Route::post('store', [ClientInvoiceController::class, 'store'])->name('store');
    Route::post('delete/{id}', [ClientInvoiceController::class, 'delete'])->name('delete');
    Route::get('all-service', [ClientInvoiceController::class, 'getService'])->name('all-service');
    Route::get('details/{id}', [ClientInvoiceController::class, 'details'])->name('details');
    Route::get('edit/{id}', [ClientInvoiceController::class, 'edit'])->name('edit');
    Route::get('order', [ClientInvoiceController::class, 'getOrder'])->name('order');
    Route::get('print/{id}', [ClientInvoiceController::class, 'invoicePrint'])->name('print');
    Route::get('payment-edit/{id}', [ClientInvoiceController::class, 'paymentEdit'])->name('payment-edit');
});

//client-order-info
Route::group(['prefix' => 'client-orders', 'as' => 'client-orders.'], function () {
    Route::get('/', [ClientOrderController::class, 'list'])->name('list');
    Route::get('add', [ClientOrderController::class, 'add'])->name('add');
    Route::get('all-service', [ClientOrderController::class, 'getService'])->name('all-service');
    Route::post('store', [ClientOrderController::class, 'store'])->name('store');
    Route::get('edit/{id}', [ClientOrderController::class, 'edit'])->name('edit');
    Route::post('delete/{id}', [ClientOrderController::class, 'delete'])->name('delete');
    Route::get('details/{id}', [ClientOrderController::class, 'details'])->name('details');
    Route::post('conversation', [ClientOrderController::class, 'conversationStore'])->name('conversation.store');
    Route::get('status-change/{order_id}/{status}', [ClientOrderController::class, 'statusChange'])->name('status.change');
    Route::get('assign-member', [ClientOrderController::class, 'assignMember'])->name('assign.member');
    Route::post('note-store', [ClientOrderController::class, 'noteStore'])->name('note.store');
    Route::post('note-delete/{id}', [ClientOrderController::class, 'noteDelete'])->name('note.delete');
});

// quotation route
Route::group(['prefix' => 'quotation', 'as' => 'quotation.'], function () {
    Route::get('/', [QuotationController::class, 'list'])->name('list');
    Route::post('store', [QuotationController::class, 'store'])->name('store');
    Route::get('add', [QuotationController::class, 'add'])->name('add');
    Route::get('edit/{id}', [QuotationController::class, 'edit'])->name('edit');
    Route::get('all-service', [QuotationController::class, 'getService'])->name('all-service');
    Route::get('delete/{id}', [QuotationController::class, 'delete'])->name('delete');
    Route::get('details/{id}', [QuotationController::class, 'details'])->name('details');
    Route::get('print/{id}', [QuotationController::class, 'quotationPrint'])->name('print');
    Route::get('send/{id}', [QuotationController::class, 'quotationSend'])->name('send');
});


Route::get('version-update', [VersionUpdateController::class, 'versionFileUpdate'])->name('file-version-update');
Route::post('version-update', [VersionUpdateController::class, 'versionFileUpdateStore'])->name('file-version-update-store');
Route::get('version-update-execute', [VersionUpdateController::class, 'versionUpdateExecute'])->name('file-version-update-execute');
Route::get('version-delete', [VersionUpdateController::class, 'versionFileUpdateDelete'])->name('file-version-delete');

Route::group(['prefix' => 'addon', 'as' => 'addon.'], function () {
    Route::get('details/{code}', [AddonUpdateController::class, 'addonSaasDetails'])->name('details')->withoutMiddleware(['addon.update']);
    Route::post('store', [AddonUpdateController::class, 'addonSaasFileStore'])->name('store')->withoutMiddleware(['addon.update']);
    Route::post('execute', [AddonUpdateController::class, 'addonSaasFileExecute'])->name('execute')->withoutMiddleware(['addon.update']);
    Route::get('delete/{code}', [AddonUpdateController::class, 'addonSaasFileDelete'])->name('delete')->withoutMiddleware(['addon.update']);
});
