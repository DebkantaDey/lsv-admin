<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Common;
use App\Models\Content;
use App\Models\Content_Report;
use App\Models\Episode;
use App\Models\History;
use App\Models\Language;
use App\Models\Like;
use App\Models\Notification;
use App\Models\View;
use App\Models\Watch_later;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class PodcastsController extends Controller
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
            $params['category'] = Category::where('type', 2)->orderby('name', 'asc')->latest()->get();
            $params['language'] = Language::orderby('name', 'asc')->latest()->get();

            if ($request->ajax()) {

                $input_search = $request['input_search'];
                if ($input_search != null && isset($input_search)) {
                    $data = Content::where('channel_id', $user['channel_id'])->where('title', 'LIKE', "%{$input_search}%")->where('content_type', 4)->latest()->get();
                } else {
                    $data = Content::where('channel_id', $user['channel_id'])->latest()->where('content_type', 4)->get();
                }

                $this->common->imageNameToUrl($data, 'portrait_img', $this->folder);
                $this->common->imageNameToUrl($data, 'landscape_img', $this->folder);
                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $delete = ' <form onsubmit="return confirm(\'Are you sure !!! You want to Delete this Podcasts ?\');" method="POST"  action="' . route('upodcasts.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="edit-delete-btn" style="outline: none;" title="Delete"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around" title="Edit">';
                        $btn .= '<a class="edit-delete-btn edit_podcasts" title="Edit" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-title="' . $row->title . '" data-portrait_img="' . $row->portrait_img . '" data-landscape_img="' . $row->landscape_img . '" data-description="' . $row->description . '"  data-category_id="' . $row->category_id . '" data-language_id="' . $row->language_id . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {
                        if ($row->status == 1) {
                            return "<button type='button' style='background:#058f00; font-weight:bold; border: none; color: white; padding: 5px 15px; outline: none;border-radius: 5px;'>Show</button>";
                        } else {
                            return "<button type='button' style='background:#e3000b; font-weight:bold; border: none; color: white; padding: 5px 20px; outline: none;border-radius: 5px;'>Hide</button>";
                        }
                    })
                    ->addColumn('episode', function ($row) {
                        $btn = '<a href="' . route('upodcast.episode.index', $row->id) . '" class="btn text-white p-1 font-weight-bold" style="background:#4e45b8;"> Episode List</a> ';
                        return $btn;
                    })
                    ->rawColumns(['action', 'status', 'episode'])
                    ->make(true);
            }
            return view('user.podcasts.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $user = User_Data();

            $validator = Validator::make($request->all(), [
                'title' => 'required|min:2',
                'description' => 'required',
                'category_id' => 'required',
                'language_id' => 'required',
                'portrait_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'landscape_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $requestData['channel_id'] = $user['channel_id'];
            $files = $requestData['portrait_img'];
            $files1 = $requestData['landscape_img'];
            $requestData['portrait_img'] = $this->common->saveImage($files, $this->folder);
            $requestData['landscape_img'] = $this->common->saveImage($files1, $this->folder);

            $requestData['artist_id'] = 0;
            $hashtag_id = $this->common->checkHashTag($requestData['description']);
            $hashtagId = 0;
            if (count($hashtag_id) > 0) {
                $hashtagId = implode(',', $hashtag_id);
            }
            $requestData['hashtag_id'] = $hashtagId;
            $requestData['content_type'] = 4;
            $requestData['content_upload_type'] = '';
            $requestData['content'] = '';
            $requestData['content_size'] = '';
            $requestData['is_rent'] = 0;
            $requestData['rent_price'] = 0;
            $requestData['is_comment'] = 0;
            $requestData['is_download'] = 0;
            $requestData['is_like'] = 0;
            $requestData['total_view'] = 0;
            $requestData['total_like'] = 0;
            $requestData['total_dislike'] = 0;
            $requestData['playlist_type'] = 0;
            $requestData['is_admin_added'] = 1;
            $requestData['status'] = 1;

            $content_data = Content::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($content_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_added')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update($id, Request $request)
    {
        try {
            $user = User_Data();

            $validator = Validator::make($request->all(), [
                'title' => 'required|min:2',
                'description' => 'required',
                'category_id' => 'required',
                'language_id' => 'required',
                'portrait_img' => 'image|mimes:jpeg,png,jpg|max:2048',
                'landscape_img' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $requestData['channel_id'] = $user['channel_id'];
            if (isset($requestData['portrait_img'])) {
                $files = $requestData['portrait_img'];
                $requestData['portrait_img'] = $this->common->saveImage($files, $this->folder);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_portrait_img']));
            }
            if (isset($requestData['landscape_img'])) {
                $files = $requestData['landscape_img'];
                $requestData['landscape_img'] = $this->common->saveImage($files, $this->folder);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_landscape_img']));
            }
            unset($requestData['old_portrait_img'], $requestData['old_landscape_img']);

            $content_data = Content::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($content_data->id)) {
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
            $data = Content::where('id', $id)->first();

            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['portrait_img']);
                $this->common->deleteImageToFolder($this->folder, $data['landscape_img']);
                $data->delete();

                $episode = Episode::where('podcasts_id', $id)->get();
                for ($i = 0; $i < count($episode); $i++) {
                    $this->common->deleteImageToFolder($this->folder, $episode[$i]['portrait_img']);
                    $this->common->deleteImageToFolder($this->folder, $episode[$i]['landscape_img']);
                    $this->common->deleteImageToFolder($this->folder, $episode[$i]['episode_audio']);
                    $episode[$i]->delete();
                }

                // Content Releted Data Delete
                Comment::where('content_id', $id)->delete();
                Content_Report::where('content_id', $id)->delete();
                History::where('content_id', $id)->delete();
                Like::where('content_id', $id)->delete();
                Notification::where('content_id', $id)->delete();
                View::where('content_id', $id)->delete();
                Watch_later::where('content_id', $id)->delete();
            }

            return redirect()->route('upodcasts.index')->with('success', __('Label.data_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    // Episode
    public function PodcastIndex($id, Request $request)
    {
        try {
            User_Data();

            $params['data'] = [];
            $params['podcasts_id'] = $id;
            $input_search = $request['input_search'];

            if ($input_search != null && isset($input_search)) {
                $params['data'] = Episode::where('name', 'LIKE', "%{$input_search}%")->where('podcasts_id', $id)->orderBy('sortable', 'asc')->paginate(15);
            } else {
                $params['data'] = Episode::where('podcasts_id', $id)->orderBy('sortable', 'asc')->paginate(15);
            }

            $this->common->imageNameToUrl($params['data'], 'portrait_img', $this->folder);
            $this->common->imageNameToUrl($params['data'], 'landscape_img', $this->folder);
            for ($i = 0; $i < count($params['data']); $i++) {
                if ($params['data'][$i]['episode_upload_type'] == 'server_video') {
                    $this->common->videoNameToUrl(array($params['data'][$i]), 'episode_audio', $this->folder);
                }
            }

            return view('user.podcasts.ep_index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function PodcastAdd($id)
    {
        try {
            $params['podcasts_id'] = $id;
            return view('user.podcasts.ep_add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function PodcastSave(Request $request)
    {
        try {
            User_Data();

            $validator = Validator::make($request->all(), [
                'podcasts_id' => 'required',
                'name' => 'required',
                'description' => 'required',
                'portrait_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'landscape_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'episode_upload_type' => 'required',
                'is_comment' => 'required',
                'is_like' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            if ($request->episode_upload_type == 'server_video') {
                $validator2 = Validator::make($request->all(), [
                    'music' => 'required',
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

            $files1 = $requestData['portrait_img'];
            $files2 = $requestData['landscape_img'];
            $requestData['portrait_img'] = $this->common->saveImage($files1, $this->folder);
            $requestData['landscape_img'] = $this->common->saveImage($files2, $this->folder);
            if ($requestData['episode_upload_type'] == 'server_video') {

                $requestData['episode_audio'] = $requestData['music'];
                $requestData['episode_size'] = $this->common->getFileSize($requestData['music'], $this->folder);
            } else {
                $requestData['episode_audio'] = $requestData['url'];
                $requestData['episode_size'] = 0;
            }
            unset($requestData['music'], $requestData['url']);

            $requestData['total_view'] = 0;
            $requestData['total_like'] = 0;
            $requestData['total_dislike'] = 0;
            $requestData['sortable'] = 1;

            $episode_data = Episode::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($episode_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_added')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function PodcastEdit($podcasts_id, $id)
    {
        try {

            $params['data'] = Episode::where('id', $id)->first();
            if ($params['data'] != null) {

                $params['podcasts_id'] = $podcasts_id;

                $this->common->imageNameToUrl(array($params['data']), 'portrait_img', $this->folder);
                $this->common->imageNameToUrl(array($params['data']), 'landscape_img', $this->folder);
                if ($params['data']['episode_upload_type'] == 'server_video') {
                    $this->common->videoNameToUrl(array($params['data']), 'content', $this->folder);
                }

                return view('user.podcasts.ep_edit', $params);
            } else {
                return redirect()->back()->with('error', __('Label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function PodcastUpdate(Request $request)
    {
        try {
            User_Data();

            $validator = Validator::make($request->all(), [
                'podcasts_id' => 'required',
                'name' => 'required',
                'description' => 'required',
                'portrait_img' => 'image|mimes:jpeg,png,jpg|max:2048',
                'landscape_img' => 'image|mimes:jpeg,png,jpg|max:2048',
                'episode_upload_type' => 'required',
                'is_comment' => 'required',
                'is_like' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            if ($request->episode_upload_type != 'server_video') {
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

            if ($requestData['episode_upload_type'] == 'server_video') {

                if ($requestData['episode_upload_type'] == $requestData['episode_upload_type']) {

                    if ($requestData['music']) {

                        $requestData['episode_audio'] = $requestData['music'];
                        $this->common->deleteImageToFolder($this->folder, basename($requestData['old_episode_audio']));
                        $requestData['episode_size'] = $this->common->getFileSize($requestData['music'], $this->folder);
                    }
                } else {

                    if ($requestData['music']) {

                        $requestData['episode_audio'] = $requestData['music'];
                        $this->common->deleteImageToFolder($this->folder, basename($requestData['old_episode_audio']));
                        $requestData['episode_size'] = $this->common->getFileSize($requestData['music'], $this->folder);
                    } else {
                        $requestData['episode_audio'] = '';
                    }
                }
            } else {
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_episode_audio']));
                $requestData['episode_size'] = 0;

                $requestData['episode_audio'] = "";
                if ($requestData['url']) {
                    $requestData['episode_audio'] = $requestData['url'];
                }
            }
            unset($requestData['music'], $requestData['url'], $requestData['old_episode_upload_type'], $requestData['old_episode_audio'], $requestData['old_portrait_img'], $requestData['old_landscape_img']);

            $episode_data = Episode::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($episode_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_edit_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_updated')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function PodcastDelete($podcasts_id, $id)
    {
        try {
            User_Data();

            $data = Episode::where('id', $id)->first();
            if (isset($data)) {

                $this->common->deleteImageToFolder($this->folder, $data['portrait_img']);
                $this->common->deleteImageToFolder($this->folder, $data['landscape_img']);
                $this->common->deleteImageToFolder($this->folder, $data['episode_audio']);
                $data->delete();

                // Content Releted Data Delete
                Comment::where('content_id', $podcasts_id)->where('episode_id', $id)->delete();
                Content_Report::where('content_id', $podcasts_id)->where('episode_id', $id)->delete();
                History::where('content_id', $podcasts_id)->where('episode_id', $id)->delete();
                Like::where('content_id', $podcasts_id)->where('episode_id', $id)->delete();
                View::where('content_id', $podcasts_id)->where('episode_id', $id)->delete();
                Watch_later::where('content_id', $podcasts_id)->where('episode_id', $id)->delete();
            }

            return redirect()->route('upodcast.episode.index', $podcasts_id)->with('success', __('Label.data_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function PodcastSortable(Request $request)
    {
        try {

            $ids = $request['ids'];

            if (isset($ids) && $ids != null && $ids != "") {

                $id_array = explode(',', $ids);
                for ($i = 0; $i < count($id_array); $i++) {
                    Episode::where('id', $id_array[$i])->update(['sortable' => $i + 1]);
                }
            }
            return response()->json(array('status' => 200, 'success' => __('Label.data_edit_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
