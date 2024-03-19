<?php

use App\Http\Services\AffiliationService;
use App\Mail\CustomEmailNotify;
use App\Models\Coin;
use App\Models\Currency;
use App\Models\EmailTemplate;
use App\Models\FileManager;
use App\Models\Hardware;
use App\Models\Language;
use App\Models\Meta;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\Quotation;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\TicketConversation;
use App\Models\User;
use App\Models\UserPackage;
use Jenssegers\Agent\Agent;
use App\Models\UserActivityLog;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\UserWallet;
use App\Models\Webhook;
use App\Models\WebhookEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Mail\EmailNotify;
use App\Models\Chat;
use App\Models\ClientInvoice;
use App\Models\ClientOrder;
use App\Models\ClientOrderItem;
use App\Models\Gateway;
use App\Models\Package;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

if (!function_exists("getOption")) {
    function getOption($option_key, $default = NULL)
    {
        $system_settings = config('settings');

        if ($option_key && isset($system_settings[$option_key])) {
            return $system_settings[$option_key];
        } else {
            return $default;
        }
    }
}

function getSettingImage($option_key)
{

    if ($option_key && $option_key != null) {


        $setting = Setting::where('option_key', $option_key)->first();
        if (isset($setting->option_value) && isset($setting->option_value) != null) {

            $file = FileManager::select('path', 'storage_type')->find($setting->option_value);


            if (!is_null($file)) {
                if (Storage::disk($file->storage_type)->exists($file->path)) {

                    if ($file->storage_type == 'public') {
                        return asset('storage/' . $file->path);
                    }

                    return Storage::disk($file->storage_type)->url($file->path);
                }
            }
        }
    }
    return asset('assets/images/no-image.jpg');
}

function settingImageStoreUpdate($option_value, $requestFile)
{

    if ($requestFile) {

        /*File Manager Call upload*/
        if ($option_value && $option_value != null) {
            $new_file = FileManager::where('id', $option_value)->first();

            if ($new_file) {
                $new_file->removeFile();
                $uploaded = $new_file->upload('Setting', $requestFile, '', $new_file->id);
            } else {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('Setting', $requestFile);
            }
        } else {
            $new_file = new FileManager();
            $uploaded = $new_file->upload('Setting', $requestFile);
        }

        /*End*/

        return $uploaded->id;
    }

    return null;
}


if (!function_exists("getDefaultImage")) {
    function getDefaultImage()
    {
        // return asset('assets/images/no-image.jpg');
        return asset('assets/images/icon/upload-img-1.svg');
    }
}

if (!function_exists("activeIfMatch")) {
    function activeIfMatch($path)
    {
        if (auth::user()->is_admin()) {
            return Request::is($path . '*') ? 'mm-active' : '';
        } else {
            return Request::is($path . '*') ? 'active' : '';
        }
    }
}

if (!function_exists("activeIfFullMatch")) {
    function activeIfFullMatch($path)
    {
        if (auth::user()->is_admin()) {
            return Request::is($path) ? 'mm-active' : '';
        } else {
            return Request::is($path) ? 'active' : '';
        }
    }
}

if (!function_exists("openIfFullMatch")) {
    function openIfFullMatch($path)
    {
        return Request::is($path) ? 'has-open' : '';
    }
}


if (!function_exists("toastMessage")) {
    function toastMessage($message_type, $message)
    {
        Toastr::$message_type($message, '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-top-right']);
    }
}

if (!function_exists("getDefaultLanguage")) {
    function getDefaultLanguage()
    {
        $language = Language::where('default', STATUS_ACTIVE)->first();
        if ($language) {
            $iso_code = $language->iso_code;
            return $iso_code;
        }

        return 'en';
    }
}

if (!function_exists("getCurrencySymbol")) {
    function getCurrencySymbol()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        if ($currency) {
            $symbol = $currency->symbol;
            return $symbol;
        }

        return '';
    }
}

if (!function_exists("getIsoCode")) {
    function getIsoCode()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        if ($currency) {
            $currency_code = $currency->currency_code;
            return $currency_code;
        }

        return '';
    }
}

if (!function_exists("getCurrencyPlacement")) {
    function getCurrencyPlacement()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        $placement = 'before';
        if ($currency) {
            $placement = $currency->symbol;
            return $placement;
        }

        return $placement;
    }
}

if (!function_exists("showPrice")) {
    function showPrice($price)
    {
        $price = getNumberFormat($price);
        if (config('app.currencyPlacement') == 'after') {
            return $price . config('app.currencySymbol');
        } else {
            return config('app.currencySymbol') . $price;
        }
    }
}


if (!function_exists("getNumberFormat")) {
    function getNumberFormat($amount)
    {
        return number_format($amount, 2, '.', '');
    }
}

if (!function_exists("decimalToInt")) {
    function decimalToInt($amount)
    {
        return number_format(number_format($amount, 2, '.', '') * 100, 0, '.', '');
    }
}

if (!function_exists("intToDecimal")) {
}
function intToDecimal($amount)
{
    return number_format($amount / 100, 2, '.', '');
}

if (!function_exists("appLanguages")) {
    function appLanguages()
    {
        return Language::where('status', 1)->get();
    }
}

if (!function_exists("selectedLanguage")) {
    function selectedLanguage()
    {
        $language = Language::where('iso_code', session()->get('local'))->first();
        if (!$language) {
            $language = Language::find(1);
            if ($language) {
                $ln = $language->iso_code;
                session(['local' => $ln]);
                App::setLocale(session()->get('local'));
            }
        }

        return $language;
    }
}

if (!function_exists("getVideoFile")) {
    function getFile($path, $storageType)
    {
        if (!is_null($path)) {
            if (Storage::disk($storageType)->exists($path)) {

                if ($storageType == 'public') {
                    return asset('storage/' . $path);
                }

                if ($storageType == 'wasabi') {
                    return Storage::disk('wasabi')->url($path);
                }


                return Storage::disk($storageType)->url($path);
            }
        }

        return asset('assets/images/no-image.jpg');
    }
}

if (!function_exists("notificationForUser")) {
    function notificationForUser()
    {
        $instructor_notifications = \App\Models\Notification::where('user_id', auth()->user()->id)->where('user_type', 2)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->get();
        $student_notifications = \App\Models\Notification::where('user_id', auth()->user()->id)->where('user_type', 3)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->get();
        return array('instructor_notifications' => $instructor_notifications, 'student_notifications' => $student_notifications);
    }
}

if (!function_exists("adminNotifications")) {
    function adminNotifications()
    {
        return \App\Models\Notification::where('user_type', 1)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->paginate(5);
    }
}

if (!function_exists('getSlug')) {
    function getSlug($text)
    {
        if ($text) {
            $data = preg_replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", "", $text);
            $slug = preg_replace("/[\/_|+ -]+/", "-", $data);
            return $slug;
        }
        return '';
    }
}


if (!function_exists('getCustomerCurrentBuildVersion')) {
    function getCustomerCurrentBuildVersion()
    {
        $buildVersion = getOption('build_version');

        if (is_null($buildVersion)) {
            return 1;
        }

        return (int)$buildVersion;
    }
}

if (!function_exists('setCustomerBuildVersion')) {
    function setCustomerBuildVersion($version)
    {
        $option = Setting::firstOrCreate(['option_key' => 'build_version']);
        $option->option_value = $version;
        $option->save();
    }
}

if (!function_exists('setCustomerCurrentVersion')) {
    function setCustomerCurrentVersion()
    {
        $option = Setting::firstOrCreate(['option_key' => 'current_version']);
        $option->option_value = config('app.current_version');
        $option->save();
    }
}




if (!function_exists('getAddonCodeBuildVersion')) {
    function getAddonCodeBuildVersion($appCode)
    {
        Artisan::call("config:clear");
        return config('addon.' . $appCode . '.build_version', 0);
    }
}

if (!function_exists('getCustomerAddonBuildVersion')) {
    function getCustomerAddonBuildVersion($code)
    {
        $buildVersion = getOption($code . '_build_version', 0);
        if (is_null($buildVersion)) {
            return 0;
        }
        return (int)$buildVersion;
    }
}

if (!function_exists('isAddonInstalled')) {
    function isAddonInstalled($code)
    {
        $buildVersion = getOption($code . '_build_version', 0);
        $codeBuildVersion = config('addon.' . $code . '.build_version', 0);
        if (is_null($buildVersion) || $codeBuildVersion == 0) {
            return 0;
        }
        return (int)$buildVersion;
    }
}

if (!function_exists('setCustomerAddonCurrentVersion')) {
    function setCustomerAddonCurrentVersion($code)
    {
        $option = Setting::firstOrCreate(['option_key' => $code . '_current_version']);
        if (config($code . '.current_version', 0) > 0) {
            $option->option_value = config($code . '.current_version', 0);
            $option->save();
        }
    }
}

if (!function_exists('setCustomerAddonBuildVersion')) {
    function setCustomerAddonBuildVersion($code, $version)
    {
        $option = Setting::firstOrCreate(['option_key' => $code . '_build_version']);
        $option->option_value = $version;
        $option->save();
    }
}

if (!function_exists('getDomainName')) {
    function getDomainName($url)
    {
        $parseUrl = parse_url(trim($url));
        if (isset($parseUrl['host'])) {
            $host = $parseUrl['host'];
        } else {
            $path = explode('/', $parseUrl['path']);
            $host = $path[0];
        }
        return trim($host);
    }
}

if (!function_exists('updateEnv')) {
    function updateEnv($values)
    {
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                setEnvironmentValue($envKey, $envValue);
            }
            return true;
        }
    }
}

