<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ads_Package;
use App\Models\Ads_Transaction;
use App\Models\Common;
use Illuminate\Http\Request;
use Exception;

class AdsTransactionController extends Controller
{
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
            $params['package'] = Ads_Package::latest()->get();
            if ($request->ajax()) {

                $input_type = $request['input_type'];
                $input_package = $request['input_package'];
                $input_search = $request['input_search'];

                if ($input_package != 0) {
                    if ($input_type == "today") {

                        if ($input_search != null && isset($input_search)) {
                            $data = Ads_Transaction::where('user_id', $user['id'])->where('transaction_id', 'LIKE', "%{$input_search}%")->with('package')->where('package_id', $input_package)
                                ->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('status', 'desc')->latest()->get();
                        } else {
                            $data = Ads_Transaction::where('user_id', $user['id'])->with('package')->where('package_id', $input_package)->whereDay('created_at', date('d'))
                                ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('status', 'desc')->latest()->get();
                        }
                    } else if ($input_type == "month") {

                        if ($input_search != null && isset($input_search)) {
                            $data = Ads_Transaction::where('user_id', $user['id'])->where('transaction_id', 'LIKE', "%{$input_search}%")->where('package_id', $input_package)->with('package')
                                ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('status', 'desc')->latest()->get();
                        } else {
                            $data = Ads_Transaction::where('user_id', $user['id'])->with('package')->where('package_id', $input_package)->whereMonth('created_at', date('m'))
                                ->whereYear('created_at', date('Y'))->orderBy('status', 'desc')->latest()->get();
                        }
                    } else if ($input_type == "year") {

                        if ($input_search != null && isset($input_search)) {
                            $data = Ads_Transaction::where('user_id', $user['id'])->where('transaction_id', 'LIKE', "%{$input_search}%")->where('package_id', $input_package)->with('package')
                                ->whereYear('created_at', date('Y'))->orderBy('status', 'desc')->latest()->get();
                        } else {
                            $data = Ads_Transaction::where('user_id', $user['id'])->with('package')->where('package_id', $input_package)->whereYear('created_at', date('Y'))->orderBy('status', 'desc')->latest()->get();
                        }
                    } else {

                        if ($input_search != null && isset($input_search)) {
                            $data = Ads_Transaction::where('user_id', $user['id'])->where('transaction_id', 'LIKE', "%{$input_search}%")->where('package_id', $input_package)->with('package')->orderBy('status', 'desc')->latest()->get();
                        } else {
                            $data = Ads_Transaction::where('user_id', $user['id'])->with('package')->where('package_id', $input_package)->orderBy('status', 'desc')->latest()->get();
                        }
                    }
                } else {
                    if ($input_type == "today") {

                        if ($input_search != null && isset($input_search)) {
                            $data = Ads_Transaction::where('user_id', $user['id'])->where('transaction_id', 'LIKE', "%{$input_search}%")->with('package')->whereDay('created_at', date('d'))
                                ->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('status', 'desc')->latest()->get();
                        } else {
                            $data = Ads_Transaction::where('user_id', $user['id'])->with('package')->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))
                                ->whereYear('created_at', date('Y'))->orderBy('status', 'desc')->latest()->get();
                        }
                    } else if ($input_type == "month") {

                        if ($input_search != null && isset($input_search)) {
                            $data = Ads_Transaction::where('user_id', $user['id'])->where('transaction_id', 'LIKE', "%{$input_search}%")->with('package')->whereMonth('created_at', date('m'))
                                ->whereYear('created_at', date('Y'))->orderBy('status', 'desc')->latest()->get();
                        } else {
                            $data = Ads_Transaction::where('user_id', $user['id'])->with('package')->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->orderBy('status', 'desc')->latest()->get();
                        }
                    } else if ($input_type == "year") {

                        if ($input_search != null && isset($input_search)) {
                            $data = Ads_Transaction::where('user_id', $user['id'])->where('transaction_id', 'LIKE', "%{$input_search}%")->with('package')->whereYear('created_at', date('Y'))->orderBy('status', 'desc')->latest()->get();
                        } else {
                            $data = Ads_Transaction::where('user_id', $user['id'])->with('package')->whereYear('created_at', date('Y'))->orderBy('status', 'desc')->latest()->get();
                        }
                    } else {

                        if ($input_search != null && isset($input_search)) {
                            $data = Ads_Transaction::where('user_id', $user['id'])->where('transaction_id', 'LIKE', "%{$input_search}%")->with('package')->orderBy('status', 'desc')->latest()->get();
                        } else {
                            $data = Ads_Transaction::where('user_id', $user['id'])->with('package')->orderBy('status', 'desc')->latest()->get();
                        }
                    }
                }

                for ($i = 0; $i < count($data); $i++) {
                    $data[$i]['date'] = date("Y-m-d", strtotime($data[$i]['created_at']));
                }

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->make(true);
            }
            return view('user.ads_transaction.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
