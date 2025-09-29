<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Exception;

class ProfileController extends Controller
{
    private $folder = "user";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['data'] = [];
            $user = User_Data();

            $params['data'] = User::where('id', $user['id'])->first();
            // Image Name to URL
            $this->common->imageNameToUrl(array($params['data']), 'image', $this->folder);
            $this->common->imageNameToUrl(array($params['data']), 'cover_img', $this->folder);
            $this->common->imageNameToUrl(array($params['data']), 'id_proof', $this->folder);

            return view('user.profile.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'channel_name' => 'required|unique:tbl_user,channel_name,' . $id,
                'description' => 'required',
                'full_name' => 'required|min:2',
                'email' => 'required|email|unique:tbl_user,email,' . $id,
                'mobile_number' => 'required|numeric|unique:tbl_user,mobile_number,' . $id,
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
                'cover_img' => 'image|mimes:jpeg,png,jpg|max:2048',
                'id_proof' => 'image|mimes:jpeg,png,jpg|max:2048',
                'address' => 'required|min:2',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
                'pincode' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $requestData['website'] = isset($request->website) ? $request->website : '';
            $requestData['facebook_url'] = isset($request->facebook_url) ? $request->facebook_url : '';
            $requestData['instagram_url'] = isset($request->instagram_url) ? $request->instagram_url : '';
            $requestData['twitter_url'] = isset($request->twitter_url) ? $request->twitter_url : '';
            $requestData['bank_name'] = isset($request->bank_name) ? $request->bank_name : '';
            $requestData['bank_code'] = isset($request->bank_code) ? $request->bank_code : '';
            $requestData['bank_address'] = isset($request->bank_address) ? $request->bank_address : '';
            $requestData['ifsc_no'] = isset($request->ifsc_no) ? $request->ifsc_no : '';
            $requestData['account_no'] = isset($request->account_no) ? $request->account_no : '';

            if (isset($request['image'])) {
                $files = $request['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']));
            }
            if (isset($request['cover_img'])) {
                $files1 = $request['cover_img'];
                $requestData['cover_img'] = $this->common->saveImage($files1, $this->folder);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_cover_img']));
            }
            if (isset($request['id_proof'])) {
                $files2 = $request['id_proof'];
                $requestData['id_proof'] = $this->common->saveImage($files2, $this->folder);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_id_proof']));
            }
            unset($requestData['old_image'], $requestData['old_cover_img'], $requestData['old_id_proof']);

            $user_data = User::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($user_data->id)) {

                return response()->json(array('status' => 200, 'success' => __('Label.data_edit_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_updated')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