if (!function_exists('setEnvironmentValue')) {
    function setEnvironmentValue($envKey, $envValue)
    {
        try {
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");
            if ($keyPosition) {
                if (PHP_OS_FAMILY === 'Windows') {
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                } else {
                    $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
                }
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                $envValue = str_replace(chr(92), "\\\\", $envValue);
                $envValue = str_replace('"', '\"', $envValue);
                $newLine = "{$envKey}=\"{$envValue}\"";
                if ($oldLine != $newLine) {
                    $str = str_replace($oldLine, $newLine, $str);
                    $str = substr($str, 0, -1);
                    $fp = fopen($envFile, 'w');
                    fwrite($fp, $str);
                    fclose($fp);
                }
            } else if (strtoupper($envKey) == $envKey) {
                $envValue = str_replace(chr(92), "\\\\", $envValue);
                $envValue = str_replace('"', '\"', $envValue);
                $newLine = "{$envKey}=\"{$envValue}\"\n";
                $str .= $newLine;
                $str = substr($str, 0, -1);
                $fp = fopen($envFile, 'w');
                fwrite($fp, $str);
                fclose($fp);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('base64urlEncode')) {
    function base64urlEncode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
}

if (!function_exists('getTimeZone')) {
    function getTimeZone()
    {
        return DateTimeZone::listIdentifiers(
            DateTimeZone::ALL
        );
    }
}

function getErrorMessage($e, $customMsg = null)
{
    if ($customMsg != null) {
        return $customMsg;
    }
    if (env('APP_DEBUG')) {
        return $e->getMessage() . $e->getLine();
    } else {
        return SOMETHING_WENT_WRONG;
    }
}

if (!function_exists('getFileUrl')) {
    function getFileUrl($id = null)
    {

        $file = FileManager::select('path', 'storage_type')->find($id);

        if (!is_null($file)) {
            if (Storage::disk($file->storage_type)->exists($file->path)) {

                if ($file->storage_type == 'public') {
                    return asset('storage/' . $file->path);
                }

                if ($file->storage_type == 'wasabi') {
                    return Storage::disk('wasabi')->url($file->path);
                }


                return Storage::disk($file->storage_type)->url($file->path);
            }
        }

        return asset('assets/images/no-image.jpg');
    }
}

if (!function_exists('getFileData')) {
    function getFileData($id, $property)
    {
        $file = FileManager::find($id);
        if ($file) {
            return $file->{$property};
        }
        return null;
    }
}

if (!function_exists('emailTemplateStatus')) {
    function emailTemplateStatus($category)
    {
        $status = EmailTemplate::where('category', $category)->where('user_id', auth()->id())->pluck('status')->first();
        if ($status) {
            return $status;
        }
        return DEACTIVATE;
    }
}


if (!function_exists('languageLocale')) {
    function languageLocale($locale)
    {
        $data = Language::where('code', $locale)->first();
        if ($data) {
            return $data->code;
        }
        return 'en';
    }
}


if (!function_exists('getUseCase')) {
    function getUseCase($useCase = [])
    {
        if (in_array("-1", $useCase)) {
            return __("All");
        }
        return count($useCase);
    }
}

function currentCurrency($attribute = '')
{
    $currentCurrency = Currency::where('current_currency', 1)->first();
    if (isset($currentCurrency->{$attribute})) {
        return $currentCurrency->{$attribute};
    }
    return '';
}

function getPairInfo($base_coin_id, $trade_coin_id, $property)
{
    $base_coin = Coin::where('id', $base_coin_id)->first();
    $trade_coin = Coin::where('id', $trade_coin_id)->first();


    if ($property == 'pare_name') {
        return $trade_coin->full_name . '/' . $base_coin->full_name;
    }
    if ($property == 'base_coin_name') {
        return $base_coin->full_name;
    }

    if ($property == 'trade_coin_name') {
        return $trade_coin->full_name;
    }

    if ($property == 'base_coin_price') {
        return convertCurrency(1, $base_coin->coin_type, $trade_coin->coin_type)['price'];
    }

    if ($property == 'trade_coin_price') {
    }
}

function currentCurrencyType()
{
    $currentCurrency = Currency::where('current_currency', 1)->first();
    return $currentCurrency->currency_code;
}

function currentCurrencyIcon()
{
    $currentCurrency = Currency::where('current_currency', 1)->first();
    return $currentCurrency->symbol;
}

function totalBlance()
{

    $userWallet = UserWallet::leftJoin('coins', 'user_wallets.coin_id', '=', 'coins.id')
        ->where('user_wallets.user_id', auth()->id())
        ->select([
            'user_wallets.id as wallet_id',
            'user_wallets.user_id',
            'user_wallets.balance',
            'user_wallets.balance_referral',
            'user_wallets.address',
            'coins.*'
        ])
        ->get();

    $order = 0;
    $blance = 0;

    foreach ($userWallet as $wallet) {
        $blance += convertCurrency($wallet->balance, currentCurrencyType(), $wallet->coin_type)["total"];
    }


    $blance = $blance + $order;

    return $blance;
}

function userWalletById($id = '')
{

    $userWallet = UserWallet::leftJoin('coins', 'user_wallets.coin_id', '=', 'coins.id')
        ->where('user_wallets.id', $id)
        ->select([
            'user_wallets.id as wallet_id',
            'user_wallets.user_id',
            'user_wallets.balance',
            'user_wallets.balance_referral',
            'user_wallets.address',
            'coins.*'
        ])
        ->get();

    return $userWallet;
}


// Convert currency
function convertCurrency($amount, $to = 'USD', $from = 'USD')
{
    //1-BTC-GBP
    try {
        $jsondata = "";

        $coinPriceInCurrency = Setting::where('option_key', 'COIN_PRICE_IN_CURRENCY_FOR' . $from)->first();


        if ($coinPriceInCurrency != null) {

            if ($coinPriceInCurrency->option_value == null) {
                $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
                $json = file_get_contents($url); //,FALSE,$ctx);
                $jsondata = json_decode($json, TRUE);

                $coinPriceInCurrency->option_value = $jsondata[$to];
                $coinPriceInCurrency->save();
            }

            $dateTime = Carbon::now()->addMinute(5);
            $currentTime = $dateTime->format('Y-m-d H:i:s');


            if (($coinPriceInCurrency->option_value != null) && (date('Y-m-d H:i:s', strtotime($coinPriceInCurrency->updated_at)) < $currentTime)) {
                $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
                $json = file_get_contents($url); //,FALSE,$ctx);
                $jsondata = json_decode($json, TRUE);

                $coinPriceInCurrency->option_value = $jsondata[$to];
                $coinPriceInCurrency->save();
            }
        } else {

            $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
            $json = file_get_contents($url); //,FALSE,$ctx);
            $jsondata = json_decode($json, TRUE);

            if ($jsondata != null) {
                $newObj = new Setting();
                $newObj->option_key = 'COIN_PRICE_IN_CURRENCY_FOR' . $from;
                $newObj->option_value = $jsondata[$to];
                $newObj->save();
            }
        }


        return [
            'total' => $amount * getOption('COIN_PRICE_IN_CURRENCY_FOR' . $from),
            'price' => getOption('COIN_PRICE_IN_CURRENCY_FOR' . $from)
        ];
    } catch (\Exception $e) {
        return [
            'total' => 0.00000000,
            'price' => 0.00000000
        ];
    }
}


function convertCurrencySwap($amount, $to = 'USD', $from = 'USD')
{
    try {
        $jsondata = "";

        $coinPriceInCurrency = Setting::where('option_key', 'COIN_PRICE_IN_CURRENCY_FOR' . $from)->first();
        if ($coinPriceInCurrency != null) {

            if ($coinPriceInCurrency->option_value == null) {
                $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
                $json = file_get_contents($url); //,FALSE,$ctx);
                $jsondata = json_decode($json, TRUE);

                $coinPriceInCurrency->option_value = $jsondata[$to];
                $coinPriceInCurrency->save();
            }

            $dateTime = Carbon::now()->addMinute(5);
            $currentTime = $dateTime->format('Y-m-d H:i:s');

            if (($coinPriceInCurrency->option_value != null) && (date('Y-m-d H:i:s', strtotime($coinPriceInCurrency->updated_at)) < $currentTime)) {
                $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
                $json = file_get_contents($url); //,FALSE,$ctx);
                $jsondata = json_decode($json, TRUE);

                $coinPriceInCurrency->option_value = $jsondata[$to];
                $coinPriceInCurrency->save();
            }
        } else {

            $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
            $json = file_get_contents($url); //,FALSE,$ctx);
            $jsondata = json_decode($json, TRUE);

            if ($jsondata != null) {
                $newObj = new Setting();
                $newObj->option_key = 'COIN_PRICE_IN_CURRENCY_FOR' . $from;
                $newObj->option_value = $jsondata[$to];
                $newObj->save();
            }
        }

        return [
            'total' => $amount * getOption('COIN_PRICE_IN_CURRENCY_FOR' . $from),
            'price' => getOption('COIN_PRICE_IN_CURRENCY_FOR' . $from)
        ];
    } catch (\Exception $e) {
        return [
            'total' => 0.00000000,
            'price' => 0.00000000
        ];
    }
}

function random_strings($length_of_string)
{
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($str_result), 0, $length_of_string);
}

function makeTenantId()
{
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($str_result), 0, 8);
}

function broadcastPrivate($eventName, $broadcastData, $userId)
{
    //    $channelName = 'private-'.env("PUSHER_PRIVATE_CHANEL_NAME").'.' . customEncrypt($userId);
    //    dispatch(new BroadcastJob($channelName, $eventName, $broadcastData))->onQueue('broadcast-data');
}

function getUserId()
{
    try {
        return Auth::id();
    } catch (\Exception $e) {
        return 0;
    }
}


if (!function_exists('visual_number_format')) {
    function visual_number_format($value)
    {
        if (is_integer($value)) {
            return number_format($value, 2, '.', '');
        } elseif (is_string($value)) {
            $value = floatval($value);
        }
        $number = explode('.', number_format($value, 10, '.', ''));
        $intVal = (int)$value;
        if ($value > $intVal || $value < 0) {
            $intPart = $number[0];
            $floatPart = substr($number[1], 0, 8);
            $floatPart = rtrim($floatPart, '0');
            if (strlen($floatPart) < 2) {
                $floatPart = substr($number[1], 0, 2);
            }
            return $intPart . '.' . $floatPart;
        }
        return $number[0] . '.' . substr($number[1], 0, 2);
    }
}

function getError($e)
{
    if (env('APP_DEBUG')) {
        return " => " . $e->getMessage();
    }
    return '';
}

function notification($title = null, $body = null, $user_id = null, $link = null)
{
    try {
        $obj = new Notification();
        $obj->title = $title;
        $obj->body = $body;
        $obj->user_id = $user_id;
        $obj->link = $link;
        $obj->save();
        return "notification sent!";
    } catch (\Exception $e) {
        return "something error!";
    }
}

if (!function_exists('get_default_language')) {
    function get_default_language()
    {
        $language = Language::where('default', STATUS_ACTIVE)->first();
        if ($language) {
            $iso_code = $language->iso_code;
            return $iso_code;
        }

        return 'en';
    }
}

if (!function_exists('get_currency_symbol')) {
    function get_currency_symbol()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        if ($currency) {
            $symbol = $currency->symbol;
            return $symbol;
        }

        return '';
    }
}

