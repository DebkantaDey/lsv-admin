<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\User;
use App\Models\Withdrawal_Request;
use Illuminate\Http\Request;
use Exception;

class WalletUserController extends Controller
{
    private $folder_user = "user";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['data'] = [];

            $input_search = $request['input_search'];
            if ($input_search != null && isset($input_search)) {
                $params['data'] = User::where('channel_name', 'LIKE', "%{$input_search}%")->orwhere('email', 'LIKE', "%{$input_search}%")->orwhere('mobile_number', 'LIKE', "%{$input_search}%")
                    ->orderBy('id', 'DESC')->paginate(16);
            } else {
                $params['data'] = User::orderBy('id', 'DESC')->paginate(16);
            }

            $this->common->imageNameToUrl($params['data'], 'image', $this->folder_user);

            return view('admin.wallet_user.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function edit($id, Request $request)
    {
        try {

            $params['data'] = User::where('id', $id)->first();
            if ($params['data'] != null) {

                $params['id'] = $id;
                $params['total_withdral_amount'] = Withdrawal_Request::where('user_id', $id)->sum('amount');

                if ($request->ajax()) {

                    $input_status = $request['input_status'];
                    if ($input_status == 1 || $input_status == 0) {
                        $data = Withdrawal_Request::where('user_id', $id)->where('status', $input_status)->orderBy('status', 'asc')->latest()->get();
                    } else {
                        $data = Withdrawal_Request::where('user_id', $id)->orderBy('status', 'asc')->latest()->get();
                    }

                    return DataTables()::of($data)
                        ->addIndexColumn()
                        ->addColumn('date', function ($row) {
                            return date("Y-m-d", strtotime($row->created_at));
                        })
                        ->addColumn('action', function ($row) {
                            if ($row->status == 1) {
                                return "<button type='button' style='background:#058f00; font-weight:bold; border: none;  color: white; padding: 4px 10px; outline: none; border-radius: 5px;'>Completed</button>";
                            } else {
                                return "<button type='button' style='background:#e3000b; font-weight:bold; border: none;  color: white; padding: 4px 20px; outline: none; border-radius: 5px;'>Pending</button>";
                            }
                        })
                        ->make(true);
                }

                return view('admin.wallet_user.edit', $params);
            } else {
                return redirect()->back()->with('error', __('Label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
