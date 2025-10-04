<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class LoginController extends Controller
{
    protected $redirectTo = '/user/dashboard';

    public function __construct()
    {
        $this->middleware('guest:user')->except('logout');
    }

    public function login()
    {
        try {
            Auth::guard('user')->logout();
            return view('user.login.login');
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
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
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $credentials = $request->only('email', 'password');

            if (Auth::guard('user')->attempt($credentials)) {
                $user = Auth::guard('user')->user();
                return response()->json(['status' => 200, 'success' => __('Label.success_login')]);
            }

            return response()->json(['status' => 400, 'errors' => __('Label.error_login')]);

        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    public function logout()
    {
        try {
            Auth::guard('user')->logout();
            return redirect()->route('user.login')->with('success', __('Label.logout_successfully'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
