<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\Ads_View_Click_Count;
use App\Models\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class AdsController extends Controller
{
    private $folder = "ads";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $user = User_Data();

            $params['data'] = [];
            $input_search = $request['input_search'];
            $input_type = $request['input_type'];

            if ($input_search != null && isset($input_search)) {
                if ($input_type != 0) {
                    $params['data'] = Ads::where('user_id', $user['id'])->where('title', 'LIKE', "%{$input_search}%")->where('type', $input_type)->orderBy('id', 'DESC')->paginate(15);
                } else {
                    $params['data'] = Ads::where('user_id', $user['id'])->where('title', 'LIKE', "%{$input_search}%")->orderBy('id', 'DESC')->paginate(15);
                }
            } else {
                if ($input_type != 0) {
                    $params['data'] = Ads::where('user_id', $user['id'])->where('type', $input_type)->orderBy('id', 'DESC')->paginate(15);
                } else {
                    $params['data'] = Ads::where('user_id', $user['id'])->orderBy('id', 'DESC')->paginate(15);
                }
            }

            $this->common->imageNameToUrl($params['data'], 'image', $this->folder);
            for ($i = 0; $i < count($params['data']); $i++) {

                if ($params['data'][$i]['type'] == 3) {
                    $this->common->videoNameToUrl(array($params['data'][$i]), 'video', $this->folder);
                }
            }

            return view('user.ads.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function create()
    {
        try {

            $params['data'] = [];
            $params['ads_setting'] = Setting_Data();
            return view('user.ads.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $user = User_Data();

            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'title' => 'required',
                'redirect_uri' => 'required',
                'budget' => 'required|numeric|min:1',
                'image' => 'required|image|mimes:jpeg,png,jpg',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            if ($request->type == 3) {
                $validator1 = Validator::make($request->all(), [
                    'video' => 'required',
                ]);
                if ($validator1->fails()) {
                    $errs1 = $validator1->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs1));
                }
            }

            // Budget Check
            $user_budget = $this->common->get_user_budget($user['id']);
            if ($user_budget < $request['budget']) {
                return response()->json(array('status' => 400, 'errors' => 'Recharge you Wallet.'));
            }

            $requestData = $request->all();
            $requestData['user_id'] = $user['id'];
            $requestData['status'] = 1;
            $requestData['is_hide'] = 0;
            $file = $requestData['image'];
            $requestData['image'] = $this->common->saveImage($file, $this->folder);

            if ($requestData['type'] == 3) {
                $requestData['video'] = $requestData['video'];
            } else {
                $requestData['video'] = "";
            }

            $ads_data = Ads::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($ads_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_added')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {

            $data = Ads::where('id', $id)->first();
            if (isset($data)) {

                $this->common->deleteImageToFolder($this->folder, $data['image']);
                $this->common->deleteImageToFolder($this->folder, $data['video']);
                $data->delete();
            }
            return redirect()->route('uads.index')->with('success', __('Label.data_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function adsDetails($id)
    {
        try {

            $params['ads_id'] = $id;
            $params['data'] = Ads::where('id', $id)->first();
            $params['total_ads_cpv'] = Ads_View_Click_Count::where('ads_id', $id)->where('type', 1)->count();
            $params['total_ads_cpc'] = Ads_View_Click_Count::where('ads_id', $id)->where('type', 2)->count();
            $params['total_use_budget'] = Ads_View_Click_Count::where('ads_id', $id)->sum('total_coin');
            $params['total_ads_cpv_coin'] = Ads_View_Click_Count::where('ads_id', $id)->where('type', 1)->sum('total_coin');
            $params['total_ads_cpc_coin'] = Ads_View_Click_Count::where('ads_id', $id)->where('type', 2)->sum('total_coin');

            return view('user.ads.details', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
