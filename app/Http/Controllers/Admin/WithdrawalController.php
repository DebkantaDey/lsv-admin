<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal_Request;
use App\Models\Common;
use App\Models\General_Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class WithdrawalController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $today_date = date('Y-m-d');
            $end_date = date("Y-m-t", strtotime(date('Y-m-d')));
            $check_request = Withdrawal_Request::whereMonth('created_at', date('m'))->whereDay('created_at', date('d'))->first();
            if ($today_date == $end_date && $check_request == null && !isset($check_request)) {

                $settingData = Setting_Data();
                $min_earning_amount = $settingData['min_withdrawal_amount'];
                $user = User::where('wallet_earning', '>=', $min_earning_amount)->where('status', 1)->where('wallet_earning', '!=', 0)->latest()->get();
                for ($i = 0; $i < count($user); $i++) {

                    $insert = new Withdrawal_Request();
                    $insert['user_id'] = $user[$i]['id'];
                    $insert['amount'] = $user[$i]['wallet_earning'];
                    $insert['payment_type'] = "";
                    $insert['payment_detail'] = "";
                    $insert['status'] = 0;
                    $insert->save();

                    $user[$i]['wallet_earning'] = 0;
                    $user[$i]->save();
                }
            }

            $params['data'] = [];
            $params['user'] = User::latest()->get();
            $params['setting'] = Setting_Data();
            if ($request->ajax()) {

                $input_user = $request['input_user'];
                $input_status = $request['input_status'];
                if ($input_user != 0) {
                    if ($input_status == 1 || $input_status == 0) {
                        $data = Withdrawal_Request::where('user_id', $input_user)->where('status', $input_status)->with('user')->orderBy('status', 'asc')->orderBy('id', 'desc')->latest()->get();
                    } else {
                        $data = Withdrawal_Request::where('user_id', $input_user)->with('user')->orderBy('status', 'asc')->orderBy('id', 'desc')->latest()->get();
                    }
                } else {
                    if ($input_status == 1 || $input_status == 0) {
                        $data = Withdrawal_Request::with('user')->where('status', $input_status)->orderBy('status', 'asc')->orderBy('id', 'desc')->latest()->get();
                    } else {
                        $data = Withdrawal_Request::with('user')->orderBy('status', 'asc')->orderBy('id', 'desc')->latest()->get();
                    }
                }

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('date', function ($row) {
                        return date("Y-m-d", strtotime($row->created_at));
                    })
                    ->addColumn('action', function ($row) {
                        if ($row->status == 1) {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id, $row->status)' style='background:#058f00; font-weight:bold; border: none;  color: white; padding: 4px 10px; outline: none; border-radius: 5px;cursor: pointer;'>Completed</button>";
                        } else {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id, $row->status)' style='background:#e3000b; font-weight:bold; border: none;  color: white; padding: 4px 20px; outline: none; border-radius: 5px;cursor: pointer;'>Pending</button>";
                        }
                    })
                    ->make(true);
            }
            return view('admin.withdrawal.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {

            $data = Withdrawal_Request::where('id', $id)->first();
            if ($data->status == 0) {
                $data->status = 1;
            } elseif ($data->status == 1) {
                $data->status = 0;
            } else {
                $data->status = 0;
            }
            $data->save();
            return response()->json(array('status' => 200, 'success' => 'Status Changed', 'id' => $data->id, 'Status_Code' => $data->status));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function saveMinWithdrawalAmount(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'min_withdrawal_amount' => 'numeric|min:1',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $data = $request->all();
            $data["min_withdrawal_amount"] = isset($data['min_withdrawal_amount']) ? $data['min_withdrawal_amount'] : 0;

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
