<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\General_Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class CustomAdsSettingController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {

            $data = Setting_Data();
            if ($data) {
                return view('admin.custom_ads_setting.index', ['result' => $data]);
            } else {
                return redirect()->back()->with('error', __('Label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function saveAdsCommission(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'ads_commission' => 'numeric|min:0|max:100',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $data = $request->all();
            $data["ads_commission"] = isset($data['ads_commission']) ? $data['ads_commission'] : 0;

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(array('status' => 200, 'success' => __('Label.save_setting')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function saveBannerAds(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'banner_ads_status' => 'required',
                'banner_ads_cpv' => 'numeric|min:0',
                'banner_ads_cpc' => 'numeric|min:0',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $data = $request->all();
            $data["banner_ads_status"] = isset($data['banner_ads_status']) ? $data['banner_ads_status'] : 0;
            $data["banner_ads_cpv"] = isset($data['banner_ads_cpv']) ? $data['banner_ads_cpv'] : 0;
            $data["banner_ads_cpc"] = isset($data['banner_ads_cpc']) ? $data['banner_ads_cpc'] : 0;

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(array('status' => 200, 'success' => __('Label.save_setting')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function saveInterstitalAds(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'interstital_ads_status' => 'required',
                'interstital_ads_cpv' => 'numeric|min:0',
                'interstital_ads_cpc' => 'numeric|min:0',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $data = $request->all();
            $data["interstital_ads_status"] = isset($data['interstital_ads_status']) ? $data['interstital_ads_status'] : 0;
            $data["interstital_ads_cpv"] = isset($data['interstital_ads_cpv']) ? $data['interstital_ads_cpv'] : 0;
            $data["interstital_ads_cpc"] = isset($data['interstital_ads_cpc']) ? $data['interstital_ads_cpc'] : 0;

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(array('status' => 200, 'success' => __('Label.save_setting')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function saveRewardAds(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'reward_ads_status' => 'required',
                'reward_ads_cpv' => 'numeric|min:0',
                'reward_ads_cpc' => 'numeric|min:0',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $data = $request->all();
            $data["reward_ads_status"] = isset($data['reward_ads_status']) ? $data['reward_ads_status'] : 0;
            $data["reward_ads_cpv"] = isset($data['reward_ads_cpv']) ? $data['reward_ads_cpv'] : 0;
            $data["reward_ads_cpc"] = isset($data['reward_ads_cpc']) ? $data['reward_ads_cpc'] : 0;

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(array('status' => 200, 'success' => __('Label.save_setting')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