if (!function_exists('get_currency_code')) {
    function get_currency_code()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        if ($currency) {
            $currency_code = $currency->currency_code;
            return $currency_code;
        }

        return '';
    }
}

if (!function_exists('get_currency_placement')) {
    function get_currency_placement()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        $placement = 'before';
        if ($currency) {
            $placement = $currency->currency_placement;
            return $placement;
        }

        return $placement;
    }
}


function getapisetting($coin_type, $property)
{
    $coin = Coin::join('api_settings', 'coins.id', '=', 'api_settings.coin_id')
        ->where('coins.coin_type', $coin_type)
        ->first([
            'coins.coin_type',
            'api_settings.*'
        ]);

    //    $coin = Coin::where('coin_type',$coin_type)->first();
    return $coin->{$property};
}

if (!function_exists('customNumberFormat')) {
    function customNumberFormat($value)
    {
        $number = explode('.', $value);
        if (!isset($number[1])) {
            return number_format($value, 8, '.', '');
        } else {
            $result = substr($number[1], 0, 8);
            if (strlen($result) < 8) {
                $result = number_format($value, 8, '.', '');
            } else {
                $result = $number[0] . "." . $result;
            }

            return $result;
        }
    }
}


if (!function_exists('calculateFees')) {
    function calculateFees($amount, $feeMethod, $feePercentage, $feeFixed)
    {
        try {
            if ($feeMethod == 1) {
                return customNumberFormat($feeFixed);
            } elseif ($feeMethod == 2) {
                return customNumberFormat(bcdiv(bcmul($feePercentage, $amount), 100));
            } elseif ($feeMethod == 3) {
                return customNumberFormat(bcadd($feeFixed, bcdiv(bcmul($feePercentage, $amount), 100)));
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }
}

if (!function_exists('excluded_user')) {
    function excluded_user($param = null)
    {
        if ($param == null) {
            return ExcludedUser::all('user_id');
        }
        $userId = ExcludedUser::pluck('user_id')->toArray();

        return $userId;
    }
}

if (!function_exists('trade_max_level')) {
    function trade_max_level()
    {
        return 5;
    }
}

if (!function_exists('reviewStar')) {
    function reviewStar($star)
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i > $star) {
                $html .= '<li class="no-rating"><i class="fa-solid fa-star"></i></li>';
            } else {
                $html .= '<li><i class="fa-solid fa-star"></i></li>';
            }
        }
        return $html;
    }
}

if (!function_exists('getExistingClients')) {
    function getExistingClients($userId = null)
    {
        $userId = is_null($userId) ? auth()->id() : $userId;
        $userPackage = userCurrentPackage($userId);

        if (is_null($userPackage)) {
            return 0;
        } else {
            $totalCount = User::query()
                ->where('created_by', $userId)
                ->where('role', USER_ROLE_CLIENT)
                ->count();
            return $totalCount;
        }
    }
}

if (!function_exists('getExistingOrders')) {
    function getExistingOrders($userId = null)
    {
        $userId = is_null($userId) ? auth()->id() : $userId;
        $userPackage = userCurrentPackage($userId);

        if (is_null($userPackage)) {
            return 0;
        } else {
            $totalCount = ClientOrder::query()
                ->where(['user_id' => $userId])
                ->count();
            return $totalCount;
        }
    }
}

if (!function_exists('userCurrentPackage')) {
    function userCurrentPackage($userId)
    {
        return UserPackage::query()
            ->where('status', ACTIVE)
            ->where('user_id', $userId)
            ->whereDate('end_date', '>=', now()->toDateTimeString())
            ->first();
    }
}

if (!function_exists('getPackageOtherFields')) {
    function getPackageOtherFields($userId = null)
    {
        $userId = is_null($userId) ? auth()->id() : $userId;
        $userPackage = userCurrentPackage($userId);

        if (is_null($userPackage)) {
            return [];
        } else {
            $package = Package::find($userPackage->package_id);
            return json_decode($package->others);
        }
    }
}

if (!function_exists('getPerCoinRate')) {
    function getPerCoinRate($coin_type)
    {
        return convertCurrencySwap(1, $coin_type, currentCurrency('currency_code'))["price"];
    }
}


if (!function_exists('allsetting')) {
    function allsetting($keys = null)
    {

        if ($keys && is_array($keys)) {
            $settings = Setting::whereIn('option_key', $keys)->pluck('option_value', 'option_key')->toArray();
            $settingsNotFoundInDB = array_fill_keys(array_diff($keys, array_keys($settings)), false);
            if (!empty($settingsNotFoundInDB)) {
                $settings = array_merge($settings, $settingsNotFoundInDB);
            }
            return $settings;
        } elseif ($keys && is_string($keys)) {
            $setting = Setting::where('option_key', $keys)->first();
            return empty($setting) ? false : $setting->value;
        }
        return Setting::pluck('option_value', 'option_key')->toArray();
    }
}


if (!function_exists('createTransaction')) {
    function createTransaction($user_id, $user_wallet_id, $amount, $post_balance, $type, $reference_id, $details)
    {
        $transaction = Transaction::create([
            'transaction_id' => Str::uuid()->getHex(),
            'user_wallet_id' => $user_wallet_id,
            'user_id' => $user_id,
            'reference_id' => $reference_id,
            'amount' => $amount,
            'post_balance' => $post_balance,
            'details' => $details,
            'type' => $type
        ]);

        if (TRANSACTION_TYPE_MINING) {
            $affiliationService = new AffiliationService;
            $affiliationService->storeAffiliationHistory($transaction);
        }
    }
}

if (!function_exists('getRandomDecimal')) {
    function getRandomDecimal($min, $max, $probabilityRatio)
    {
        // Calculate the adjusted maximum value based on the probability ratio
        $adjustedMax = $max + ($max - $min) * ($probabilityRatio - 1);

        // Generate a random decimal number within the range
        $randomDecimal = mt_rand($min * 10000, $adjustedMax * 10000) / 10000;

        // Check if the random decimal number needs to be adjusted
        if ($randomDecimal > $max) {
            // Set the number to the maximum value
            $randomDecimal = $max;
        }

        return $randomDecimal;
    }
}

if (!function_exists('getReturnAmountRange')) {
    function getReturnAmountRange($userMining)
    {
        if ($userMining->userPlan->plan->return_type == RETURN_TYPE_RANDOM) {

            if (!is_null($userMining->user_hardware_id)) {
                $allHardware = Hardware::where('status', STATUS_ACTIVE)->orderBy('speed', 'ASC')->get();
                $maxSpeed = $allHardware->max('speed');
                $hardwareRange = [];
                foreach ($allHardware as $hardware) {
                    $hardwareRange[$hardware->id] = ($hardware->speed / $maxSpeed);
                }

                $max = ($userMining->userPlan->plan->max_return_amount_per_day * $hardwareRange[$userMining->userHardware->hardware_id]);
                return ['min' => $userMining->userPlan->plan->min_return_amount_per_day, 'max' => $max];
            }

            return ['min' => $userMining->userPlan->plan->min_return_amount_per_day, 'max' => $userMining->userPlan->plan->min_return_amount_per_day];
        }

        return ['min' => $userMining->userPlan->plan->return_amount_per_day, 'max' => $userMining->userPlan->plan->return_amount_per_day];
    }
}

if (!function_exists('getPlanEarningEstimation')) {
    function getPlanEarningEstimation($plan)
    {
        if ($plan->return_type == RETURN_TYPE_FIXED) {
            return $plan->return_amount_per_day . ' ' . $plan->coin->coin_type;
        } elseif ($plan->return_type == RETURN_TYPE_RANDOM) {
            return $plan->min_return_amount_per_day . ' ' . $plan->coin->coin_type . '-' . $plan->max_return_amount_per_day . ' ' . $plan->coin->coin_type;
        }
    }
}

