<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Exception;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $User = User_Data();

            $params['data'] = [];
            $params['user_id'] = $User['id'];

            return view('user.changepassword.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|min:4',
                'confirm_password' => 'required|min:4|same:new_password',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $user = User::where('id', $id)->first();

            if (isset($user) && $user != null) {

                if (Hash::check($request['current_password'], $user['password'])) {

                    $user->password = Hash::make($request['new_password']);
                    if ($user->save()) {
                        return response()->json(array('status' => 200, 'success' => 'Password Change Successfully.'));
                    }
                } else {
                    return response()->json(array('status' => 400, 'errors' => 'Please Enter Right Current Password.'));
                }
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_updated')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
