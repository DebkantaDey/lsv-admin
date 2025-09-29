<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Common;
use App\Models\Content;
use App\Models\Rent_Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class RentTransactionController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['data'] = [];
            $params['user'] = User::latest()->get();
            if ($request->ajax()) {

                $input_type = $request['input_type'];
                $input_user = $request['input_user'];
                $input_search = $request['input_search'];

                $query = Rent_Transaction::with('Content', 'user')->orderBy('id', 'desc');
                if ($input_user != 0) {
                    $query->where('user_id', $input_user);
                }
                if (!empty($input_search)) {
                    $query->where('transaction_id', 'LIKE', "%{$input_search}%");
                }
                if ($input_type == "today") {
                    $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "month") {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "year") {
                    $query->whereYear('created_at', date('Y'));
                }
                $data = $query->latest()->get();

                for ($i = 0; $i < count($data); $i++) {
                    $data[$i]['date'] = date("Y-m-d", strtotime($data[$i]['created_at']));
                }

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $delete = '<form onsubmit="return confirm(\'Are you sure !!! You want to Delete this Transaction ?\');" method="POST"  action="' . route('renttransaction.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="edit-delete-btn" style="outline: none;" title="Delete"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= $delete;
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.rent_transaction.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function create(Request $request)
    {
        try {
            $params['data'] = [];
            $params['user'] = User::where('id', $request->user_id)->first();
            $params['content'] = Content::where('content_type', 1)->where('status', 1)->where('is_rent', 1)->get();

            return view('admin.rent_transaction.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function searchUser(Request $request)
    {
        try {
            $name = $request->name;
            $user = User::orWhere('full_name', 'like', '%' . $name . '%')->orWhere('mobile_number', 'like', '%' . $name . '%')->orWhere('email', 'like', '%' . $name . '%')->latest()->get();

            $url = url('admin/renttransaction/create?user_id');
            $text = '<table width="100%" class="table table-striped category-table text-center table-bordered"><tr style="background: #F9FAFF;"><th>Full Name</th><th>Mobile</th><th>Email</th><th>Action</th></tr>';
            if ($user->count() > 0) {
                foreach ($user as $row) {

                    $a = '<a class="btn-link" href="' . $url . '=' . $row->id . '">Select</a>';
                    $text .= '<tr><td>' . $row->full_name . '</td><td>' . $row->mobile_number . '</td><td>' . $row->email . '</td><td>' . $a . '</td></tr>';
                }
            } else {
                $text .= '<tr><td colspan="4">User Not Found</td></tr>';
            }
            $text .= '</table>';

            return response()->json(array('status' => 200, 'success' => 'Search User', 'result' => $text));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'content_id' => 'required'
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $content = Content::where('id', $request->content_id)->first();

            $Transction = new Rent_Transaction();
            $Transction->user_id = $request->user_id;
            $Transction->content_id = $request->content_id;
            $Transction->transaction_id = 'admin';
            $Transction->price = $content->rent_price;
            $Transction->description = 'admin';
            $Transction->status = 1;

            $setting = Setting_Data();
            $admin_commission = $setting['rent_commission'];
            $persentage = round(($admin_commission / 100) * $content->rent_price);
            $user_wallet_amount = $content->rent_price - $persentage;

            $Transction->admin_commission = $persentage;
            $Transction->user_wallet_amount = $user_wallet_amount;

            if ($Transction->save()) {
                if ($Transction->id) {

                    // User Wallet Add Amount
                    $content = Content::where('id', $request['content_id'])->first();
                    if ($content != null && isset($content)) {
                        User::where('channel_id', $content['channel_id'])->increment('wallet_earning', $user_wallet_amount);
                    }

                    return response()->json(array('status' => 200, 'success' => __('Label.Transction_Add_Successfully')));
                } else {
                    return response()->json(array('status' => 400, 'errors' => __('Label.Transction_Not_Add')));
                }
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.Transction_Not_Add')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function destroy($id)
    {
        try {

            Rent_Transaction::where('id', $id)->delete();
            return redirect()->route('renttransaction.index')->with('success', __('Label.data_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
