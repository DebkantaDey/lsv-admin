<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Content;
use App\Models\Playlist_Content;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

// Playlist Type : 1- Public, 2- Private
class PlaylistController extends Controller
{
    public $common;
    public $page_limit;
    private $folder_content = "content";
    public function __construct()
    {
        try {
            $this->common = new Common();
            $this->page_limit = env('PAGE_LIMIT');
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function create_playlist(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'channel_id' => 'required',
                    'title' => 'required',
                    'playlist_type' => 'required|numeric',
                ],
                [
                    'channel_id.required' => __('api_msg.channel_id_is_required'),
                    'title.required' => __('api_msg.title_is_required'),
                    'playlist_type.required' => __('api_msg.playlist_type_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $channel_id = $request['channel_id'];
            $title = $request['title'];
            $playlist_type = $request['playlist_type'];
            $description = isset($request->description) ? $request->description : "";

            $insert = new Content();
            $insert['content_type'] = 5;
            $insert['channel_id'] = $channel_id;
            $insert['category_id'] = 0;
            $insert['language_id'] = 0;
            $insert['artist_id'] = 0;
            $insert['hashtag_id'] = "";
            $insert['title'] = $title;
            $insert['description'] = $description;
            $insert['portrait_img'] = "";
            $insert['landscape_img'] = "";
            $insert['content_upload_type'] = "";
            $insert['content'] = "";
            $insert['content_size'] = "";
            $insert['is_rent'] = 0;
            $insert['rent_price'] = 0;
            $insert['is_comment'] = 0;
            $insert['is_download'] = 0;
            $insert['is_like'] = 0;
            $insert['total_view'] = 0;
            $insert['total_like'] = 0;
            $insert['total_dislike'] = 0;
            $insert['playlist_type'] = $playlist_type;
            $insert['is_admin_added'] = 1;
            $insert['status'] = 1;

            if ($insert->save()) {
                return $this->common->API_Response(200, __('api_msg.playlist_create_successfully'), []);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function edit_playlist(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'content_id' => 'required|numeric',
                    'title' => 'required',
                    'playlist_type' => 'required|numeric',
                ],
                [
                    'content_id.required' => __('api_msg.content_id_is_required'),
                    'title.required' => __('api_msg.title_is_required'),
                    'playlist_type.required' => __('api_msg.playlist_type_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $content_id = $request['content_id'];
            $title = $request['title'];
            $playlist_type = $request['playlist_type'];
            $description = isset($request->description) ? $request->description : "";

            $update = Content::where('id', $content_id)->first();
            if (isset($update) && $update != null) {

                $update['title'] = $title;
                $update['playlist_type'] = $playlist_type;
                $update['description'] = $description;
                $update->save();
                return $this->common->API_Response(200, __('api_msg.playlist_edit_successfully'), []);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function delete_playlist(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'content_id' => 'required|numeric',
                ],
                [
                    'content_id.required' => __('api_msg.content_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $content_id = $request['content_id'];
            Content::where('id', $content_id)->delete();
            Playlist_Content::where('playlist_id', $content_id)->delete();
            return $this->common->API_Response(200, __('api_msg.playlist_delete_successfully'), []);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_remove_content_to_playlist(Request $request) // Type = 0-Remove, 1-Add
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'channel_id' => 'required',
                    'playlist_id' => 'required|numeric',
                    'content_type' => 'required|numeric',
                    'content_id' => 'required|numeric',
                    'episode_id' => 'numeric',
                    'type' => 'required|numeric',
                ],
                [
                    'channel_id.required' => __('api_msg.channel_id_is_required'),
                    'playlist_id.required' => __('api_msg.playlist_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                    'content_id.required' => __('api_msg.content_id_is_required'),
                    'type.required' => __('api_msg.type_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $channel_id = $request['channel_id'];
            $playlist_id = $request['playlist_id'];
            $content_type = $request['content_type'];
            $content_id = $request['content_id'];
            $episode_id = isset($request['episode_id']) ? $request['episode_id'] : 0;
            $type = $request['type'];

            if ($type == 1) {

                $content = Playlist_Content::where('channel_id', $channel_id)->where('playlist_id', $playlist_id)->where('content_type', $content_type)->where('content_id', $content_id)->where('episode_id', $episode_id)->first();
                if (!isset($content) && $content == null) {

                    $insert = new Playlist_Content();
                    $insert['channel_id'] = $channel_id;
                    $insert['playlist_id'] = $playlist_id;
                    $insert['content_type'] = $content_type;
                    $insert['content_id'] = $content_id;
                    $insert['episode_id'] = $episode_id;
                    $insert['sortable'] = 1;
                    $insert->save();
                }
                return $this->common->API_Response(200, __('api_msg.content_add_successfully'), []);
            } else if ($type == 0) {

                $content = Playlist_Content::where('channel_id', $channel_id)->where('playlist_id', $playlist_id)->where('content_type', $content_type)->where('content_id', $content_id)->where('episode_id', $episode_id)->first();
                if (isset($content) && $content != null) {

                    $content->delete();
                }
                return $this->common->API_Response(200, __('api_msg.content_delete_successfully'), []);
            } else {
                return $this->common->API_Response(200, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_playlist_content(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'numeric',
                    'playlist_id' => 'required|numeric',
                    'content_type' => 'numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'playlist_id.required' => __('api_msg.playlist_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $playlist_id = $request['playlist_id'];
            $user_id = isset($request->user_id) ? $request->user_id : 0;
            $content_type = isset($request->content_type) ? $request->content_type : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            if ($content_type == 0) {
                $playlist_content = Playlist_Content::where('playlist_id', $playlist_id)->orderBy('sortable', 'asc')->latest()->get();
            } else {
                $playlist_content = Playlist_Content::where('playlist_id', $playlist_id)->where('content_type', $content_type)->orderBy('sortable', 'asc')->latest()->get();
            }

            $content_id = [];
            for ($i = 0; $i < count($playlist_content); $i++) {
                $content_id[] = $playlist_content[$i]['content_id'];
            }

            if (count($content_id) > 0) {

                $ids_ordered = implode(',', $content_id);
                $data = Content::whereIn('id', $content_id)->orderByRaw("FIELD(id, $ids_ordered)");

                if (isset($data) && $data != null) {

                    $total_rows = $data->count();
                    $total_page = $this->page_limit;
                    $page_size = ceil($total_rows / $total_page);
                    $current_page = $request->page_no ?? 1;
                    $offset = $current_page * $total_page - $total_page;

                    $more_page = $this->common->more_page($current_page, $page_size);
                    $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

                    $data->take($total_page)->offset($offset);
                    $data = $data->latest()->get();

                    if (count($data) > 0) {

                        for ($i = 0; $i < count($data); $i++) {

                            $data[$i]['portrait_img'] = $this->common->getImage($this->folder_content, $data[$i]['portrait_img']);
                            $data[$i]['landscape_img'] = $this->common->getImage($this->folder_content, $data[$i]['landscape_img']);
                            if ($data[$i]['content_upload_type'] == 'server_video') {
                                $data[$i]['content'] = $this->common->getVideo($this->folder_content, $data[$i]['content']);
                            }

                            $data[$i]['user_id'] = $this->common->getUserId($data[$i]['channel_id']);
                            $data[$i]['channel_name'] = $this->common->getChannelName($data[$i]['channel_id']);
                            $data[$i]['channel_image'] = $this->common->getChannelImage($data[$i]['channel_id']);
                            $data[$i]['category_name'] = $this->common->getCategoryName($data[$i]['category_id']);
                            $data[$i]['artist_name'] = $this->common->getArtistName($data[$i]['artist_id']);
                            $data[$i]['language_name'] = $this->common->getLanguageName($data[$i]['language_id']);
                            $data[$i]['is_subscribe'] = $this->common->is_subscribe(1, $user_id, $data[$i]['user_id']); // Type 1- Channel, 2- Artist
                            $data[$i]['total_comment'] = $this->common->getTotalComment($data[$i]['id']);
                            $data[$i]['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$i]['content_type'], $data[$i]['id'], 0);
                            $data[$i]['total_subscriber'] = $this->common->total_subscriber($data[$i]['user_id']);
                            $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                            $data[$i]['stop_time'] = $this->common->getContentStopTime($user_id, $data[$i]['content_type'], $data[$i]['id'], 0);
                        }
                        return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
                    } else {
                        return $this->common->API_Response(400, __('api_msg.data_not_found'));
                    }
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_multipal_content_to_playlist(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'playlist_id' => 'required|numeric',
                    'content_type' => 'required|numeric',
                    'content_id' => 'required',
                    'channel_id' => 'required',
                ],
                [
                    'playlist_id.required' => __('api_msg.playlist_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                    'content_id.required' => __('api_msg.content_id_is_required'),
                    'channel_id.required' => __('api_msg.channel_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $channel_id = $request['channel_id'];
            $playlist_id = $request['playlist_id'];
            $content_type = $request['content_type'];
            $content_id = $request['content_id'];
            $episode_id = isset($request['episode_id']) ? $request['episode_id'] : 0;

            $playlist = Content::where('content_type', 5)->where('id', $playlist_id)->first();
            if (isset($playlist) && $playlist != null) {

                $content_ids = explode(",", $content_id);
                for ($i = 0; $i < count($content_ids); $i++) {

                    if ($content_ids[$i] != "" && $content_ids[$i] != null && isset($content_ids[$i])) {

                        $check_ids = Playlist_Content::where('content_id', $content_ids[$i])->where('content_type', $content_type)->where('playlist_id', $playlist_id)
                            ->where('channel_id', $channel_id)->where('episode_id', 0)->where('status', 1)->first();

                        if ($check_ids == null && !isset($check_ids)) {

                            $insert = new Playlist_Content();
                            $insert['channel_id'] = $channel_id;
                            $insert['playlist_id'] = $playlist_id;
                            $insert['content_type'] = $content_type;
                            $insert['content_id'] = $content_ids[$i];
                            $insert['episode_id'] = 0;
                            $insert['sortable'] = 1;
                            $insert['status'] = 1;
                            $insert->save();
                        }
                    }
                }

                return $this->common->API_Response(200, __('api_msg.content_add_successfully'), []);
            } else {
                return $this->common->API_Response(400, "Playlist Not Found.");
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_content_to_playlist(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'content_type' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $content_type = $request['content_type'];
            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Content::where('status', 1)->where('content_type', $content_type)->where('is_rent', 0);

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get();

            if (count($data) > 0) {

                for ($i = 0; $i < count($data); $i++) {

                    $data[$i]['portrait_img'] = $this->common->getImage($this->folder_content, $data[$i]['portrait_img']);
                    $data[$i]['landscape_img'] = $this->common->getImage($this->folder_content, $data[$i]['landscape_img']);
                    if ($data[$i]['content_upload_type'] == 'server_video') {
                        $data[$i]['content'] = $this->common->getVideo($this->folder_content, $data[$i]['content']);
                    }

                    $data[$i]['user_id'] = $this->common->getUserId($data[$i]['channel_id']);
                    $data[$i]['channel_name'] = $this->common->getChannelName($data[$i]['channel_id']);
                    $data[$i]['channel_image'] = $this->common->getChannelImage($data[$i]['channel_id']);
                    $data[$i]['category_name'] = $this->common->getCategoryName($data[$i]['category_id']);
                    $data[$i]['artist_name'] = $this->common->getArtistName($data[$i]['artist_id']);
                    $data[$i]['language_name'] = $this->common->getLanguageName($data[$i]['language_id']);
                    $data[$i]['is_subscribe'] = $this->common->is_subscribe(1, $user_id, $data[$i]['user_id']); // Type 1- Channel, 2- Artist
                    $data[$i]['total_comment'] = $this->common->getTotalComment($data[$i]['id']);
                    $data[$i]['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$i]['content_type'], $data[$i]['id'], 0);
                    $data[$i]['total_subscriber'] = $this->common->total_subscriber($data[$i]['user_id']);
                    $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $data[$i]['stop_time'] = $this->common->getContentStopTime($user_id, $data[$i]['content_type'], $data[$i]['id'], 0);
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