if (!function_exists('privateUserNotification')) {
    function privateUserNotification()
    {
        return Notification::where('user_id', Auth::id())
            ->where('status', ACTIVE)
            ->orderBy('id', 'DESC')
            ->where('view_status', STATUS_PENDING)
            ->get();
    }
}
if (!function_exists('publicUserNotification')) {
    function publicUserNotification()
    {
        return Notification::where('user_id', null)
            ->where('status', ACTIVE)
            ->orderBy('id', 'DESC')
            ->where('view_status', STATUS_PENDING)
            ->get();
    }
}

function get_clientIp()
{
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

function humanFileSize($size, $unit = '')
{
    if ((!$unit && $size >= 1 << 30) || $unit == 'GB') {
        return number_format($size / (1 << 30), 2) . 'GB';
    }

    if ((!$unit && $size >= 1 << 20) || $unit == 'MB') {
        return number_format($size / (1 << 20), 2) . 'MB';
    }

    if ((!$unit && $size >= 1 << 10) || $unit == 'KB') {
        return number_format($size / (1 << 10), 2) . 'KB';
    }

    return number_format($size) . ' bytes';
}

if (!function_exists('getMeta')) {
    function getMeta($slug)
    {
        $metaData = [
            'meta_title' => null,
            'meta_description' => null,
            'meta_keyword' => null,
            'og_image' => null,
        ];

        $meta = Meta::where('slug', $slug)->select([
            'meta_title',
            'meta_description',
            'meta_keyword',
            'og_image',
        ])->first();

        if (!is_null($meta)) {
            $metaData = $meta->toArray();
        } else {
            $meta = Meta::where('slug', 'default')->select([
                'meta_title',
                'meta_description',
                'meta_keyword',
                'og_image',
            ])->first();

            if (!is_null($meta)) {
                $metaData = $meta->toArray();
            }
        }

        $metaData['meta_title'] = $metaData['meta_title'] != NULL ? $metaData['meta_title'] : getOption('app_name');
        $metaData['meta_description'] = $metaData['meta_description'] != NULL ? $metaData['meta_description'] : getOption('app_name');
        $metaData['meta_keyword'] = $metaData['meta_keyword'] != NULL ? $metaData['meta_keyword'] : getOption('app_name');
        $metaData['og_image'] = $metaData['og_image'] != NULL ? getFileUrl($metaData['og_image']) : getFileUrl(getOption('app_logo'));

        return $metaData;
    }
}


function genericEmailNotify($singleData = null, $userData = null, $customData = null, $template = null)
{
    if ($singleData != null) {
        Mail::to($singleData->to)->send(new CustomEmailNotify($singleData, $userData, $customData, $template));
    } elseif ($userData != null) {
        Mail::to($userData->email)->send(new CustomEmailNotify($singleData, $userData, $customData, $template));
    }
}

function getEmailTemplate($category, $property, $link = null, $customData = null, $userData = null)
{
    $data = EmailTemplate::where('slug', $category)->first();
    if ($data && $data != null) {
        if ($property == 'body') {
            $body = $data->{$property};
            foreach (emailTempFields() as $key => $item) {
                if ($key == '{{reset_password_url}}') {
                    $body = str_replace($key, $link, $body);
                } else if ($key == '{{email_verify_url}}') {
                    $body = str_replace($key, $link, $body);
                } else if ($key == '{{order_id}}' && $customData != NULL) {
                    $body = str_replace($key, is_object($customData) ? $customData->order_id : $customData['order_id'], $body);
                } else if ($key == '{{ticket_id}}' && $customData != NULL) {
                    $body = str_replace($key, is_object($customData) ? $customData->ticket_id : $customData['ticket_id'], $body);
                } else if ($key == '{{username}}') {
                    $body = str_replace($key, $userData->name, $body);
                } else if ($key == '{{otp}}') {
                    $body = str_replace($key, $userData->otp, $body);
                } else {
                    $body = str_replace($key, $item, $body);
                }
            }
            return $body;
        } else if ($property == 'subject') {

            $subject = $data->{$property};
            foreach (emailTempFields() as $key => $item) {
                if ($key == '{{customField}}') {
                    $subject = str_replace($key, $customData->customField, $subject);
                }
            }
            return $subject;
        } else {
            return $data->{$property};
        }
    }
    return '';
}

function getEmailTemplateContent($body, $category = null, $customizedFieldsArray = [])
{
    if ($body) {
        $body = $body;
        if ($customizedFieldsArray) {
            foreach (emailTempFields($category) as $key => $item) {
                if (isset($customizedFieldsArray[$key])) {
                    $body = str_replace($key, $customizedFieldsArray[$key], $body);
                }
            }
        }
        return $body;
    }
    return '';
}

function getInvoiceSettingContent($content, $customizedFieldsArray = [])
{
    if ($content) {
        $content = $content;
        if ($customizedFieldsArray) {
            foreach (invoiceSettingFields() as $field) {
                if (isset($customizedFieldsArray[$field])) {
                    $content = str_replace($field, $customizedFieldsArray[$field], $content);
                }
            }
        }
        return $content;
    }
    return '';
}

function gatewaySettings()
{
    return '{"paypal":[{"label":"Url","name":"url","is_show":0},{"label":"Client ID","name":"key","is_show":1},{"label":"Secret","name":"secret","is_show":1}],"stripe":[{"label":"Url","name":"url","is_show":0},{"label":"Public Key","name":"key","is_show":0},{"label":"Secret Key","name":"secret","is_show":1}],"razorpay":[{"label":"Url","name":"url","is_show":0},{"label":"Key","name":"key","is_show":1},{"label":"Secret","name":"secret","is_show":1}],"instamojo":[{"label":"Url","name":"url","is_show":0},{"label":"Api Key","name":"key","is_show":1},{"label":"Auth Token","name":"secret","is_show":1}],"mollie":[{"label":"Url","name":"url","is_show":0},{"label":"Mollie Key","name":"key","is_show":1},{"label":"Secret","name":"secret","is_show":0}],"paystack":[{"label":"Url","name":"url","is_show":0},{"label":"Public Key","name":"key","is_show":1},{"label":"Secret Key","name":"secret","is_show":0}],"mercadopago":[{"label":"Url","name":"url","is_show":0},{"label":"Client ID","name":"key","is_show":1},{"label":"Client Secret","name":"secret","is_show":1}],"sslcommerz":[{"label":"Url","name":"url","is_show":0},{"label":"Store ID","name":"key","is_show":1},{"label":"Store Password","name":"secret","is_show":1}],"flutterwave":[{"label":"Hash","name":"url","is_show":1},{"label":"Public Key","name":"key","is_show":1},{"label":"Client Secret","name":"secret","is_show":1}],"coinbase":[{"label":"Hash","name":"url","is_show":0},{"label":"API Key","name":"key","is_show":1},{"label":"Client Secret","name":"secret","is_show":0}]}';
}

if (!function_exists('setUserGateway')) {
    function setUserGateway($tenantId, $userId = null)
    {
        $data = [
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Paypal', 'slug' => 'paypal', 'image' => 'assets/images/gateway-icon/paypal.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Stripe', 'slug' => 'stripe', 'image' => 'assets/images/gateway-icon/stripe.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Razorpay', 'slug' => 'razorpay', 'image' => 'assets/images/gateway-icon/razorpay.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Instamojo', 'slug' => 'instamojo', 'image' => 'assets/images/gateway-icon/instamojo.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Mollie', 'slug' => 'mollie', 'image' => 'assets/images/gateway-icon/mollie.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Paystack', 'slug' => 'paystack', 'image' => 'assets/images/gateway-icon/paystack.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Sslcommerz', 'slug' => 'sslcommerz', 'image' => 'assets/images/gateway-icon/sslcommerz.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Flutterwave', 'slug' => 'flutterwave', 'image' => 'assets/images/gateway-icon/flutterwave.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Mercadopago', 'slug' => 'mercadopago', 'image' => 'assets/images/gateway-icon/mercadopago.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Bank', 'slug' => 'bank', 'image' => 'assets/images/gateway-icon/bank.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Cash', 'slug' => 'cash', 'image' => 'assets/images/gateway-icon/cash.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
        ];
        Gateway::insert($data);
    }
}

if (!function_exists('setUserEmailTemplate')) {
    function setUserEmailTemplate($tenantId, $userId = null)
    {
        $data = [
            [
                'user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'ticket', 'title' => 'Ticket Create Notify For Client',
                'slug' => 'ticket-create-notify-for-client',
                'subject' => 'New Ticket Created - {{tracking_no}}',
                'body' =>   '<p><b>Dear</b> {{username}},</p>
                            <p>We are happy to inform you that a new ticket has been successfully created in our system with the following details:</p>
                            <p>Tracking No: <b>{{tracking_no}}</p>
                            <p>Date Created: {{ticket_created_time}}</p>
                            <p> Title: {{ticket_title}}</p>
                            <p>
                                You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal.
                                If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}}  or {{contact_phone}}.
                                Thank you for using our services!
                            </p>
                            <p><b>Best regards</b>, {{app_name}}</p>',
                'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()
            ],

            [
                'user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'ticket', 'title' => 'Ticket Create Notify For Admin',
                'slug' => 'ticket-create-notify-for-admin',
                'subject' => 'New Ticket Created - {{tracking_no}}',
                'body' => '<p><b>Dear</b> {{username}},            </p>
                            <p>We are happy to inform you that a new ticket has been successfully created in our system with the following details:            </p>
                            <p>Tracking No: <b>{{tracking_no}}</p>
                            <p>Date Created: {{ticket_created_time}}</p>
                            <p> Title: {{ticket_title}}</p>
                            <p>
                                You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal.
                                If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}}  or {{contact_phone}}.
                                Thank you for using our services!
                            </p>
                            <p><b>Best regards</b>, {{app_name}}</p>',
                'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()
            ],

            [
                'user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'ticket', 'title' => 'Ticket Create Notify For Team Member',
                'slug' => 'ticket-create-notify-for-team-member',
                'subject' => 'New Ticket Created - {{tracking_no}}',
                'body' => '<p><b>Dear</b> {{username}},</p>
                            <p>We are happy to inform you that a new ticket has been successfully created in our system with the following details:</p>
                            <p>Tracking No: <b>{{tracking_no}}</p>
                            <p>Date Created: {{ticket_created_time}}</p>
                            <p>Title: {{ticket_title}}</p>
                            <p>
                                You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal.
                                If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}}  or {{contact_phone}}.
                                Thank you for using our services!
                            </p>
                            <p> <b>Best regards</b>, {{app_name}} </p>',
                'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()
            ],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'ticket', 'title' => 'Ticket Conversation For Admin', 'slug' => 'ticket-conversation-for-admin', 'subject' => 'New Reply For Your Ticket -{{tracking_no}}', 'body' => '<div><span style="font-weight: bolder;">Dear</span>&nbsp;{{username}},</div><div><br></div><div>A new ticket has been created in our system. Ticket Tracking No: {{tracking_no}} with the following details:</div><div>Date Created: {{ticket_created_time}}&nbsp;</div><div><br></div><div>Title: {{ticket_title}}&nbsp;</div><div><br></div><div>Thank you for your attention.</div><div><span style="font-weight: bolder;">Best regards,</span>&nbsp;{{app_name}}</div>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'ticket', 'title' => 'Ticket Conversation For Team Member', 'slug' => 'ticket-conversation-for-team-member', 'subject' => 'New Reply For Your Ticket - {{tracking_no}}', 'body' => '<div><span style="font-weight: bolder;">Dear</span>&nbsp;{{username}},</div><div><br></div><div>We are happy to inform you that your Tracking No: {{tracking_no}} has reply in our system with the following details:&nbsp;</div><div>Date Created: {{ticket_created_time}}&nbsp;</div><div><br></div><div>Title: {{ticket_title}}&nbsp;</div><div><br></div><div>Thank you for your attention.</div><div><span style="font-weight: bolder;">Best regards</span>, {{app_name}}</div>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'ticket', 'title' => 'Ticket Conversation For Client', 'slug' => 'ticket-conversation-for-client', 'subject' => 'New Reply For Your Ticket - {{tracking_no}}', 'body' => '<div><span style="font-weight: bolder;">Dear</span>&nbsp;{{username}},</div><div><br></div><div>We are happy to inform you that your Tracking No: {{tracking_no}} has reply in our system with the following details:&nbsp;</div><div>Date Created: {{ticket_created_time}}&nbsp;</div><div><br></div><div>Title: {{ticket_title}}&nbsp;</div><div><br></div><div>You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal. If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}} or {{contact_phone}}. Thank you for using our services!</div><div><br></div><div><span style="font-weight: bolder;">Best regards</span>, {{app_name}}</div>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'ticket', 'title' => 'Ticket Status Change For Client', 'slug' => 'ticket-status-change-for-client', 'subject' => 'Ticket Status Changed - {{tracking_no}}', 'body' => '<div><span style="font-weight: bolder;">Dear</span>&nbsp;{{username}},</div><div><br></div><div>We are happy to inform you that your Tracking No: {{tracking_no}} has ticket status change in our system with the following details:&nbsp;</div><div>Date Created: {{ticket_created_time}}&nbsp;</div><div><br></div><div>Title: {{ticket_title}}&nbsp;</div><div><br></div><div>You can track the progress of your ticket and provide any additional information or updates by logging into your account on our support portal. If you have any questions or need further assistance, please don\'t hesitate to reply to this email or contact our support team at {{contact_email}} or {{contact_phone}}. Thank you for using our services!</div><div><br></div><div><span style="font-weight: bolder;">Best regards</span>, {{app_name}}</div>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'ticket', 'title' => 'Ticket assign For Team Member', 'slug' => 'ticket-assign-for-team-member', 'subject' => 'ticket assign', 'body' => 'ticket asaingn', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'quotation', 'title' => 'Quotation Email Send', 'slug' => 'quotation-email-send', 'subject' => 'ticket assign', 'body' => 'ticket asaingn', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'invoice', 'title' => 'Invoice Unpaid Notify For Client', 'slug' => 'invoice-unpaid-notify-for-client', 'subject' => 'Invoice Unpaid Notify For Client', 'body' => 'Invoice Unpaid Notify For Client', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'invoice', 'title' => 'Invoice Paid Notify For Client', 'slug' => 'invoice-paid-notify-for-client', 'subject' => 'Invoice Paid Notify For Client', 'body' => 'Invoice Paid Notify For Client', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];
        EmailTemplate::insert($data);
    }
}

