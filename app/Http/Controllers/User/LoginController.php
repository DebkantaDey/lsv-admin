<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class LoginController extends Controller
{
    protected $redirectTo = 'user/login';

    public function __construct()
    {
        try {
            $this->middleware('guest', ['except' => 'logout']);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function login()
    {
        try {
            Auth()->guard('user')->logout();
            return view('user.login.login');
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function save_login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required|min:4',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            $requestData = $request->all();

            if (Auth()->guard('user')->attempt(['email' => $requestData['email'], 'password' => $requestData['password'], 'user_penal_status' => 1]) || Auth()->guard('user')->attempt(['mobile_number' => $requestData['email'], 'password' => $requestData['password'], 'user_penal_status' => 1])) {
                $user = auth()->guard('user')->user();
                return response()->json(array('status' => 200, 'success' => __('Label.success_login')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.error_login')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function logout()
    {
        try {
            Auth()->guard('user')->logout();
            return redirect()->route('user.login')->with('success', __('Label.logout_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
