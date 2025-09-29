<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Common;
use App\Models\Content;
use App\Models\Interests_Category;
use App\Models\Like;
use App\Models\View;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class VideoController extends Controller
{
    private $folder_category = "category";
    private $folder_content = "content";
    public $common;
    public $page_limit;
    public function __construct()
    {
        try {
            $this->common = new Common();
            $this->page_limit = env('PAGE_LIMIT');
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function get_video_category(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'numeric',
                    'page_no' => 'numeric',
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = isset($request->user_id) ? $request->user_id : 0;
            $page_no = $request->page_no ?? 1;

            if ($user_id != 0) {

                $cat_ids = [];
                $in_data = [];
                $interests = Interests_Category::select('category_id')->where('user_id', $user_id)->orderBy('count', 'desc')->latest()->get();
                foreach ($interests as $key => $value) {

                    $id = Category::where('id', $value['category_id'])->where('type', 1)->first();
                    if (isset($id) && $id != null) {

                        $this->common->imageNameToUrl(array($id), 'image', $this->folder_category);
                        $in_data[] = $id;
                    }
                    $cat_ids[] = $value['category_id'];
                }

                $not_in_data = Category::whereNotIn('id', $cat_ids)->where('type', 1)->latest()->get();
                $this->common->imageNameToUrl($not_in_data, 'image', $this->folder_category);
                $not_in_data = $not_in_data->toArray();

                $fin_array = array_merge($in_data, $not_in_data);
                $currentItems = array_slice($fin_array, $this->page_limit * ($page_no - 1), $this->page_limit);

                $paginator = new LengthAwarePaginator($currentItems, count($fin_array), $this->page_limit, $page_no);
                $more_page = $this->common->more_page($page_no, $paginator->lastPage());

                $response['pagination'] = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
                $response['data'] = $paginator->items();
            } else {

                $array = Category::where('type', 1)->orderBy('id', 'DESC');
                $response = $this->common->Pagination($array, $page_no);

                $this->common->imageNameToUrl($response['data'], 'image', $this->folder_category);
            }

            if (count($response['data']) > 0) {
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $response['data'], $response['pagination']);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_video_list(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'is_home_page' => 'required|numeric',
                    'user_id' => 'numeric',
                    'category_id' => 'numeric',
                    'page_no' => 'numeric',
                ],
                [
                    'is_home_page.required' => __('api_msg.is_home_page_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = isset($request->user_id) ? $request->user_id : 0;
            $is_home_page = $request['is_home_page'];
            $category_id = isset($request->category_id) ? $request->category_id : 0;
            $page_no = $request->page_no ?? 1;

            $block_channel_list = $this->common->get_block_channel($user_id);
            $get_subscriber = $this->common->get_subscriber($user_id);
            $get_interests_category = $this->common->get_interests_category($user_id);
            $now = date("Y-m-d H:i:s");
            $last_3days = date("Y-m-d H:i:s", strtotime('-3 day', strtotime($now)));

            if ($is_home_page == 1) {

                // Get Content
                $content = Content::where('content_type', 1)->where('status', 1)->where('is_rent', 0)
                    ->whereNotIn('channel_id', $block_channel_list)->latest()->get();
                $content_ids = array();
                foreach ($content as $key => $value) {
                    $content_ids[] = $value['id'];
                }

                // Get Recent Content
                $recent_data = Content::whereIn('id', $content_ids)->whereIn('channel_id', $get_subscriber)->where('is_rent', 0)
                    ->where('created_at', ">", $last_3days)->orderBy('total_view', 'desc')->latest()->get()->toArray();
                $recent_data_ids = array();
                foreach ($recent_data as $key => $value) {
                    $recent_data_ids[] = $value['id'];
                }

                // Get Interests Content
                $interests_data = Content::whereIn('id', $content_ids)->whereIn('category_id', $get_interests_category)->whereNotIn('id', $recent_data_ids)->where('is_rent', 0)
                    ->orderBy('total_view', 'desc')->latest()->get()->toArray();
                $interests_data_ids = array();
                foreach ($interests_data as $key => $value) {
                    $interests_data_ids[] = $value['id'];
                }

                // Get Relatio Content (like, view)
                $like_data = Like::where('user_id', $user_id)->where('content_type', 1)->where('status', 1)
                    ->whereNotIn('content_id', $recent_data_ids)->whereNotIn('content_id', $interests_data_ids)->latest()->get();
                $like_data_ids = array();
                foreach ($like_data as $key => $value) {
                    $like_data_ids[] = $value['content_id'];
                }

                $view_data = View::where('user_id', $user_id)->where('content_type', 1)->where('status', 1)
                    ->whereNotIn('content_id', $recent_data_ids)->whereNotIn('content_id', $interests_data_ids)
                    ->whereNotIn('content_id', $like_data_ids)->latest()->get();
                $view_data_ids = array();
                foreach ($view_data as $key => $value) {
                    $view_data_ids[] = $value['content_id'];
                }

                $relation_data = Content::whereIn('id', $like_data_ids)->whereIn('id', $view_data_ids)->where('is_rent', 0)
                    ->orderBy('total_view', 'desc')->where('status', 1)->latest()->get()->toArray();
                $relation_data_ids = array();
                foreach ($relation_data as $key => $value) {
                    $relation_data_ids[] = $value['id'];
                }

                // Get Other Content
                $other_data = Content::whereIn('id', $content_ids)->whereNotIn('id', $recent_data_ids)->whereNotIn('id', $interests_data_ids)->where('is_rent', 0)
                    ->whereNotIn('id', $relation_data_ids)->orderBy('total_view', 'desc')->where('status', 1)->latest()->get()->toArray();

                // Marge All Array 
                $final_array = array_merge($recent_data, $interests_data, $relation_data, $other_data);
                $currentItems = array_slice($final_array, $this->page_limit * ($page_no - 1), $this->page_limit);

                $paginator = new LengthAwarePaginator($currentItems, count($final_array), $this->page_limit, $page_no);
                $more_page = $this->common->more_page($page_no, $paginator->lastPage());

                $pagination = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
                $data = $paginator->items();
            } else if ($is_home_page == 0 && $category_id != 0) {

                // Get Content
                $content = Content::where('content_type', 1)->where('status', 1)->where('category_id', $category_id)->where('is_rent', 0)
                    ->whereNotIn('channel_id', $block_channel_list)->latest()->get();
                $content_ids = array();
                foreach ($content as $key => $value) {
                    $content_ids[] = $value['id'];
                }

                // Get Recent Content
                $recent_data = Content::whereIn('id', $content_ids)->whereIn('channel_id', $get_subscriber)->where('is_rent', 0)
                    ->where('created_at', ">", $last_3days)->orderBy('total_view', 'desc')->latest()->get()->toArray();
                $recent_data_ids = array();
                foreach ($recent_data as $key => $value) {
                    $recent_data_ids[] = $value['id'];
                }

                // Get Interests Content
                $interests_data = Content::whereIn('id', $content_ids)->whereIn('category_id', $get_interests_category)->whereNotIn('id', $recent_data_ids)->where('is_rent', 0)
                    ->orderBy('total_view', 'desc')->latest()->get()->toArray();
                $interests_data_ids = array();
                foreach ($interests_data as $key => $value) {
                    $interests_data_ids[] = $value['id'];
                }

                // Get Relatio Content (like, view)
                $like_data = Like::where('user_id', $user_id)->where('content_type', 1)->where('status', 1)
                    ->whereNotIn('content_id', $recent_data_ids)->whereNotIn('content_id', $interests_data_ids)->latest()->get();
                $like_data_ids = array();
                foreach ($like_data as $key => $value) {
                    $like_data_ids[] = $value['content_id'];
                }

                $view_data = View::where('user_id', $user_id)->where('content_type', 1)->where('status', 1)
                    ->whereNotIn('content_id', $recent_data_ids)->whereNotIn('content_id', $interests_data_ids)
                    ->whereNotIn('content_id', $like_data_ids)->latest()->get();
                $view_data_ids = array();
                foreach ($view_data as $key => $value) {
                    $view_data_ids[] = $value['content_id'];
                }

                $relation_data = Content::whereIn('id', $like_data_ids)->whereIn('id', $view_data_ids)->where('is_rent', 0)
                    ->orderBy('total_view', 'desc')->where('status', 1)->latest()->get()->toArray();
                $relation_data_ids = array();
                foreach ($relation_data as $key => $value) {
                    $relation_data_ids[] = $value['id'];
                }

                // Get Other Content
                $other_data = Content::whereIn('id', $content_ids)->whereNotIn('id', $recent_data_ids)->whereNotIn('id', $interests_data_ids)->where('is_rent', 0)
                    ->whereNotIn('id', $relation_data_ids)->orderBy('total_view', 'desc')->where('status', 1)->latest()->get()->toArray();

                // Marge All Array 
                $final_array = array_merge($recent_data, $interests_data, $relation_data, $other_data);
                $currentItems = array_slice($final_array, $this->page_limit * ($page_no - 1), $this->page_limit);

                $paginator = new LengthAwarePaginator($currentItems, count($final_array), $this->page_limit, $page_no);
                $more_page = $this->common->more_page($page_no, $paginator->lastPage());

                $pagination = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
                $data = $paginator->items();
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            if (count($data) > 0) {

                $return = array();
                foreach ($data as $key) {

                    $key['portrait_img'] = $this->common->getImage($this->folder_content, $key['portrait_img']);
                    $key['landscape_img'] = $this->common->getImage($this->folder_content, $key['landscape_img']);
                    if ($key['content_upload_type'] == 'server_video') {
                        $key['content'] = $this->common->getVideo($this->folder_content, $key['content']);
                    }

                    $key['user_id'] = $this->common->getUserId($key['channel_id']);
                    $key['channel_name'] = $this->common->getChannelName($key['channel_id']);
                    $key['channel_image'] = $this->common->getChannelImage($key['channel_id']);
                    $key['category_name'] = $this->common->getCategoryName($key['category_id']);
                    $key['artist_name'] = $this->common->getArtistName($key['artist_id']);
                    $key['language_name'] = $this->common->getLanguageName($key['language_id']);
                    $key['is_subscribe'] = $this->common->is_subscribe(1, $user_id, $key['user_id']); // Type 1- Channel, 2- Artist
                    $key['total_comment'] = $this->common->getTotalComment($key['id']);
                    $key['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $key['content_type'], $key['id'], 0);
                    $key['total_subscriber'] = $this->common->total_subscriber($key['user_id']);
                    $key['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $key['stop_time'] = $this->common->getContentStopTime($user_id, $key['content_type'], $key['id'], 0);

                    $return[] = $key;
                }

                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $return, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_releted_video(Request $request)
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
            $user_id = isset($request->user_id) ? $request->user_id : 0;

            $content = Content::where('id', $content_id)->first();
            if (isset($content) && $content != null) {

                $page_size = 0;
                $current_page = 0;
                $more_page = false;

                $data = Content::where('id', '!=', $content['id'])->where('content_type', 1)->where('is_rent', 0)->where('category_id', $content['category_id'])->where('status', 1)->orderby('total_view', 'desc')->orderBy('total_like', 'desc');

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
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
