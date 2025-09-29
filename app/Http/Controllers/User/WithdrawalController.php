<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal_Request;
use App\Models\Common;
use Illuminate\Http\Request;
use Exception;

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
            $user = User_Data();

            $params['data'] = [];
            $params['setting'] = Setting_Data();
            $params['user'] = $user;
            $params['user']['total_withdral_amount'] = Withdrawal_Request::where('user_id', $user['id'])->sum('amount');

            if ($request->ajax()) {

                $input_status = $request['input_status'];
                if ($input_status == 1 || $input_status == 0) {
                    $data = Withdrawal_Request::where('user_id', $user['id'])->where('status', $input_status)->orderBy('status', 'asc')->latest()->get();
                } else {
                    $data = Withdrawal_Request::where('user_id', $user['id'])->orderBy('status', 'asc')->latest()->get();
                }

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('date', function ($row) {
                        return date("Y-m-d", strtotime($row->created_at));
                    })
                    ->addColumn('action', function ($row) {
                        if ($row->status == 1) {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id, $row->status)' style='background:#058f00; font-weight:bold; border: none;  color: white; padding: 4px 10px; outline: none; border-radius: 5px;'>Completed</button>";
                        } else {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id, $row->status)' style='background:#e3000b; font-weight:bold; border: none;  color: white; padding: 4px 20px; outline: none; border-radius: 5px;'>Pending</button>";
                        }
                    })
                    ->make(true);
            }
            return view('user.withdrawal.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
