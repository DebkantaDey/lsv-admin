<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Common;
use App\Models\Language;
use App\Models\Hashtag;
use App\Models\User;
use App\Models\Content;
use App\Models\Content_Report;
use App\Models\History;
use App\Models\Like;
use App\Models\Notification;
use App\Models\View;
use App\Models\Watch_later;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Http;

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

            $params['data'] = [];
            $params['channel'] = User::orderby('channel_name', 'asc')->latest()->get();
            $params['category'] = Category::where('type', 1)->orderby('name', 'asc')->latest()->get();
            $params['language'] = Language::orderby('name', 'asc')->latest()->get();

            $input_search = $request['input_search'];
            $input_channel = $request['input_channel'];
            $input_category = $request['input_category'];
            $input_language = $request['input_language'];
            $input_rent = $request['input_rent'];

            if ($input_search != null && isset($input_search)) {

                if ($input_channel != 0 && $input_category == 0 && $input_language == 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 1)
                            ->where('channel_id', $input_channel)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 0)
                            ->where('channel_id', $input_channel)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)
                            ->where('channel_id', $input_channel)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_channel == 0 && $input_category != 0 && $input_language == 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 1)
                            ->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 0)
                            ->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)
                            ->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_channel == 0 && $input_category == 0 && $input_language != 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 1)
                            ->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 0)
                            ->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)
                            ->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_channel != 0 && $input_category != 0 && $input_language == 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 1)
                            ->where('channel_id', $input_channel)->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 0)
                            ->where('channel_id', $input_channel)->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)
                            ->where('channel_id', $input_channel)->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_channel != 0 && $input_category == 0 && $input_language != 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 1)
                            ->where('channel_id', $input_channel)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 0)
                            ->where('channel_id', $input_channel)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)
                            ->where('channel_id', $input_channel)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_channel == 0 && $input_category != 0 && $input_language != 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 1)
                            ->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 0)
                            ->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)
                            ->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_channel != 0 && $input_category != 0 && $input_language != 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 1)
                            ->where('channel_id', $input_channel)->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 0)
                            ->where('channel_id', $input_channel)->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)
                            ->where('channel_id', $input_channel)->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    }
                } else {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 1)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->where('is_rent', 0)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 1)->orderBy('id', 'DESC')->paginate(15);
                    }
                }
            } else {

                if ($input_channel != 0 && $input_category == 0 && $input_language == 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 1)->where('channel_id', $input_channel)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 0)->where('channel_id', $input_channel)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('content_type', 1)->where('channel_id', $input_channel)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_channel == 0 && $input_category != 0 && $input_language == 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 1)->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 0)->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('content_type', 1)->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_channel == 0 && $input_category == 0 && $input_language != 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 1)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 0)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('content_type', 1)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_channel != 0 && $input_category != 0 && $input_language == 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 1)->where('channel_id', $input_channel)->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 0)->where('channel_id', $input_channel)->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('content_type', 1)->where('channel_id', $input_channel)->where('category_id', $input_category)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_channel != 0 && $input_category == 0 && $input_language != 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 1)->where('channel_id', $input_channel)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 0)->where('channel_id', $input_channel)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('content_type', 1)->where('channel_id', $input_channel)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_channel == 0 && $input_category != 0 && $input_language != 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 1)->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 2)->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('content_type', 1)->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    }
                } elseif ($input_channel != 0 && $input_category != 0 && $input_language != 0) {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 1)->where('channel_id', $input_channel)->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 0)->where('channel_id', $input_channel)->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('content_type', 1)->where('channel_id', $input_channel)->where('category_id', $input_category)->where('language_id', $input_language)->orderBy('id', 'DESC')->paginate(15);
                    }
                } else {

                    if ($input_rent == 1) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 1)->orderBy('id', 'DESC')->paginate(15);
                    } else if ($input_rent == 2) {
                        $params['data'] = Content::where('content_type', 1)->where('is_rent', 0)->orderBy('id', 'DESC')->paginate(15);
                    } else {
                        $params['data'] = Content::where('content_type', 1)->orderBy('id', 'DESC')->paginate(15);
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

            return view('admin.video.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function create()
    {
        try {
            $params['data'] = [];

            $params['category'] = Category::where('type', 1)->orderBy('name', 'asc')->latest()->get();
            $params['channel'] = User::orderBy('channel_name', 'asc')->latest()->get();
            $params['language'] = Language::orderBy('name', 'asc')->latest()->get();

            return view('admin.video.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'channel_id' => 'required',
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

            $requestData['artist_id'] = 0;
            $requestData['is_download'] = 0;
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

                $params['channel'] = User::orderby('channel_name', 'asc')->latest()->get();
                $params['category'] = Category::where('type', 1)->orderby('name', 'asc')->latest()->get();
                $params['language'] = Language::orderby('name', 'asc')->latest()->get();

                $this->common->imageNameToUrl(array($params['data']), 'portrait_img', $this->folder);
                $this->common->imageNameToUrl(array($params['data']), 'landscape_img', $this->folder);
                if ($params['data']['content_upload_type'] == 'server_video') {
                    $this->common->videoNameToUrl(array($params['data']), 'content', $this->folder);
                }

                return view('admin.video.edit', $params);
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
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'channel_id' => 'required',
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

                if ($requestData['content_upload_type'] == $requestData['old_content_upload_type']) {

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

            return redirect()->route('video.index')->with('success', __('Label.data_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    // Status Change
    public function changeStatus(Request $request)
    {
        try {

            $data = Content::where('id', $request->id)->with('channel')->first();
            if ($data->status == 0) {
                $data->status = 1;
            } elseif ($data->status == 1) {
                $data->status = 0;

                // Send Notification
                if ($data['channel'] != null || $data['content_type'] == 1 || $data['content_type'] == 3 || $data['content_type'] == 4) {

                    $title = App_Name() . ' Hide Your ' . $data['title'] . ' Post.';
                    $this->common->save_notification(5, $title, 0, $data['channel']['id'], $data['id']);
                }
            } else {
                $data->status = 0;
            }
            $data->save();
            return response()->json(array('status' => 200, 'success' => 'Status Changed', 'id' => $data->id, 'Status' => $data->status));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    // Save Chunk
    public function saveChunk()
    {
        @set_time_limit(5 * 60);

        $targetDir = storage_path('/app/public/content');
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

        // Remove old temp files
        if ($cleanupTargetDir && is_dir($targetDir) && $dir = opendir($targetDir)) {
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // Remove temp file if it is older than the max age and not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        } else {
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
        }

        // Open temp file
        if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off
            rename("{$filePath}.part", $filePath);

            // Generate a new filename based on the current date and time
            $extension = pathinfo($fileName, PATHINFO_EXTENSION); // Get the file extension from the original filename
            $newFileName = 'vid' . date('_d_m_Y_') . rand(1111, 9999) . '.' . $extension; // Use the extracted extension
            $newFilePath = $targetDir . DIRECTORY_SEPARATOR . $newFileName;

            // Rename the uploaded file to the new filename
            rename($filePath, $newFilePath);

            // Send the new file name back to the client
            die(json_encode(array('jsonrpc' => '2.0', 'result' => $newFileName, 'id' => 'id')));
        }

        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }

    // Import Video
    public function importVideo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'channel_id' => 'required',
                'category_id' => 'required',
                'language_id' => 'required',
                'youtube_channel_id' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $channel_id = $request->channel_id;
            $category_id = $request->category_id;
            $language_id = $request->language_id;
            $youtube_channel_id = $request->youtube_channel_id;

            $url = 'https://www.googleapis.com/youtube/v3/search?key=AIzaSyCkMEOzA2mOEwO9bWPXEx-oYikQ7TV0RiE&channelId=' . $youtube_channel_id . '&part=snippet,id&order=date&type=video&maxResults=50';
            $response = Http::get($url);
            $status = $response->getStatusCode();

            if ($status == 200) {

                $Data = (array) $response->json();

                if (isset($Data['items']) && count($Data['items']) > 0) {

                    for ($i = 0; $i < count($Data['items']); $i++) {

                        if ($Data['items'][$i]['id']['kind'] == "youtube#video") {

                            $insert = new Content();
                            $insert['content_type'] = 1;
                            $insert['channel_id'] = $channel_id;
                            $insert['category_id'] = $category_id;
                            $insert['language_id'] = $language_id;
                            $insert['artist_id'] = 0;
                            $hashtag_id = $this->common->checkHashTag($Data['items'][$i]['snippet']['description']);
                            $hashtagId = 0;
                            if (count($hashtag_id) > 0) {
                                $hashtagId = implode(',', $hashtag_id);
                            }
                            $insert['hashtag_id'] = $hashtagId;
                            $insert['title'] = $Data['items'][$i]['snippet']['title'];
                            $insert['description'] = $Data['items'][$i]['snippet']['description'];
                            if (isset($Data['items'][$i]['snippet']['thumbnails']['medium']['url'])) {

                                $url = $Data['items'][$i]['snippet']['thumbnails']['medium']['url'];
                                $insert['portrait_img'] = $this->URLSaveInImage($url);
                            } else {
                                $insert['portrait_img'] = "";
                            }
                            if (isset($Data['items'][$i]['snippet']['thumbnails']['medium']['url'])) {

                                $url1 = $Data['items'][$i]['snippet']['thumbnails']['medium']['url'];
                                $insert['landscape_img'] = $this->URLSaveInImage($url1);
                            } else {
                                $insert['landscape_img'] = "";
                            }
                            $insert['content_upload_type'] = 'youtube';
                            $insert['content'] = $Data['items'][$i]['id']['videoId'];
                            $insert['content_size'] = 0;
                            $insert['content_duration'] = 0;
                            $insert['is_rent'] = 0;
                            $insert['rent_price'] = 0;
                            $insert['is_comment'] = 1;
                            $insert['is_download'] = 0;
                            $insert['is_like'] = 1;
                            $insert['total_view'] = 0;
                            $insert['total_like'] = 0;
                            $insert['total_dislike'] = 0;
                            $insert['playlist_type'] = 0;
                            $insert['is_admin_added'] = 1;
                            $insert['status'] = 1;
                            $insert->save();
                        }
                    }

                    // Pagination
                    $TotalResult = $Data['pageInfo']['totalResults'] - 1;
                    $ResultsPerPage = $Data['pageInfo']['resultsPerPage'];

                    if ($TotalResult > $ResultsPerPage) {

                        $NextPageToken = $Data['nextPageToken'];

                        $minus = $TotalResult - $ResultsPerPage;
                        $dived = $minus / $ResultsPerPage;
                        $rouned = (int) ceil($dived);

                        for ($i = 0; $i < $rouned; $i++) {

                            $url1 = 'https://www.googleapis.com/youtube/v3/search?key=AIzaSyCkMEOzA2mOEwO9bWPXEx-oYikQ7TV0RiE&channelId=' . $youtube_channel_id . '&pageToken=' . $NextPageToken . '&part=snippet,id&order=date&type=video&maxResults=50';
                            $response1 = Http::get($url1);
                            $status1 = $response1->getStatusCode();

                            if ($status1 == 200) {

                                $Data1 = (array) $response1->json();

                                if ($i != $dived - 1 && isset($Data1['nextPageToken'])) {
                                    $NextPageToken = $Data1['nextPageToken'];
                                }
                                if (isset($Data1['items']) && count($Data1['items']) > 0) {

                                    for ($j = 0; $j < count($Data1['items']); $j++) {

                                        if ($Data1['items'][$j]['id']['kind'] == "youtube#video") {

                                            $insert = new Content();
                                            $insert['content_type'] = 1;
                                            $insert['channel_id'] = $channel_id;
                                            $insert['category_id'] = $category_id;
                                            $insert['language_id'] = $language_id;
                                            $insert['artist_id'] = 0;
                                            $hashtag_id = $this->common->checkHashTag($Data1['items'][$j]['snippet']['description']);
                                            $hashtagId = 0;
                                            if (count($hashtag_id) > 0) {
                                                $hashtagId = implode(',', $hashtag_id);
                                            }
                                            $insert['hashtag_id'] = $hashtagId;
                                            $insert['title'] = $Data1['items'][$j]['snippet']['title'];
                                            $insert['description'] = $Data1['items'][$j]['snippet']['description'];
                                            if (isset($Data1['items'][$j]['snippet']['thumbnails']['medium']['url'])) {

                                                $url = $Data1['items'][$j]['snippet']['thumbnails']['medium']['url'];
                                                $insert['portrait_img'] = $this->URLSaveInImage($url);
                                            } else {
                                                $insert['portrait_img'] = "";
                                            }
                                            if (isset($Data1['items'][$j]['snippet']['thumbnails']['medium']['url'])) {

                                                $url1 = $Data1['items'][$j]['snippet']['thumbnails']['medium']['url'];
                                                $insert['landscape_img'] = $this->URLSaveInImage($url1);
                                            } else {
                                                $insert['landscape_img'] = "";
                                            }
                                            $insert['content_upload_type'] = 'youtube';
                                            $insert['content'] = $Data1['items'][$j]['id']['videoId'];
                                            $insert['content_size'] = 0;
                                            $insert['content_duration'] = 0;
                                            $insert['is_rent'] = 0;
                                            $insert['rent_price'] = 0;
                                            $insert['is_comment'] = 1;
                                            $insert['is_download'] = 0;
                                            $insert['is_like'] = 1;
                                            $insert['total_view'] = 0;
                                            $insert['total_like'] = 0;
                                            $insert['total_dislike'] = 0;
                                            $insert['playlist_type'] = 0;
                                            $insert['is_admin_added'] = 1;
                                            $insert['status'] = 1;
                                            $insert->save();
                                        }
                                    }
                                }
                            }
                        }
                    }

                    return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
                } else {
                    return response()->json(array('status' => 400, 'errors' => __('Label.data_not_added')));
                }
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.Channel_Not_Found')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function URLSaveInImage($url)
    {
        $ext = pathinfo($url);
        $image_name = date('d_m_Y_') . rand(0, 99) . '_' . uniqid() . '.' . $ext['extension'];
        $path = storage_path('app/public/') . $this->folder . '/';
        file_put_contents($path . $image_name, file_get_contents($url));
        return $image_name;
    }
}
