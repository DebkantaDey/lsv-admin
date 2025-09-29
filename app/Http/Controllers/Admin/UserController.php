<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block_Channel;
use App\Models\Comment;
use App\Models\Common;
use App\Models\Content;
use App\Models\Content_Report;
use App\Models\Episode;
use App\Models\Hashtag;
use App\Models\History;
use App\Models\Interests_Category;
use App\Models\Interests_Hashtag;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Playlist_Content;
use App\Models\Read_Notification;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\View;
use App\Models\Watch_later;
use App\Models\Withdrawal_Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

// Login Type : 1= OTP, 2= Goggle, 3= Apple, 4= Normal
class UserController extends Controller
{
    private $folder = "user";
    private $folder_content = "content";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $params['data'] = [];

            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_type = $request['input_type'];
                $input_login_type = $request['input_login_type'];

                $query = User::latest();
                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('full_name', 'LIKE', "%{$input_search}%")
                            ->orWhere('channel_name', 'LIKE', "%{$input_search}%")
                            ->orWhere('email', 'LIKE', "%{$input_search}%")
                            ->orWhere('mobile_number', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_login_type !== 'all') {
                    $query->where('type', $input_login_type);
                }
                if ($input_type == 'today') {
                    $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == 'month') {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == 'year') {
                    $query->whereYear('created_at', date('Y'));
                }
                $data = $query->get();

