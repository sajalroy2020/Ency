<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GatewayRequest;
use App\Http\Services\GatewayService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    use ResponseTrait;

    public $gatewayService;

    public function __construct()
    {
        $this->gatewayService = new GatewayService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Gateway');
        $data['activeGateway'] = 'active';
        $data['activeSetting'] = 'active';
        $data['gateways'] = $this->gatewayService->getAll(auth()->user()->tenant_id);
        return view('admin.setting.gateway.index', $data);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Gateway');
        $data['pageTitle'] = __('Edit Gateway');
        $data['activeGateway'] = 'active';
        $data['activeSetting'] = 'active';
        $data['gateway'] = $this->gatewayService->getInfo($id);
        $data['gatewayCurrencies'] = $this->gatewayService->getCurrencyByGatewayId($data['gateway']->id);
        $data['gatewaySettings'] = json_decode(gatewaySettings(), true)[$data['gateway']->slug] ?? [];
        $data['gatewayBanks'] = $this->gatewayService->banks(auth()->user()->tenant_id);
        return view('admin.setting.gateway.edit', $data);
    }

    public function store(GatewayRequest $request)
    {
        return $this->gatewayService->store($request);
    }

    public function getInfo(Request $request)
    {
        return $this->gatewayService->getCurrenciesByGatewayId($request->id);
    }

    public function getCurrencyByGateway(Request $request)
    {
        $data = $this->gatewayService->getCurrencyByGatewayId($request->id);
        return $this->success($data);
    }
}
