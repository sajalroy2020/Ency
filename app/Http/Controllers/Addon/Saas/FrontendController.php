<?php

namespace App\Http\Controllers\Addon\Saas;

use App\Http\Services\FrontendService;
use App\Models\CoreFeaturesSetting;
use App\Models\Faq;
use App\Models\FeaturesSetting;
use App\Models\Package;
use App\Models\ServiceSetting;
use App\Models\Testimonial;
use Illuminate\Routing\Controller as BaseController;

class FrontendController extends BaseController
{
    public function index()
    {
        $data['pageTitle'] = __('Welcome');
        $frontendSection = new FrontendService();
        $frontendSection = $frontendSection->getHomeFrontendSection();
        $data['section'] = [
            'hero_area' => $frontendSection->where('slug', 'hero_area')->first(),
            'features' => $frontendSection->where('slug', 'features')->first(),
            'services' => $frontendSection->where('slug', 'services')->first(),
            'core_features' => $frontendSection->where('slug', 'core_features')->first(),
            'pricing' => $frontendSection->where('slug', 'pricing')->first(),
            'testimonials_area' => $frontendSection->where('slug', 'testimonials_area')->first(),
            'faqs_area' => $frontendSection->where('slug', 'faqs_area')->first(),
        ];
        $data['features'] = FeaturesSetting::where('status', STATUS_ACTIVE)->get();
        $data['services'] = ServiceSetting::where('status', STATUS_ACTIVE)->take(4)->get();
        $data['coreFeatures'] = CoreFeaturesSetting::where('status', STATUS_ACTIVE)->get();
        $data['packages'] = Package::where('status', ACTIVE)->where('is_trail', '!=', ACTIVE)->get();
        $data['testimonials'] = Testimonial::where('status', ACTIVE)->get();
        $data['faqs'] = Faq::where('status', ACTIVE)->get();
        return view('addon.saas.frontend.index', $data);
    }
}