                $this->common->imageNameToUrl($data, 'image', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $delete = '<form onsubmit="return confirm(\'Are you sure !!! You want to Delete this User ?\');" method="POST" action="' . route('user.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="edit-delete-btn" style="outline: none;" title="Delete"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a href="' . route('user.wallet', [$row->id]) . '" class="edit-delete-btn mr-2" title="Wallet"><i class="fa-solid fa-wallet fa-xl"></i></a>';
                        $btn .= '<a href="' . route('user.edit', [$row->id]) . '" class="edit-delete-btn mr-2" title="Edit"><i class="fa-solid fa-pen-to-square fa-xl"></i></a>';
                        $btn .= $delete;
                        $btn .= '</div>';

                        return $btn;
                    })
                    ->addColumn('date', function ($row) {
                        return date("Y-m-d", strtotime($row->created_at));
                    })
                    ->addColumn('penal_status', function ($row) {
                        $status = $row->user_penal_status == 1 ? 'ON' : 'OFF';
                        $color = $row->user_penal_status == 1 ? '#058f00' : '#e3000b';

                        return "<button type='button' id='$row->id' onclick='change_status($row->id, $row->user_penal_status)' style='background:$color; font-weight:bold; border: none; color: white; padding: 5px 15px; outline: none; border-radius: 5px; cursor: pointer;'>$status</button>";
                    })
                    ->rawColumns(['action', 'penal_status'])
                    ->make(true);
            }

            return view('admin.user.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create()
    {
        try {
            $params['data'] = [];
            return view('admin.user.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'channel_name' => 'required|min:2|unique:tbl_user,channel_name',
                'description' => 'required',
                'full_name' => 'required|min:2',
                'email' => 'required|unique:tbl_user|email',
                'password' => 'required|min:4',
                'country_code' => 'required',
                'country_name' => 'required',
                'mobile_number' => [
                    'required',
                    'numeric',
                    Rule::unique('tbl_user')->where(function ($query) use ($request) {
                        return $query->where('country_code', $request->country_code)
                            ->where('mobile_number', $request->mobile_number);
                    }),
                ],
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
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

            $requestData['channel_id'] = Str::random(8);
            $requestData['password'] = Hash::make($requestData['password']);
            $files = $requestData['image'];
            $requestData['image'] = $this->common->saveImage($files, $this->folder);
            $requestData['cover_img'] = '';
            if (isset($request['cover_img'])) {
                $files1 = $request['cover_img'];
                $requestData['cover_img'] = $this->common->saveImage($files1, $this->folder);
            }
            $requestData['type'] = 4;
            $requestData['device_type'] = 0;
            $requestData['device_token'] = "";
            $requestData['website'] = isset($request->website) ? $request->website : '';
            $requestData['facebook_url'] = isset($request->facebook_url) ? $request->facebook_url : '';
            $requestData['instagram_url'] = isset($request->instagram_url) ? $request->instagram_url : '';
            $requestData['twitter_url'] = isset($request->twitter_url) ? $request->twitter_url : '';
            $requestData['wallet_balance'] = 0;
            $requestData['wallet_earning'] = 0;
            $requestData['bank_name'] = isset($request->bank_name) ? $request->bank_name : '';
            $requestData['bank_code'] = isset($request->bank_code) ? $request->bank_code : '';
            $requestData['bank_address'] = isset($request->bank_address) ? $request->bank_address : '';
            $requestData['ifsc_no'] = isset($request->ifsc_no) ? $request->ifsc_no : '';
            $requestData['account_no'] = isset($request->account_no) ? $request->account_no : '';
            $requestData['id_proof'] = '';
            if (isset($request['id_proof'])) {
                $files2 = $request['id_proof'];
                $requestData['id_proof'] = $this->common->saveImage($files2, $this->folder);
            }
            $requestData['user_penal_status'] = 0;
            $requestData['status'] = 1;

            $user_data = User::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($user_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_added')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function edit($id)
    {
        try {

            $params['data'] = User::where('id', $id)->first();
            if ($params['data'] != null) {

                $this->common->imageNameToUrl(array($params['data']), 'image', $this->folder);
                $this->common->imageNameToUrl(array($params['data']), 'cover_img', $this->folder);
                $this->common->imageNameToUrl(array($params['data']), 'id_proof', $this->folder);

                return view('admin.user.edit', $params);
            } else {
                return redirect()->back()->with('error', __('Label.page_not_found'));
            }
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
                'country_code' => 'required',
                'country_name' => 'required',
                'mobile_number' => [
                    'required',
                    'numeric',
                    Rule::unique('tbl_user')->where(function ($query) use ($request, $id) {
                        return $query->where('country_code', $request->country_code)
                            ->where('mobile_number', $request->mobile_number)
                            ->where('id', '!=', $id);
                    }),
                ],
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

            if ($requestData['password'] != null && isset($requestData['password'])) {
                $requestData['password'] = Hash::make($request->password);
            } else {
                unset($requestData['password']);
            }
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

            $User_data = User::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($User_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_edit_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_updated')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function destroy($id)
    {
        try {
            $data = User::where('id', $id)->first();
            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['image']);
                $this->common->deleteImageToFolder($this->folder, $data['cover_img']);
                $this->common->deleteImageToFolder($this->folder, $data['id_proof']);
                $data->delete();

                // Releted Data Delete
                Block_Channel::where('user_id', $id)->delete();
                Block_Channel::where('block_user_id', $id)->delete();
                History::where('user_id', $id)->delete();
                Interests_Hashtag::where('user_id', $id)->delete();
                Interests_Category::where('user_id', $id)->delete();
                Notification::where('from_user_id', $id)->delete();
                Read_Notification::where('user_id', $id)->delete();
                Subscriber::where('user_id', $id)->delete();
                Subscriber::where('to_user_id', $id)->delete();
                Watch_later::where('user_id', $id)->delete();

                $this->deleteChannelContent($data['channel_id']);
            }
            return redirect()->route('user.index')->with('success', __('Label.data_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function wallet($id, Request $request)
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

                return view('admin.user.wallet', $params);
            } else {
                return redirect()->back()->with('error', __('Label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function userPenalStatus($id)
    {
        try {

            $data = User::where('id', $id)->first();
            if ($data->user_penal_status == 0) {
                $data->user_penal_status = 1;

                // Send Mail (Type = 1- Register, 2- Transaction, 3- Report, 4- User Penal Active)
                $this->common->Send_Mail(4, $data['email']);
            } elseif ($data->user_penal_status == 1) {
                $data->user_penal_status = 0;
            } else {
                $data->user_penal_status = 0;
            }
            $data->save();
            return response()->json(array('status' => 200, 'success' => 'Status Changed', 'id' => $data->id, 'Status_Code' => $data->user_penal_status));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    // Channel Content Delete
    public function deleteChannelContent($channelId)
    {
        $contents = Content::whereIn('content_type', [1, 3, 4, 5])->where('channel_id', $channelId)->get();

        foreach ($contents as $content) {
            $contentId = $content->id;

            if (in_array($content->content_type, [1, 2])) {
                $oldHashtags = explode(',', $content->hashtag_id);
                Hashtag::whereIn('id', $oldHashtags)->decrement('total_used', 1);
            }

            $this->common->deleteImageToFolder($this->folder_content, $content->portrait_img);
            $this->common->deleteImageToFolder($this->folder_content, $content->landscape_img);
            $this->common->deleteImageToFolder($this->folder_content, $content->content);

            if ($content->content_type == 3) {
                $episodes = Episode::where('podcasts_id', $contentId)->get();
                foreach ($episodes as $episode) {
                    $this->common->deleteImageToFolder($this->folder_content, $episode->portrait_img);
                    $this->common->deleteImageToFolder($this->folder_content, $episode->landscape_img);
                    $this->common->deleteImageToFolder($this->folder_content, $episode->episode_audio);
                    $episode->delete();
                }
            }

            if ($content->content_type == 5) {
                Playlist_Content::where('channel_id', $channelId)->delete();
            }

            // Delete Related Data
            Comment::where('content_id', $contentId)->delete();
            Content_Report::where('content_id', $contentId)->delete();
            History::where('content_id', $contentId)->delete();
            Like::where('content_id', $contentId)->delete();
            Notification::where('content_id', $contentId)->delete();
            View::where('content_id', $contentId)->delete();
            Watch_later::where('content_id', $contentId)->delete();

            $content->delete();
        }
    }
}