function replaceBrackets($content, $customizedFieldsArray)
{
    $pattern = '/{{(.*?)}}/';
    $content = preg_replace_callback($pattern, function ($matches) use ($customizedFieldsArray) {
        $field = trim($matches[1]);
        if (array_key_exists($field, $customizedFieldsArray)) {
            return $customizedFieldsArray[$field];
        }
        return $matches[0];
    }, $content);
    return $content;
}

// checkout order generate
function makeClientOrder($orderData, $client, $userId, $tenant_id)
{
    try {

        $userPackage = UserPackage::query()
            ->where(['status' => ACTIVE, 'user_id' => $userId])
            ->whereDate('end_date', '>=', now()->toDateTimeString())
            ->first();

        $totalOrder = ClientOrder::where(['user_id' => $userId, 'tenant_id' => $tenant_id])->count();

        if (is_null($userPackage) || ($userPackage->number_of_order <= $totalOrder)) {
            throw new Exception(__("Something went wrong, Contact with admin"));
        }

        $clientOrder = new ClientOrder();
        $clientOrder->amount = $orderData['amount'];
        $clientOrder->order_form_id = isset($orderData['order_form_id']) ? $orderData['order_form_id'] : null;
        $clientOrder->quotation_id = isset($orderData['quotation_id']) ? $orderData['quotation_id'] : null;
        $clientOrder->discount = $orderData['discount'];
        $clientOrder->discount_type = $orderData['discount_type'];
        $clientOrder->tax_amount = 0.00;
        $clientOrder->tax_type = TAX_TYPE_FLAT;
        $clientOrder->platform_charge = $orderData['platform_charge'];
        $clientOrder->subtotal = $orderData['amount'];
        $clientOrder->total = $orderData['amount'];
        $clientOrder->transaction_amount = 0.00;
        $clientOrder->payment_status = PAYMENT_STATUS_PENDING;
        $clientOrder->order_create_type = $orderData['order_create_type'];
        $clientOrder->working_status = 0;
        $clientOrder->system_currency = Currency::where('current_currency', 'on')->first()->currency_code;
        $clientOrder->client_id = $client->id;
        $clientOrder->user_id = $userId;
        $clientOrder->tenant_id = $tenant_id;
        $clientOrder->recurring_type = $orderData['recurring_type'] ?? 0;
        $clientOrder->recurring_payment_type = $orderData['recurring_payment_type'];
        $clientOrder->save();

        $itemDiscount = 0;
        foreach ($orderData['orderItems'] as $item) {
            $clientOrderItem = new ClientOrderItem();
            $clientOrderItem->order_id = $clientOrder->id;
            $clientOrderItem->service_id = $item->service_id ?? 0;
            $clientOrderItem->price = $item->price;
            $clientOrderItem->quantity = $item->quantity;
            if (isset($orderData['quotation_id'])) {
                $clientOrderItem->discount = 0;
                $clientOrderItem->total = $item->price;
            } else {
                $clientOrderItem->discount = $orderData['discount'];
                $clientOrderItem->total = $item->price - $orderData['discount'];
                $itemDiscount += $orderData['discount'];
            }
            $clientOrderItem->save();
        }

        $clientOrder->subtotal = $orderData['amount'] - $itemDiscount;
        $clientOrder->total = $orderData['amount'] - $itemDiscount;
        $clientOrder->order_id = 'ODR-' . sprintf('%06d', $clientOrder->id);
        $clientOrder->save();
        return ['success' => true, 'data' => $clientOrder];
    } catch (Exception $e) {
        Log::info($e->getMessage() . "ffffff");

        return ['success' => false, 'data' => [], 'message' => $e->getMessage()];
    }
}

