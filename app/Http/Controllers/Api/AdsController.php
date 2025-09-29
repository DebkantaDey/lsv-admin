<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\Ads_Package;
use App\Models\Ads_Transaction;
use App\Models\Ads_View_Click_Count;
use App\Models\Common;
use App\Models\Content;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

// Ads Type : 1- Banner Ads, 2- Interstital Ads, 3- Reward Ads
class AdsController extends Controller
{
    public $common;
    public $page_limit;
    private $folder_ads = "ads";
    private $folder_package = "package";
    private $folder_user = "user";
    public function __construct()
    {
        try {
            $this->common = new Common();
            $this->page_limit = env('PAGE_LIMIT');
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function get_ads(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'type' => 'required|numeric',
                ],
                [
                    'type.required' => __('api_msg.type_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            // Ads Inactive
            $this->common->InactiveAds();

            $data = Ads::where('is_hide', 0)->where('status', 1)->where('type', $request['type'])->inRandomOrder()->first();
            if ($data != null && isset($data)) {

                $data['image'] = $this->common->getImage($this->folder_ads, $data['image']);
                if ($data['type'] == 3) {
                    $data['video'] = $this->common->getVideo($this->folder_ads, $data['video']);
                }

                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_ads_view_click_count(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'ads_type' => 'required|numeric',
                    'ads_id' => 'required|numeric',
                    'device_type' => 'required|numeric',
                    'device_token' => 'required',
                    'type' => 'required|numeric',
                ],
                [
                    'ads_type.required' => __('api_msg.ads_type_is_required'),
                    'ads_id.required' => __('api_msg.ads_id_is_required'),
                    'device_type.required' => __('api_msg.device_type_is_required'),
                    'device_token.required' => __('api_msg.device_token_is_required'),
                    'type.required' => __('api_msg.type_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            if ($request['ads_type'] == 3) {
                $validation1 = Validator::make(
                    $request->all(),
                    [
                        'content_id' => 'required|numeric',
                    ],
                    [
                        'content_id.required' => __('api_msg.content_id_is_required'),
                    ]
                );
                if ($validation1->fails()) {
                    $data['status'] = 400;
                    $data['message'] = $validation1->errors()->first();
                    return $data;
                }
            }

            // Ads Inactive
            $this->common->InactiveAds($request['ads_id']);

            $check_ads = Ads::where('id', $request['ads_id'])->where('status', 1)->where('is_hide', 0)->latest()->first();
            if ($check_ads != null && isset($check_ads)) {

                $settingData = Setting_Data();

                // Type = 1- CPV, 2- CPC
                if ($request['type'] == 1) {

                    if ($request['ads_type'] == 1 || $request['ads_type'] == 2) {

                        if ($request['ads_type'] == 1) {
                            $ads_coin = $settingData['banner_ads_cpv'];
                        } else {
                            $ads_coin = $settingData['interstital_ads_cpv'];
                        }

                        $insert = new Ads_View_Click_Count();
                        $insert['ads_type'] = $request['ads_type'];
                        $insert['ads_id'] = $request['ads_id'];
                        $insert['device_type'] = $request['device_type'];
                        $insert['device_token'] = $request['device_token'];
                        $insert['content_id'] = 0;
                        $insert['type'] = 1;
                        $insert['total_coin'] = $ads_coin;
                        $insert['admin_commission'] = $ads_coin;
                        $insert['user_wallet_earning'] = 0;
                        $insert['status'] = 1;

                        if ($insert->save()) {
                            User::where('id', $check_ads['user_id'])->decrement('wallet_balance', $ads_coin);
                        }
                    } else if ($request['ads_type'] == 3) {

                        $check_content = Content::where('id', $request['content_id'])->latest()->first();
                        if ($check_content != null && isset($check_content)) {

                            $ads_coin = $settingData['reward_ads_cpv'];

                            $insert = new Ads_View_Click_Count();
                            $insert['ads_type'] = $request['ads_type'];
                            $insert['ads_id'] = $request['ads_id'];
                            $insert['device_type'] = $request['device_type'];
                            $insert['device_token'] = $request['device_token'];
                            $insert['content_id'] = $request['content_id'];
                            $insert['type'] = 1;
                            $insert['total_coin'] = $ads_coin;

                            $commission = $settingData['ads_commission'];
                            $admin_commission = round(($commission / 100) * $ads_coin);

                            $insert['admin_commission'] = $admin_commission;
                            $insert['user_wallet_earning'] = $ads_coin - $admin_commission;
                            $insert['status'] = 1;

                            if ($insert->save()) {
                                User::where('id', $check_ads['user_id'])->decrement('wallet_balance', $ads_coin);
                                User::where('channel_id', $check_content['channel_id'])->increment('wallet_earning', $insert['user_wallet_earning']);
                            }
                        }
                    }
                } else if ($request['type'] == 2) {

                    if ($request['ads_type'] == 1 || $request['ads_type'] == 2) {

                        if ($request['ads_type'] == 1) {
                            $ads_cpc = $settingData['banner_ads_cpc'];
                        } else {
                            $ads_cpc = $settingData['interstital_ads_cpc'];
                        }

                        $user_wallet_coin = $this->common->get_user_budget($check_ads['user_id']);
                        $total_view_click_coin = $this->common->get_total_view_click_coin($check_ads['id']);
                        $remening_budget = $check_ads['budget'] - $total_view_click_coin;

                        if ($ads_cpc <= $user_wallet_coin && $ads_cpc <= $remening_budget) {

                            $insert = new Ads_View_Click_Count();
                            $insert['ads_type'] = $request['ads_type'];
                            $insert['ads_id'] = $request['ads_id'];
                            $insert['device_type'] = $request['device_type'];
                            $insert['device_token'] = $request['device_token'];
                            $insert['content_id'] = 0;
                            $insert['type'] = 2;
                            $insert['total_coin'] = $ads_cpc;
                            $insert['admin_commission'] = $ads_cpc;
                            $insert['user_wallet_earning'] = 0;
                            $insert['status'] = 1;

                            if ($insert->save()) {
                                User::where('id', $check_ads['user_id'])->decrement('wallet_balance', $ads_cpc);
                            }
                        }
                    } else if ($request['type'] == 3) {

                        $ads_cpc = $settingData['reward_ads_cpc'];
                        $user_wallet_coin = $this->common->get_user_budget($check_ads['user_id']);
                        $total_view_click_coin = $this->common->get_total_view_click_coin($check_ads['id']);
                        $remening_budget = $check_ads['budget'] - $total_view_click_coin;

                        if ($ads_cpc <= $user_wallet_coin && $ads_cpc <= $remening_budget) {

                            $check_content = Content::where('id', $request['content_id'])->latest()->first();
                            if ($check_content != null && isset($check_content)) {

                                $insert = new Ads_View_Click_Count();
                                $insert['ads_type'] = $request['ads_type'];
                                $insert['ads_id'] = $request['ads_id'];
                                $insert['device_type'] = $request['device_type'];
                                $insert['device_token'] = $request['device_token'];
                                $insert['content_id'] = $request['content_id'];
                                $insert['type'] = 2;
                                $insert['total_coin'] = $ads_cpc;

                                $commission = $settingData['ads_commission'];
                                $admin_commission = round(($commission / 100) * $ads_cpc);

                                $insert['admin_commission'] = $admin_commission;
                                $insert['user_wallet_earning'] = $ads_cpc - $admin_commission;
                                $insert['status'] = 1;

                                if ($insert->save()) {
                                    User::where('id', $check_ads['user_id'])->decrement('wallet_balance', $ads_cpc);
                                    User::where('channel_id', $check_content['channel_id'])->increment('wallet_earning', $insert['user_wallet_earning']);
                                }
                            }
                        }
                    }
                }
            }

            return $this->common->API_Response(200, __('api_msg.add_record_successfully'), []);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_ads_package(Request $request)
    {
        try {

            $user_id = isset($request->user_id) ? $request->user_id : 0;

            $data = Ads_Package::where('status', 1)->orderBy('price', 'asc')->latest()->get();
            if (count($data) > 0) {

                $this->common->imageNameToUrl($data, 'image', $this->folder_package);
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_ads_transaction(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'package_id' => 'required|numeric',
                    'price' => 'required|numeric',
                    'coin' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'package_id.required' => __('api_msg.package_id_is_required'),
                    'price.required' => __('api_msg.price_is_required'),
                    'coin.required' => __('api_msg.coin_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request->user_id;
            $package_id = $request->package_id;
            $price = $request->price;
            $coin = $request->coin;
            $transaction_id = isset($request->transaction_id) ? $request->transaction_id : "";
            $description = isset($request->description) ? $request->description : "";

            $insert = new Ads_Transaction();
            $insert->user_id = $user_id;
            $insert->package_id = $package_id;
            $insert->transaction_id = $transaction_id;
            $insert->price = $price;
            $insert->coin = $coin;
            $insert->description = $description;
            $insert->status = 1;
            if ($insert->save()) {

                // Send Mail (Type = 1- Register, 2- Transaction, 3- Report, 4- User Penal Active)
                $user_email = User::where('id', $user_id)->first();
                if ($user_email != null && isset($user_email)) {
                    $this->common->Send_Mail(2, $user_email);
                }

                User::where('id', $user_id)->increment('wallet_balance', $coin);

                return $this->common->API_Response(200, __('api_msg.transaction_successfully'), []);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_ads_transaction_list(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request->user_id;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Ads_Transaction::where('user_id', $user_id)->with('user')->latest()->orderBy('id', 'desc');

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                for ($i = 0; $i < count($data); $i++) {

                    $data[$i]['channel_name'] = "";
                    $data[$i]['full_name'] = "";
                    $data[$i]['email'] = "";
                    $data[$i]['mobile_number'] = "";
                    $data[$i]['image'] = asset('assets/imgs/default.png');
                    if ($data[$i]['user'] != null && isset($data[$i]['user'])) {
                        $data[$i]['channel_name'] = $data[$i]['user']['channel_name'];
                        $data[$i]['full_name'] = $data[$i]['user']['full_name'];
                        $data[$i]['email'] = $data[$i]['user']['email'];
                        $data[$i]['mobile_number'] = $data[$i]['user']['mobile_number'];
                        $data[$i]['image'] = $this->common->getImage($this->folder_user, $data[$i]['user']['image']);
                        unset($data[$i]['user']);
                    }
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_ads_coin_history(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request->user_id;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $user_ads = Ads::where('user_id', $user_id)->orderBy('id', 'desc')->latest()->get();
            $user_ads_ids = [];
            for ($i = 0; $i < count($user_ads); $i++) {
                $user_ads_ids[] = $user_ads[$i]['id'];
            }
            if (count($user_ads_ids) > 0) {
                $data = Ads_View_Click_Count::selectRaw('ads_id, sum(total_coin) as total_coin')->whereIn('ads_id', $user_ads_ids)->with('ads')->groupBy('ads_id');
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->orderBy('total_coin', 'desc')->get();

            if (count($data) > 0) {

                for ($i = 0; $i < count($data); $i++) {

                    $data[$i]['title'] = "";
                    if ($data[$i]['ads'] != null && isset($data[$i]['ads'])) {
                        $data[$i]['title'] = $data[$i]['ads']['title'];
                        unset($data[$i]['ads']);
                    }
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
