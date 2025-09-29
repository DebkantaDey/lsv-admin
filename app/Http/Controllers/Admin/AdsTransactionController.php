<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ads_Package;
use App\Models\Ads_Transaction;
use App\Models\User;
use App\Models\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

            $params['data'] = [];
            $params['package'] = Ads_Package::latest()->get();
            if ($request->ajax()) {

                $input_type = $request['input_type'];
                $input_package = $request['input_package'];
                $input_search = $request['input_search'];

                $query = Ads_Transaction::with('package', 'user')->orderBy('status', 'desc')->latest();
                if ($input_package != 0) {
                    $query->where('package_id', $input_package);
                }
                if ($input_search != null && isset($input_search)) {
                    $query->where('transaction_id', 'LIKE', "%{$input_search}%");
                }
                if ($input_type == "today") {
                    $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "month") {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "year") {
                    $query->whereYear('created_at', date('Y'));
                }
                $data = $query->get();

                for ($i = 0; $i < count($data); $i++) {
                    $data[$i]['date'] = date("Y-m-d", strtotime($data[$i]['created_at']));
                }

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $delete = '<form onsubmit="return confirm(\'Are you sure !!! You want to Delete this Transaction ?\');" method="POST"  action="' . route('adtransaction.destroy', [$row->id]) . '">
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
            return view('admin.ads_transaction.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function create(Request $request)
    {
        try {
            $params['data'] = [];
            $params['user'] = User::where('id', $request->user_id)->first();
            $params['package'] = Ads_Package::get();

            return view('admin.ads_transaction.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function searchUser(Request $request)
    {
        try {
            $name = $request->name;
            $user = User::orWhere('full_name', 'like', '%' . $name . '%')->orWhere('mobile_number', 'like', '%' . $name . '%')->orWhere('email', 'like', '%' . $name . '%')->latest()->get();

            $url = url('admin/adtransaction/create?user_id');
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
                'package_id' => 'required'
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $package = Ads_Package::where('id', $request->package_id)->first();

            $Transction = new Ads_Transaction();
            $Transction->user_id = $request->user_id;
            $Transction->package_id = $request->package_id;
            $Transction->transaction_id = 'admin';
            $Transction->price = $package->price;
            $Transction->coin = $package->coin;
            $Transction->description = 'admin';
            $Transction->status = 1;
            if ($Transction->save()) {
                if ($Transction->id) {

                    // Plus Coin in User
                    User::where('id', $request->user_id)->increment('wallet_balance', $package->coin);

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

            Ads_Transaction::where('id', $id)->delete();
            return redirect()->route('adtransaction.index')->with('success', __('Label.data_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