function makeClientInvoice($invoiceData, $clientId, $userId, $tenantId)
{
    try {
        Log::info(">>>>>>>>>>");

        $clientInvoice = new ClientInvoice();
        $clientInvoice->order_id = $invoiceData['order']->id;
        $clientInvoice->tax_amount = 0.00;
        $clientInvoice->tax_type = TAX_TYPE_FLAT;
        $clientInvoice->payment_status = PAYMENT_STATUS_PENDING;

        if (isset($invoiceData['gateway'])) {
            $clientInvoice->total = $invoiceData['order']->total;
            $clientInvoice->payable_amount = $invoiceData['order']->total;
            $clientInvoice->gateway_id = $invoiceData['gateway']->id;
            $clientInvoice->gateway_currency = $invoiceData['gateway_currency']->currency;
            $clientInvoice->conversion_rate = $invoiceData['gateway_currency']->conversion_rate;
            $clientInvoice->transaction_amount = $invoiceData['order']->total * $invoiceData['gateway_currency']->conversion_rate;
            $clientInvoice->platform_charge = $invoiceData['platform_charge'];
            $clientInvoice->system_currency = Currency::where('current_currency', 'on')->first()->currency_code;;
        } else {
            $clientInvoice->payable_amount = $invoiceData['order']->total;
            $clientInvoice->total = $invoiceData['order']->total;
        }

        $clientInvoice->due_date = isset($invoiceData['due_date']) ? $invoiceData['due_date'] : now();
        $clientInvoice->is_recurring = isset($invoiceData['is_recurring']) ? $invoiceData['is_recurring'] : DEACTIVATE;
        $clientInvoice->client_id = $clientId;
        $clientInvoice->user_id = $userId;
        $clientInvoice->tenant_id = $tenantId;
        $clientInvoice->save();

        $clientInvoice->invoice_id = 'INV-' . sprintf('%06d', $clientInvoice->id);
        $clientInvoice->save();
        return ['success' => true, 'data' => $clientInvoice];
    } catch (Exception $e) {
        return ['success' => false, 'data' => [], 'message' => $e->getMessage()];
    }
}

function custom_number_format($value)
{
    if (is_integer($value)) {
        return number_format($value, 8, '.', '');
    } elseif (is_string($value)) {
        $value = floatval($value);
    }
    $number = explode('.', number_format($value, 10, '.', ''));
    return $number[0] . '.' . substr($number[1], 0, 8);
}

