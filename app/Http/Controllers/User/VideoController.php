<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Common;
use App\Models\Language;
use App\Models\Hashtag;
use App\Models\Content;
use App\Models\Content_Report;
use App\Models\History;
use App\Models\Like;
use App\Models\Notification;
use App\Models\View;
use App\Models\Watch_later;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class VideoController extends Controller
{
    private $folder = "content";
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

            $params['category'] = Category::where('type', 1)->orderby('name', 'asc')->latest()->get();
            $params['language'] = Language::orderby('name', 'asc')->latest()->get();

            $input_search = $request['input_search'];
            $input_category = $request['input_category'];
            $input_language = $request['input_language'];
            $input_rent = $request['input_rent'];

            if ($input_search != null && isset($input_search)) {

                if ($input_category != 0 && $input_language == 0) {

                    if ($input_rent == 1 || $input_rent == 2) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('channel_id', $user['channel_id'])->where('content_type', 1)->where('is_rent', $input_rent)
                            ->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('channel_id', $user['channel_id'])->where('content_type', 1)
                            ->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_category == 0 && $input_language != 0) {

                    if ($input_rent == 1 || $input_rent == 2) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('channel_id', $user['channel_id'])->where('content_type', 1)->where('is_rent', $input_rent)
                            ->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('channel_id', $user['channel_id'])->where('content_type', 1)
                            ->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_category != 0 && $input_language != 0) {

                    if ($input_rent == 1 || $input_rent == 2) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('channel_id', $user['channel_id'])->where('content_type', 1)->where('is_rent', $input_rent)
                            ->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('channel_id', $user['channel_id'])->where('content_type', 1)
                            ->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    }
                } else {

                    if ($input_rent == 1 || $input_rent == 2) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('channel_id', $user['channel_id'])->where('content_type', 1)->where('is_rent', $input_rent)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('channel_id', $user['channel_id'])->where('content_type', 1)->orderBy('id', 'DESC')->paginate(15);
                    }
                }
            } else {

                if ($input_category != 0 && $input_language == 0) {

                    if ($input_rent == 1 || $input_rent == 2) {
                        $params['data'] = Content::where('channel_id', $user['channel_id'])->where('content_type', 1)->where('is_rent', $input_rent)->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('channel_id', $user['channel_id'])->where('content_type', 1)->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_category == 0 && $input_language != 0) {

                    if ($input_rent == 1 || $input_rent == 2) {
                        $params['data'] = Content::where('channel_id', $user['channel_id'])->where('content_type', 1)->where('is_rent', $input_rent)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('channel_id', $user['channel_id'])->where('content_type', 1)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_category != 0 && $input_language != 0) {

                    if ($input_rent == 1 || $input_rent == 2) {
                        $params['data'] = Content::where('channel_id', $user['channel_id'])->where('content_type', 1)->where('is_rent', $input_rent)->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('channel_id', $user['channel_id'])->where('content_type', 1)->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    }
                } else {

                    if ($input_rent == 1 || $input_rent == 2) {
                        $params['data'] = Content::where('channel_id', $user['channel_id'])->where('content_type', 1)->where('is_rent', $input_rent)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('channel_id', $user['channel_id'])->where('content_type', 1)->orderBy('id', 'DESC')->paginate(15);
                    }
                }
            }

            $this->common->imageNameToUrl($params['data'], 'portrait_img', $this->folder);
            $this->common->imageNameToUrl($params['data'], 'landscape_img', $this->folder);
            for ($i = 0; $i < count($params['data']); $i++) {
                if ($params['data'][$i]['content_upload_type'] == 'server_video') {
                    $this->common->videoNameToUrl(array($params['data'][$i]), 'content', $this->folder);
                }
            }

            return view('user.video.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function create()
    {
        try {

            $params['data'] = [];
            $params['category'] = Category::where('type', 1)->orderBy('name', 'asc')->latest()->get();
            $params['language'] = Language::orderBy('name', 'asc')->latest()->get();

            return view('user.video.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $user = User_Data();

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'category_id' => 'required',
                'language_id' => 'required',
                'portrait_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'content_upload_type' => 'required',
                'is_rent' => 'required',
                'is_comment' => 'required',
                'is_like' => 'required',
                'landscape_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'content_duration' => 'required|after_or_equal:00:00:01',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            if ($request->is_rent == 1) {
                $validator1 = Validator::make($request->all(), [
                    'rent_price' => 'required|numeric|min:0',
                ]);
                if ($validator1->fails()) {
                    $errs1 = $validator1->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs1));
                }
            }
            if ($request->content_upload_type == 'server_video') {
                $validator2 = Validator::make($request->all(), [
                    'video' => 'required',
                ]);
            } else {
                $validator2 = Validator::make($request->all(), [
                    'url' => 'required',
                ]);
            }
            if ($validator2->fails()) {
                $errs2 = $validator2->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs2));
            }

            $requestData = $request->all();
            $requestData['is_download'] = 0;

            $requestData['channel_id'] = $user['channel_id'];
            $requestData['artist_id'] = 0;
            $hashtag_id = $this->common->checkHashTag($requestData['description']);
            $hashtagId = 0;
            if (count($hashtag_id) > 0) {
                $hashtagId = implode(',', $hashtag_id);
            }
            $requestData['hashtag_id'] = $hashtagId;
            $files1 = $requestData['portrait_img'];
            $files2 = $requestData['landscape_img'];
            $requestData['portrait_img'] = $this->common->saveImage($files1, $this->folder);
            $requestData['landscape_img'] = $this->common->saveImage($files2, $this->folder);
            $requestData['content_type'] = 1;
            if ($requestData['content_upload_type'] == 'server_video') {

                $requestData['content'] = $requestData['video'];
                $requestData['content_size'] = $this->common->getFileSize($requestData['video'], $this->folder);
            } else {
                $requestData['content'] = $requestData['url'];
                $requestData['content_size'] = 0;
            }
            if ($requestData['is_rent'] == 0) {
                $requestData['rent_price'] = 0;
            }
            unset($requestData['video'], $requestData['url']);
            $requestData['content_duration'] = Time_To_Milliseconds($requestData['content_duration']);
            $requestData['total_view'] = 0;
            $requestData['total_like'] = 0;
            $requestData['total_dislike'] = 0;
            $requestData['playlist_type'] = 0;
            $requestData['is_admin_added'] = 1;

            $video_data = Content::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($video_data->id)) {
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

            $params['data'] = Content::where('id', $id)->first();
            if ($params['data'] != null) {

                $params['category'] = Category::where('type', 1)->orderby('name', 'asc')->latest()->get();
                $params['language'] = Language::orderby('name', 'asc')->latest()->get();

                $this->common->imageNameToUrl(array($params['data']), 'portrait_img', $this->folder);
                $this->common->imageNameToUrl(array($params['data']), 'landscape_img', $this->folder);
                if ($params['data']['content_upload_type'] == 'server_video') {
                    $this->common->videoNameToUrl(array($params['data']), 'content', $this->folder);
                }

                return view('user.video.edit', $params);
            } else {
                return redirect()->back()->with('error', __('Label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update(Request $request)
    {
        try {
            $user = User_Data();

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'category_id' => 'required',
                'language_id' => 'required',
                'portrait_img' => 'image|mimes:jpeg,png,jpg|max:2048',
                'content_upload_type' => 'required',
                'is_rent' => 'required',
                'is_comment' => 'required',
                'is_like' => 'required',
                'landscape_img' => 'image|mimes:jpeg,png,jpg|max:2048',
                'content_duration' => 'required|after_or_equal:00:00:01',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            if ($request->is_rent == 1) {
                $validator1 = Validator::make($request->all(), [
                    'rent_price' => 'required|numeric|min:0',
                ]);
                if ($validator1->fails()) {
                    $errs1 = $validator1->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs1));
                }
            }
            if ($request->content_upload_type != 'server_video') {
                $validator2 = Validator::make($request->all(), [
                    'url' => 'required',
                ]);
                if ($validator2->fails()) {
                    $errs2 = $validator2->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs2));
                }
            }

            $requestData = $request->all();
            $requestData['is_download'] = 0;
            $requestData['channel_id'] = $user['channel_id'];

            $old_hashtag = explode(',', $requestData['old_hashtag_id']);
            Hashtag::whereIn('id', $old_hashtag)->decrement('total_used', 1);
            $hashtag_id = $this->common->checkHashTag($requestData['description']);
            $hashtagId = 0;
            if (count($hashtag_id) > 0) {
                $hashtagId = implode(',', $hashtag_id);
            }
            $requestData['hashtag_id'] = $hashtagId;

            if (isset($requestData['portrait_img'])) {
                $files = $requestData['portrait_img'];
                $requestData['portrait_img'] = $this->common->saveImage($files, $this->folder);
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_portrait_img']));
            }
            if (isset($requestData['landscape_img'])) {
                $files1 = $requestData['landscape_img'];
                $requestData['landscape_img'] = $this->common->saveImage($files1, $this->folder);
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_landscape_img']));
            }
            if ($requestData['content_upload_type'] == 'server_video') {

                if ($requestData['content_upload_type'] == $requestData['content_upload_type']) {

                    if ($requestData['video']) {

                        $requestData['content'] = $requestData['video'];
                        $this->common->deleteImageToFolder($this->folder, basename($requestData['old_content']));
                        $requestData['content_size'] = $this->common->getFileSize($requestData['video'], $this->folder);
                    }
                } else {

                    if ($requestData['video']) {

                        $requestData['content'] = $requestData['video'];
                        $this->common->deleteImageToFolder($this->folder, basename($requestData['old_content']));
                        $requestData['content_size'] = $this->common->getFileSize($requestData['video'], $this->folder);
                    } else {
                        $requestData['content'] = '';
                    }
                }
            } else {
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_content']));
                $requestData['content_size'] = 0;

                $requestData['content'] = "";
                if ($requestData['url']) {
                    $requestData['content'] = $requestData['url'];
                }
            }
            if ($requestData['is_rent'] == 0) {
                $requestData['rent_price'] = 0;
            }
            unset($requestData['video'], $requestData['url'], $requestData['old_content_upload_type'], $requestData['old_hashtag_id'], $requestData['old_content'], $requestData['old_portrait_img'], $requestData['old_landscape_img']);
            $requestData['content_duration'] = Time_To_Milliseconds($requestData['content_duration']);

            $video_data = Content::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($video_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_edit_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_updated')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {
 
            $data = Content::where('id', $id)->first();
            if (isset($data)) {

                $old_hashtag = explode(',', $data['hashtag_id']);
                Hashtag::whereIn('id', $old_hashtag)->decrement('total_used', 1);

                $this->common->deleteImageToFolder($this->folder, $data['portrait_img']);
                $this->common->deleteImageToFolder($this->folder, $data['landscape_img']);
                $this->common->deleteImageToFolder($this->folder, $data['content']);
                $data->delete();

                // Video Releted Data Delete
                Comment::where('content_id', $id)->delete();
                Content_Report::where('content_id', $id)->delete();
                History::where('content_id', $id)->delete();
                Like::where('content_id', $id)->delete();
                Notification::where('content_id', $id)->delete();
                View::where('content_id', $id)->delete();
                Watch_later::where('content_id', $id)->delete();
            }

            return redirect()->route('uvideo.index')->with('success', __('Label.data_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    // Status Change
    public function changeStatus(Request $request)
    {
        try {
            if (Auth::guard('admin')->user()->type != 1) {
                return response()->json(array('status' => 400, 'errors' => __('Label.you_have_no_right_to_add_edit_and_delete')));
            } else {

                $data = Content::where('id', $request->id)->first();
                if ($data->status == 0) {
                    $data->status = 1;
                } elseif ($data->status == 1) {
                    $data->status = 0;
                } else {
                    $data->status = 0;
                }
                $data->save();
                return response()->json(array('status' => 200, 'success' => 'Status Changed', 'id' => $data->id, 'Status' => $data->status));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