if (!function_exists('setCommonNotification')) {
    function setCommonNotification($userId, $title, $details, $link = NULL,)
    {
        try {
            DB::beginTransaction();
            $obj = new Notification();
            $obj->user_id = $userId != NULL ? $userId : NULL;
            $obj->title = $title;
            $obj->body = $details;
            $obj->link = $link != NULL ? $link : NULL;
            $obj->save();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}

if (!function_exists('checkoutPaymentMail')) {
    function checkoutPaymentMail($invoice)
    {
        return true;
    }
}

if (!function_exists('userNotification')) {
    function userNotification($type)
    {
        if ($type == 'seen') {
            return Notification::leftJoin('notification_seens', 'notifications.id', '=', 'notification_seens.notification_id')
                ->where(function ($query) {
                    $query->where('notifications.user_id', null)->orWhere('notifications.user_id', Auth::id());
                })
                ->where('notifications.status', ACTIVE)
                ->where('notification_seens.id', '!=', null)
                ->orderBy('id', 'DESC')
                ->get([
                    'notifications.*',
                    'notification_seens.id as seen_id',
                ]);
        } else if ($type == 'unseen') {
            $test = Notification::leftJoin('notification_seens', 'notifications.id', '=', 'notification_seens.notification_id')
                ->where(function ($query) {
                    $query->where('notifications.user_id', null)->orWhere('notifications.user_id', Auth::id());
                })
                ->where('notifications.status', ACTIVE)
                ->where('notification_seens.id', null)
                ->orderBy('id', 'DESC')
                ->get([
                    'notifications.*',
                    'notification_seens.id as seen_id',
                ]);
            return $test;
        } else if ($type == 'seen-unseen') {
            return Notification::leftJoin('notification_seens', 'notifications.id', '=', 'notification_seens.notification_id')
                ->where(function ($query) {
                    $query->where('notifications.user_id', null)->orWhere('notifications.user_id', Auth::id());
                })
                ->where('notifications.status', ACTIVE)
                ->orderBy('id', 'DESC')
                ->get([
                    'notifications.*',
                    'notification_seens.id as seen_id',
                ]);
        }
    }
}

if (!function_exists('getSubText')) {
    function getSubText($html, $limit = 100000)
    {
        return \Illuminate\Support\Str::limit(strip_tags($html), $limit);
    }
}
if (!function_exists('getPaymentType')) {
    function getPaymentType($object)
    {
        return $className = class_basename(get_class($object));
    }
}
if (!function_exists('thousandFormat')) {
    function thousandFormat($number)
    {
        $number = (int)preg_replace('/[^0-9]/', '', $number);
        if ($number >= 1000) {
            $rn = round($number);
            $format_number = number_format($rn);
            $ar_nbr = explode(',', $format_number);
            $x_parts = array('K', 'M', 'B', 'T', 'Q');
            $x_count_parts = count($ar_nbr) - 1;
            $dn = $ar_nbr[0] . ((int)$ar_nbr[1][0] !== 0 ? '.' . $ar_nbr[1][0] : '');
            $dn .= $x_parts[$x_count_parts - 1];

            return $dn;
        }
        return $number;
    }
}

if (!function_exists('getTicketNumber')) {
    function getTicketNumber($eventId, $oldTotal)
    {
        return $eventId . sprintf('%04d', ++$oldTotal);
    }
}

if (!function_exists('userMessageUnseen')) {
    function userMessageUnseen()
    {
        return Chat::where('receiver_id', auth()->id())->where('is_seen', STATUS_PENDING)->count();
    }
}

if (!function_exists('encodeId')) {
    function encodeId($id)
    {
        return encrypt($id);
    }
}
if (!function_exists('decodeId')) {
    function decodeId($id)
    {
        return decrypt($id);
    }
}

if (!function_exists('createWebhookEvent')) {
    function createWebhookEvent($type, $planId, $userId, $requestData)
    {
        try {
            $webhookData = Webhook::where('plan_id', $planId)->first();
            if ($webhookData && $webhookData != null) {
                $reponse = getApiCall($webhookData->webhook_url, $requestData);
                $reponseData = json_decode($reponse);
                $webhookEventObj = new WebhookEvent();
                $webhookEventObj->event_id = 'WHE-' . sprintf('%06d', $planId);
                $webhookEventObj->event_type = $type ?? '';
                $webhookEventObj->user_id = $userId ?? '';
                $webhookEventObj->webhook_id = $webhookData->id;
                $webhookEventObj->product_id = $webhookData->product_id;
                $webhookEventObj->plan_id = $webhookData->plan_id;
                $webhookEventObj->request_data = json_encode($requestData);
                $webhookEventObj->webhook_url = $webhookData->webhook_url;
                $webhookEventObj->response_msg = isset($reponseData->message) ? $reponseData->message : '';
                $webhookEventObj->response_code = isset($reponseData->code) ? $reponseData->code : '';
                $webhookEventObj->response_data = $reponseData != null ? json_encode($reponseData) : '';
                $webhookEventObj->retry_count = 0;
                $webhookEventObj->status = (isset($reponseData->status) && $reponseData->status == true) ? WEBHOOK_EVENT_STATUS_SUCCESS : WEBHOOK_EVENT_STATUS_FAILED;
                $webhookEventObj->save();
                Log::info('Webhook: Event generated successfully: Id-' . $webhookEventObj->id);
            }
        } catch (Exception $exception) {
            Log::info('Webhook Event Create Exception:' . $exception->getMessage());
        }
    }
}


function getApiCall($url, $requestData)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url . '?' . http_build_query($requestData),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    Log::info($response);
    return $response;
    throw new Exception($response->getBody());
}

function addUserActivityLog($action, $user_id)
{
    $current_ip = get_clientIp();
    $agent = new Agent();
    $deviceType = isset($agent) && $agent->isMobile() == true ? 'Mobile' : 'Web';
    $location = geoip()->getLocation($current_ip);
    $activity['user_id'] = $user_id;
    $activity['action'] = $action;
    $activity['ip_address'] = isset($current_ip) ? $current_ip : '0.0.0.0';
    $activity['source'] = $deviceType;
    $activity['location'] = $location->country;
    UserActivityLog::create($activity);
};


function currencyPrice($price)
{
    if ($price == null) {
        return 0;
    }
    if (getCurrencyPlacement() == 'after')
        return number_format($price, 2) . '' . getCurrencySymbol();
    else {
        return getCurrencySymbol() . number_format($price, 2);
    }
}

function getEmailByUserId($user_id)
{
    return User::where('id', $user_id)->first(['email'])?->email;
}

function getUserData($user_id, $property)
{
    $data = User::where('id', $user_id)->first();
    if (!is_null($data)) {
        return $data->{$property};
    }
    return null;
}

if (!function_exists('getTicketIdHtml')) {
    function getTicketIdHtml($data)
    {
        if ($data->last_reply_id == null && $data->status == TICKET_STATUS_OPEN) {
            return '<a href="' . getRoute($data) . '" class="btn btn-outline-primary position-relative ticket-tracking-id  agent-ticket-id" >
                            ' . $data->ticket_id . '
                             <span class="badge bg-danger position-absolute rounded-pill agent-msg-new start-100 top-0 translate-middle">
                                ' . __("New") . '
                             </span>
                        </a>';
        } else if ($data->is_seen == 0) {
            if (\auth()->user()->role == USER_ROLE_CLIENT) {
                return '<a href="' . getRoute($data) . '" class="btn btn-outline-primary position-relative ticket-tracking-id  agent-ticket-id" >
                            ' . $data->ticket_id . '
                              <span class="badge bg-danger position-absolute agent-msg-no-message rounded-pill top-0 translate-middle">
                                <i class="fa-regular  fa-envelope mb-0"></i>
                             </span>
                        </a>';
            } else {
                if ($data->last_reply_by != null && getRoleByUserId($data->last_reply_by) == USER_ROLE_CLIENT) {
                    return '<a href="' . getRoute($data) . '" class="btn btn-outline-primary position-relative ticket-tracking-id  agent-ticket-id" >
                            ' . $data->ticket_id . '
                              <span class="badge bg-danger position-absolute agent-msg-no-message rounded-pill top-0 translate-middle">
                                <i class="fa-regular  fa-envelope mb-0"></i>
                             </span>
                        </a>';
                } else {
                    return '<a href="' . getRoute($data) . '" class="btn btn-outline-primary position-relative ticket-tracking-id  agent-ticket-id" >
                            ' . $data->ticket_id . '
                            <span class="badge bg-danger position-absolute agent-msg-no-message rounded-pill top-0 translate-middle">
                                <i class="fa-regular  fa-envelope mb-0"></i>
                             </span>
                        </a>';
                }
            }
        } else {
            return '<a href="' . getRoute($data) . '" class="btn btn-outline-primary position-relative ticket-tracking-id agent-ticket-id" >
                            ' . $data->ticket_id . '
                        </a>';
        }
    }
}

function getRoleByUserId($user_id)
{
    return User::where('id', $user_id)->first(['role'])->role;
}

function getRoute($data)
{
    $view_route = '';
    if (Auth::user()->role == USER_ROLE_CLIENT) {
        $view_route = route('user.ticket.details', encrypt($data->id));
    } else {
        $view_route = route('admin.ticket.details', encrypt($data->id));
    }
    return $view_route;
}

function getCustomEmailTemplate($type, $template, $property, $link = null, $customData = null, $userData = null)
{
    $data = EmailTemplate::where('slug', $template)->first();
    if ($data && $data != null) {
        if ($property == 'body') {
            $body = $data->{$property};
            foreach (customEmailTempFields($type) as $key => $item) {
                if ($key == '{{reset_password_url}}') {
                    $body = str_replace($key, $link, $body);
                } else if ($key == '{{email_verify_url}}') {
                    $body = str_replace($key, $link, $body);
                } else if ($key == '{{order_id}}' && $customData != NULL) {
                    $body = str_replace($key, is_object($customData) ? $customData->order_id : $customData['order_id'], $body);
                } else if ($key == '{{tracking_no}}' && $customData != NULL) {
                    $body = str_replace($key, is_object($customData) ? $customData->ticket_id : $customData['ticket_id'], $body);
                } else if ($key == '{{username}}') {
                    $body = str_replace($key, is_object($customData) ? $userData->name : $userData['name'], $body);
                } else if ($key == '{{ticket_title}}') {
                    $body = str_replace($key, $customData->ticket_title, $body);
                } else if ($key == '{{ticket_description}}') {
                    $body = str_replace($key, $customData->ticket_description, $body);
                } else if ($key == '{{ticket_created_time}}') {
                    $body = str_replace($key, $customData->created_at, $body);
                } else if ($key == '{{client_name}}') {
                    $body = str_replace($key, $customData->client_name, $body);
                } else if ($key == '{{link}}') {
                    $body = str_replace($key, $link, $body);
                } else {
                    $body = str_replace($key, $item, $body);
                }
            }
            return $body;
        } else if ($property == 'subject') {
            $subject = $data->{$property};

            foreach (customEmailTempFields($type) as $key => $item) {
                if ($key == '{{tracking_no}}') {
                    $subject = str_replace($key, $customData->ticket_id, $subject);
                }
            }

            return $subject;
        } else {
            return $data->{$property};
        }
    }
    return '';
}

function getTicketAssigned($ticketId)
{
    try {
        $ticketActiveAssignee = [];
        $ticketsData = Ticket::where('id', $ticketId)->with(['assignee'])->first();
        if ($ticketsData) {
            return $ticketsData->assignee;
        }
        return $ticketActiveAssignee;
    } catch (\Exception $e) {
        return [];
    }
}

//email notification helper start
function newTicketEmailNotify($ticket_id, $email = null)
{
    try {
        if (getOption('app_mail_status')) {

            $ticketData = Ticket::find($ticket_id);
            if ($ticketData && $ticketData != null) {
                $userData = User::find($ticketData->client_id);

                //send client mail
                $templeate = 'ticket-create-notify-for-client';
                Mail::to($userData->email)->send(new CustomEmailNotify('ticket', $ticketData, $templeate, $userData, ''));

                //send admin mail
                $templeate = 'ticket-create-notify-for-admin';
                $adminData = User::where(['tenant_id' => $ticketData->tenant_id, 'role' => USER_ROLE_ADMIN])->first();
                Mail::to($adminData->email)->send(new CustomEmailNotify('ticket', $ticketData, $templeate, $adminData, ''));

                //send assignee mail if exist
                $agentAssignee = getTicketAssigned($ticket_id);
                if (count($agentAssignee) > 0) {
                    $templeate = 'ticket-create-notify-for-team-member';
                    foreach ($agentAssignee as $agent) {
                        Mail::to(getEmailByUserId($agent->assigned_to))->send(new CustomEmailNotify('ticket', $ticketData, $templeate, $agent, ''));
                    }
                }
            } else {
                Log::info('New Ticket Create Email Notify Alert: Ticket not found');
            }
        } else {
            Log::info('New Ticket Create Email Notify Alert: App mail status not active');
        }
        return true;
    } catch (\Exception $e) {
        Log::info('New Ticket Create Email Notify Error: ' . $e->getMessage());
        return true;
    }
}

function ticketStatusChangeEmailNotify($ticket_id)
{
    try {
        if (getOption('app_mail_status')) {
            $ticketData = Ticket::find($ticket_id);
            if ($ticketData && $ticketData != null) {
                $userData = User::find($ticketData->client_id);
                //send customer mail
                $templeate = 'ticket-status-change-for-client';
                Mail::to($userData->email)->send(new CustomEmailNotify('ticket', $ticketData, $templeate, $userData, ''));
            } else {
                Log::info('Ticket Status Change Email Notify Alert: Ticket not found');
            }
        } else {
            Log::info('Ticket Status Change Email Notify Alert: App mail status not active');
        }
        return true;
    } catch (\Exception $e) {
        Log::info('Ticket Status Change Email Notify Error: ' . $e->getMessage());
        return true;
    }
}

function ticketConversationEmailNotifyToAdminAndTeamMember($ticket_id)
{
    try {
        if (getOption('app_mail_status')) {

            $ticketData = Ticket::find($ticket_id);
            if ($ticketData && $ticketData != null) {

                //send admin mail
                $templeate = 'ticket-conversation-for-admin';
                $adminData = User::where(['tenant_id' => $ticketData->tenant_id, 'role' => USER_ROLE_ADMIN])->first();
                Mail::to($adminData->email)->send(new CustomEmailNotify('ticket', $ticketData, $templeate, $adminData, ''));

                //send agent mail if exist
                $memberAssignee = getTicketAssigned($ticket_id);
                if (count($memberAssignee) > 0) {
                    $replyData = TicketConversation::where('ticket_id', $ticket_id)->get();
                    $memberList = [];
                    $assignMembertList = [];
                    $replyMemberList = [];
                    foreach ($memberAssignee as $member) {
                        $assignMembertList[] = [
                            'email' => getUserData($member->assigned_to, 'email'),
                            'name' => getUserData($member->assigned_to, 'name'),
                            'role' => getUserData($member->assigned_to, 'role')
                        ];
                    }
                    if (count($replyData) > 0) {
                        foreach ($replyData as $member) {
                            $userData = User::where('id', $member->user_id)
                                ->where('role', USER_ROLE_TEAM_MEMBER)
                                ->first();
                            if (is_null($userData)) {
                                continue;
                            }
                            $replyMemberList[] = [
                                'email' => $userData->email,
                                'name' => $userData->name,
                                'role' => $userData->role
                            ];
                        }
                        if ($replyMemberList != null && count($replyMemberList) > 0) {
                            $assignMembertList = array_unique(array_merge($assignMembertList, $replyMemberList), SORT_REGULAR);
                        }
                    }

                    $templeate = 'ticket-conversation-for-team-member';
                    foreach ($assignMembertList as $member) {
                        Mail::to($member['email'])->send(new CustomEmailNotify('ticket', $ticketData, $templeate, $member, ''));
                    }
                }
            } else {
                Log::info('Ticket Conversation Email Notify Alert: Ticket not found');
            }
        } else {
            Log::info('Ticket Conversation Email Notify Alert: App mail status not active');
        }

        return true;
    } catch (\Exception $e) {
        Log::info('Ticket Conversation Email Notify Error: ' . $e->getMessage());
        return true;
    }
}

function ticketConversationEmailNotifyForCustomer($ticket_id)
{
    try {
        if (getOption('app_mail_status')) {
            $ticketData = Ticket::find($ticket_id);
            if ($ticketData && $ticketData != null) {
                $userData = User::find($ticketData->client_id);
                //send customer mail
                $templeate = 'ticket-conversation-for-client';
                Mail::to($userData->email)->send(new CustomEmailNotify('ticket', $ticketData, $templeate, $userData, ''));
            } else {
                Log::info('Ticket Conversation Email Notify Alert: Ticket not found');
            }
        } else {
            Log::info('Ticket Conversation Email Notify Alert: App mail status not active');
        }

        return true;
    } catch (\Exception $e) {
        Log::info('Ticket Conversation Email Notify Error: ' . $e->getMessage());
        return true;
    }
}

function orderMailNotify($invoice_id, $type = INVOICE_MAIL_TYPE_UNPAID)
{
    try {
        if (getOption('app_mail_status')) {
            $clientInvoice = ClientInvoice::find($invoice_id);

            if ($clientInvoice && $clientInvoice != null) {
                $userData = User::find($clientInvoice->client_id);
                //send customer mail
                if ($type == INVOICE_MAIL_TYPE_PAID) {
                    $templeate = 'invoice-paid-notify-for-client';
                } else {
                    $templeate = 'invoice-unpaid-notify-for-client';
                }
                Mail::to($userData->email)->send(new CustomEmailNotify('invoice', $clientInvoice, $templeate, $userData, ''));
            } else {
                Log::info('Invoice Email Notify Alert: Invoice not found');
            }
        } else {
            Log::info('Invoice Email Notify Alert: App mail status not active');
        }
        return true;
    } catch (\Exception $e) {
        Log::info('Ticket Conversation Email Notify Error: ' . $e->getMessage());
        return true;
    }
}

function sendForgotMail($email)
{
    try {
        if (getOption('app_mail_status')) {
            $token = Str::random(64);
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now()
            ]);

            $user = User::where('email', $email)->first();

            //send customer mail
            $customizedFieldsArray = [];

            $link = route('password.reset.verify', $token);

            Mail::to($email)->send(new CustomEmailNotify('reset-password', collect($customizedFieldsArray), 'password-reset', $user, $link));
        } else {
            Log::info('Forgot Email Notify Alert: App mail status not active');
        }
        return true;
    } catch (\Exception $e) {
        Log::info('Ticket Conversation Email Notify Error: ' . $e->getMessage());
        return true;
    }
}

function assigneMemberEmailNotify($ticket_id, $assigne_to)
{
    try {
        if (getOption('app_mail_status')) {

            $ticketData = Ticket::find($ticket_id);
            if ($ticketData && $ticketData != null) {
                $userData = User::find($assigne_to);

                //send client mail
                $templeate = 'ticket-assign-for-team-member';
                Mail::to($userData->email)->send(new CustomEmailNotify('ticket', $ticketData, $templeate, $userData, ''));
            } else {
                Log::info('Assign Member Email Notify Alert: Ticket not found');
            }
        } else {
            Log::info('Assign Member Email Notify Alert: App mail status not active');
        }
        return true;
    } catch (\Exception $e) {
        Log::info('Assign Member Email Notify Error: ' . $e->getMessage());
        return true;
    }
}
//email notification helper end

//Subscription notification and mail helper start
function subscriptionBuyNotify($package_id)
{
    try {
        $packageData = UserPackage::find($package_id);

        if ($packageData && $packageData != null) {
            //send super admin mail & notify
            $templeate = 'package-buy-notify-for-super-admin';
            $sadminData = User::where(['tenant_id' => '', 'role' => USER_ROLE_SUPER_ADMIN])->first();
            setCommonNotification($sadminData->id, getCustomEmailTemplate('subscription', $templeate, 'subject', $link = '', $packageData, $sadminData), getCustomEmailTemplate('subscription', $templeate, 'body', '', $packageData, $sadminData));
        } else {
            // Log::info('New Ticket Create Email Notify Alert: Ticket not found');
        }
        return true;
    } catch (\Exception $e) {
        Log::info('Buy User Package Email Notify Error: ' . $e->getMessage());
        return true;
    }
}
//Subscription notification and mail helper end


//notification helper start
function newTicketNotify($ticket_id)
{
    try {
        $ticketData = Ticket::find($ticket_id);

        if ($ticketData && $ticketData != null) {
            $userData = User::find($ticketData->client_id);

            //send customer mail
            $templeate = 'ticket-create-notify-for-client';
            setCommonNotification($userData->id, getCustomEmailTemplate('ticket', $templeate, 'subject', $link = '', $ticketData, $userData), getCustomEmailTemplate('ticket', $templeate, 'body', '', $ticketData, $userData));


            //send admin mail
            $templeate = 'ticket-create-notify-for-admin';
            $adminData = User::where(['tenant_id' => $ticketData->tenant_id, 'role' => USER_ROLE_ADMIN])->first();
            setCommonNotification($adminData->id, getCustomEmailTemplate('ticket', $templeate, 'subject', $link = '', $ticketData, $adminData), getCustomEmailTemplate('ticket', $templeate, 'body', '', $ticketData, $adminData));


            //send agent mail if exist
            $agentAssignee = getTicketAssigned($ticket_id);
            if (count($agentAssignee) > 0) {
                $templeate = 'ticket-create-notify-for-team-member';
                foreach ($agentAssignee as $agent) {
                    setCommonNotification($agent->assigned_to, getCustomEmailTemplate('ticket', $templeate, 'subject', $link = '', $ticketData, $agent), getCustomEmailTemplate('ticket', $templeate, 'body', '', $ticketData, $agent));
                }
            }
        } else {
            Log::info('New Ticket Create Email Notify Alert: Ticket not found');
        }
        return true;
    } catch (\Exception $e) {
        Log::info('New Ticket Create Email Notify Error: ' . $e->getMessage());
        return true;
    }
}

function ticketStatusChangeNotify($ticket_id)
{
    $ticketData = Ticket::find($ticket_id);
    if ($ticketData && $ticketData != null) {
        $userData = User::find($ticketData->client_id);
        //send customer mail
        $templeate = 'ticket-status-change-for-client';
        setCommonNotification($userData->id, getCustomEmailTemplate('ticket', $templeate, 'subject', $link = '', $ticketData, $userData), getCustomEmailTemplate('ticket', $templeate, 'body', '', $ticketData, $userData));
    }
}

function ticketConversationNotifyToAdminAndTeamMember($ticket_id)
{
    $ticketData = Ticket::find($ticket_id);
    if ($ticketData && $ticketData != null) {

        //send admin mail
        $templeate = 'ticket-conversation-for-admin';
        $adminData = User::where(['tenant_id' => $ticketData->tenant_id, 'role' => USER_ROLE_ADMIN])->first();
        setCommonNotification($adminData->id, getCustomEmailTemplate('ticket', $templeate, 'subject', $link = '', $ticketData, $adminData), getCustomEmailTemplate('ticket', $templeate, 'body', '', $ticketData, $adminData));

        //send agent mail if exist
        $agentAssignee = getTicketAssigned($ticket_id);
        if (count($agentAssignee) > 0) {
            $templeate = 'ticket-conversation-for-team-member';
            foreach ($agentAssignee as $agent) {
                setCommonNotification($agent->id, getCustomEmailTemplate('ticket', $templeate, 'subject', $link = '', $ticketData, $agent), getCustomEmailTemplate('ticket', $templeate, 'body', '', $ticketData, $agent));
            }
        }
    }
}

function ticketConversationNotifyForCustomer($ticket_id)
{
    try {
        $ticketData = Ticket::find($ticket_id);
        if ($ticketData && $ticketData != null) {
            $userData = User::find($ticketData->client_id);
            //send customer mail
            $templeate = 'ticket-conversation-for-client';
            setCommonNotification($userData->id, getCustomEmailTemplate('ticket', $templeate, 'subject', $link = '', $ticketData, $userData), getCustomEmailTemplate('ticket', $templeate, 'body', '', $ticketData, $userData));
        }
    } catch (\Exception $e) {
        Log::info($e->getMessage());
    }
}

function getServiceById($id, $property)
{
    $data = Service::withTrashed()->find($id);
    return $data?->{$property};
}

function getOrderItemByOrderId($id)
{
    return ClientOrderItem::where('order_id', $id)->get();
}

function getOrderIdByOrderCustomId($id)
{
    return ClientOrder::where('order_id', $id)->first('id')?->id;
}

function sendQuotationToClientEmail($queation_id)
{
    try {
        if (getOption('app_mail_status')) {
            $quotationData = Quotation::find($queation_id);
            if ($quotationData && $quotationData != null) {
                //send client mail
                $link = route('quotation.preview', [encrypt($queation_id), QUOTATION_STATUS_VIEWED]);
                $template = 'quotation-email-send';
                Mail::to($quotationData->email)->send(new CustomEmailNotify('quotation', $quotationData, $template, null, $link));
                $quotationData->status = QUOTATION_STATUS_SENT;
                $quotationData->is_emailed = 1;
                $quotationData->save();
                return true;
            } else {
                Log::info('Quotation Send Email Alert: Quotation not found');
                return false;
            }
        } else {
            Log::info('Quotation Send Email Alert: App mail status not active');
            return false;
        }
    } catch (\Exception $e) {
        Log::info('Quotation Send Email Error: ' . $e->getMessage());
        return false;
    }
}
//notification helper end

if (!function_exists('setUserPackage')) {
    function setUserPackage($userId, $package, $duration, $orderId = NULL)
    {
        UserPackage::where(['user_id' => $userId])->whereIn('status', [ACTIVE, INITIATE])->update(['status' => DEACTIVATE]);

        UserPackage::create([
            'user_id' => $userId,
            'package_id' => $package->id,
            'name' => $package->name,
            'number_of_client' => $package->number_of_client,
            'number_of_order' => $package->number_of_order,
            'monthly_price' => $package->monthly_price,
            'yearly_price' => $package->yearly_price,
            'order_id' => $orderId,
            'is_trail' => $package->is_trail,
            'start_date' => now(),
            'end_date' => Carbon::now()->addDays($duration),
            'status' => ACTIVE,
        ]);
    }
}

if (!function_exists('getAddonCodeCurrentVersion')) {
    function getAddonCodeCurrentVersion($appCode)
    {
        Artisan::call("config:clear");
        if ($appCode == 'ENCYSAAS') {
            return config('addon.ENCYSAAS.current_version', 0);
        }
    }
}
