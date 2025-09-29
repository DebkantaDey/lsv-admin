<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Common;
use App\Models\Content;
use App\Models\Episode;
use App\Models\Interests_Category;
use App\Models\Language;
use App\Models\Playlist_Content;
use App\Models\Section;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

// is_home_page = 1- Yes, 2- No
// content_type = 1- Music, 2- Podcasts, 3- Radio, 4- Playlist, 5- Category, 6- Language

class MusicController extends Controller
{
    private $folder_category = "category";
    private $folder_language = "language";
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

    public function get_music_category(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'numeric',
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

                    $id = Category::where('id', $value['category_id'])->where('type', 2)->first();
                    if (isset($id) && $id != null) {

                        $this->common->imageNameToUrl(array($id), 'image', $this->folder_category);
                        $in_data[] = $id;
                    }
                    $cat_ids[] = $value['category_id'];
                }

                $not_in_data = Category::whereNotIn('id', $cat_ids)->where('type', 2)->latest()->get();
                $this->common->imageNameToUrl($not_in_data, 'image', $this->folder_category);
                $not_in_data = $not_in_data->toArray();

                $fin_array = array_merge($in_data, $not_in_data);
                $currentItems = array_slice($fin_array, $this->page_limit * ($page_no - 1), $this->page_limit);

                $paginator = new LengthAwarePaginator($currentItems, count($fin_array), $this->page_limit, $page_no);
                $more_page = $this->common->more_page($page_no, $paginator->lastPage());

                $response['pagination'] = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
                $response['data'] = $paginator->items();
            } else {

                $array = Category::where('type', 2)->orderBy('id', 'DESC');
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
    public function get_music_section(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'is_home_screen' => 'required|numeric',
                    'user_id' => 'numeric',
                    'content_type' => 'numeric',
                    'page_no' => 'numeric',
                ],
                [
                    'is_home_screen.required' => __('api_msg.is_home_screen_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = isset($request->user_id) ? $request->user_id : 0;
            $is_home_screen = $request['is_home_screen'];
            $content_type = isset($request->content_type) ? $request->content_type : 0;
            $page_no = $request->page_no ?? 1;
            $page_size = 0;
            $more_page = false;

            if ($is_home_screen == 1) {
                $data = Section::where('is_home_screen', $is_home_screen)->where('status', 1)->orderBy('sortable', 'asc')->latest();
            } else if ($is_home_screen == 2) {
                $data = Section::where('is_home_screen', $is_home_screen)->where('content_type', $content_type)->where('status', 1)->orderBy('sortable', 'asc')->latest();
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $offset = $page_no * $total_page - $total_page;

            $more_page = $this->common->more_page($page_no, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $page_no, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                for ($i = 0; $i < count($data); $i++) {

                    $data[$i]['data'] = [];
                    if ($data[$i]['content_type'] == 1) {

                        $query = $this->common->music_section_query($user_id, 2, $data[$i]['category_id'], $data[$i]['language_id'], $data[$i]['artist_id'], $data[$i]['order_by_view'], $data[$i]['order_by_like'], $data[$i]['order_by_upload'], $data[$i]['no_of_content']);
                        $data[$i]['data'] = $query;
                    } else if ($data[$i]['content_type'] == 2) {

                        $query = $this->common->music_section_query($user_id, 4, $data[$i]['category_id'], $data[$i]['language_id'], 0, $data[$i]['order_by_view'], $data[$i]['order_by_like'], $data[$i]['order_by_upload'], $data[$i]['no_of_content']);

                        // Episode array
                        for ($j = 0; $j < count($query); $j++) {

                            $episode_array = [];
                            $episode = Episode::select('id', 'name', 'portrait_img', 'description')->where('podcasts_id', $query[$j]['id'])->orderBy('sortable', 'asc')->latest()->take(3)->get();
                            if (count($episode) > 0) {

                                $this->common->imageNameToUrl($episode, 'portrait_img', $this->folder_content);
                                $episode_array = $episode;
                            }
                            $query[$j]['episode_array'] = $episode_array;
                        }

                        $data[$i]['data'] = $query;
                    } else if ($data[$i]['content_type'] == 3) {

                        $query = $this->common->music_section_query($user_id, 6, $data[$i]['category_id'], $data[$i]['language_id'], $data[$i]['artist_id'], $data[$i]['order_by_view'], $data[$i]['order_by_like'], $data[$i]['order_by_upload'], $data[$i]['no_of_content']);
                        $data[$i]['data'] = $query;
                    } else if ($data[$i]['content_type'] == 4) {

                        $query = $this->common->music_section_query($user_id, 5, 0, 0, 0, $data[$i]['order_by_view'], $data[$i]['order_by_like'], $data[$i]['order_by_upload'], $data[$i]['no_of_content']);

                        // Playlist Image array
                        for ($j = 0; $j < count($query); $j++) {
                            $image_array = [];
                            $playlist_content = Playlist_Content::where('playlist_id', $query[$j]['id'])->where('content_type', 2)->orderBy('sortable', 'asc')->with('Content')->latest()->get();
                            if (count($playlist_content) > 0) {
                                $img_count = 0;
                                for ($k = 0; $k < count($playlist_content); $k++) {

                                    if ($playlist_content[$k]['Content'] != null & isset($playlist_content[$k]['Content'])) {

                                        $this->common->imageNameToUrl(array($playlist_content[$k]['Content']), 'portrait_img', $this->folder_content);
                                        $image_array[] = $playlist_content[$k]['Content']['portrait_img'];

                                        $img_count = $img_count + 1;
                                        if ($img_count == 4) {
                                            break;
                                        }
                                    }
                                }
                            }
                            $query[$j]['playlist_image'] = $image_array;
                        }

                        $data[$i]['data'] = $query;
                    } else if ($data[$i]['content_type'] == 5) {

                        $query = Category::where('type', 2)->orderBy('id', 'desc')->get();
                        $this->common->imageNameToUrl($query, 'image', $this->folder_category);

                        for ($j = 0; $j < count($query); $j++) {
                            $query[$j]['title'] = $query[$j]['name'];
                            $query[$j]['portrait_img'] = $query[$j]['image'];
                            $query[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);
                        }

                        $data[$i]['data'] = $query;
                    } else if ($data[$i]['content_type'] == 6) {

                        $query = Language::orderBy('id', 'desc')->get();
                        $this->common->imageNameToUrl($query, 'image', $this->folder_language);

                        for ($j = 0; $j < count($query); $j++) {
                            $query[$j]['title'] = $query[$j]['name'];
                            $query[$j]['portrait_img'] = $query[$j]['image'];
                            $query[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);
                        }
                        $data[$i]['data'] = $query;
                    }
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_music_section_detail(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'numeric',
                    'section_id' => 'required|numeric',
                    'page_no' => 'numeric',
                ],
                [
                    'section_id.required' => __('api_msg.section_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = isset($request->user_id) ? $request->user_id : 0;
            $section_id = $request['section_id'];
            $page_no = $request->page_no ?? 1;
            $page_size = 0;
            $more_page = false;

            $section = Section::where('id', $section_id)->first();
            if ($section != null && isset($section)) {

                if ($section['content_type'] == 1) {

                    $data = $this->common->music_section_details_query(2, $section['category_id'], $section['language_id'], $section['artist_id'], $section['order_by_view'], $section['order_by_like'], $section['order_by_upload']);
                } else if ($section['content_type'] == 2) {

                    $data = $this->common->music_section_details_query(4, $section['category_id'], $section['language_id'], 0, $section['order_by_view'], $section['order_by_like'], $section['order_by_upload']);
                } else if ($section['content_type'] == 3) {

                    $data = $this->common->music_section_details_query(6, $section['category_id'], $section['language_id'], $section['artist_id'], $section['order_by_view'], $section['order_by_like'], $section['order_by_upload']);
                } else if ($section['content_type'] == 4) {

                    $data = $this->common->music_section_details_query(5, 0, 0, 0, $section['order_by_view'], $section['order_by_like'], $section['order_by_upload']);
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $offset = $page_no * $total_page - $total_page;

            $more_page = $this->common->more_page($page_no, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $page_no, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get();

            if (count($data) > 0) {

                for ($j = 0; $j < count($data); $j++) {

                    $data[$j]['portrait_img'] = $this->common->getImage($this->folder_content, $data[$j]['portrait_img']);
                    $data[$j]['landscape_img'] = $this->common->getImage($this->folder_content, $data[$j]['landscape_img']);
                    if ($data[$j]['content_upload_type'] == 'server_video') {
                        $data[$j]['content'] = $this->common->getVideo($this->folder_content, $data[$j]['content']);
                    }

                    $data[$j]['user_id'] = $this->common->getUserId($data[$j]['channel_id']);
                    $data[$j]['channel_name'] = $this->common->getChannelName($data[$j]['channel_id']);
                    $data[$j]['channel_image'] = $this->common->getChannelImage($data[$j]['channel_id']);
                    $data[$j]['category_name'] = $this->common->getCategoryName($data[$j]['category_id']);
                    $data[$j]['artist_name'] = $this->common->getArtistName($data[$j]['artist_id']);
                    $data[$j]['language_name'] = $this->common->getLanguageName($data[$j]['language_id']);
                    $data[$j]['is_subscribe'] = $this->common->is_subscribe(1, $user_id, $data[$j]['user_id']); // Type 1- Channel, 2- Artist
                    $data[$j]['total_comment'] = $this->common->getTotalComment($data[$j]['id']);
                    $data[$j]['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$j]['content_type'], $data[$j]['id'], 0);
                    $data[$j]['total_subscriber'] = $this->common->total_subscriber($data[$j]['user_id']);
                    $data[$j]['total_episode'] = $this->common->getTotalEpisode($data[$j]['id']);
                    $data[$j]['is_rent_buy'] = $this->common->getRentBuy($user_id, $data[$j]['id']);
                    $data[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $data[$j]['stop_time'] = $this->common->getContentStopTime($user_id, $data[$j]['content_type'], $data[$j]['id'], 0);

                    // Playlist Image array
                    $image_array = [];
                    if ($data[$j]['content_type'] == 5) {

                        $playlist_content = Playlist_Content::where('playlist_id', $data[$j]['id'])->where('content_type', 2)->orderBy('sortable', 'asc')->with('Content')->latest()->get();
                        if (count($playlist_content) > 0) {

                            $img_count = 0;
                            for ($i = 0; $i < count($playlist_content); $i++) {

                                if ($playlist_content[$i]['Content'] != null & isset($playlist_content[$i]['Content'])) {

                                    $this->common->imageNameToUrl(array($playlist_content[$i]['Content']), 'portrait_img', $this->folder_content);
                                    $image_array[] = $playlist_content[$i]['Content']['portrait_img'];

                                    $img_count = $img_count + 1;
                                    if ($img_count == 4) {
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    $data[$j]['playlist_image'] = $image_array;
                }

                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_music_by_category(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'numeric',
                    'category_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'category_id.required' => __('api_msg.category_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;
            $category_id = $request['category_id'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Content::where('status', 1)->where('content_type', 2)->where('category_id', $category_id)->where('is_rent', 0);

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

                for ($j = 0; $j < count($data); $j++) {

                    $data[$j]['portrait_img'] = $this->common->getImage($this->folder_content, $data[$j]['portrait_img']);
                    $data[$j]['landscape_img'] = $this->common->getImage($this->folder_content, $data[$j]['landscape_img']);
                    if ($data[$j]['content_upload_type'] == 'server_video') {
                        $data[$j]['content'] = $this->common->getVideo($this->folder_content, $data[$j]['content']);
                    }

                    $data[$j]['user_id'] = $this->common->getUserId($data[$j]['channel_id']);
                    $data[$j]['channel_name'] = $this->common->getChannelName($data[$j]['channel_id']);
                    $data[$j]['channel_image'] = $this->common->getChannelImage($data[$j]['channel_id']);
                    $data[$j]['category_name'] = $this->common->getCategoryName($data[$j]['category_id']);
                    $data[$j]['artist_name'] = $this->common->getArtistName($data[$j]['artist_id']);
                    $data[$j]['language_name'] = $this->common->getLanguageName($data[$j]['language_id']);
                    $data[$j]['is_subscribe'] = $this->common->is_subscribe(1, $user_id, $data[$j]['user_id']); // Type 1- Channel, 2- Artist
                    $data[$j]['total_comment'] = $this->common->getTotalComment($data[$j]['id']);
                    $data[$j]['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$j]['content_type'], $data[$j]['id'], 0);
                    $data[$j]['total_subscriber'] = $this->common->total_subscriber($data[$j]['user_id']);
                    $data[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $data[$j]['stop_time'] = $this->common->getContentStopTime($user_id, $data[$j]['content_type'], $data[$j]['id'], 0);
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_music_by_language(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'numeric',
                    'language_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'language_id.required' => __('api_msg.language_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;
            $language_id = $request['language_id'];
            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Content::where('status', 1)->where('content_type', 2)->where('language_id', $language_id)->where('is_rent', 0);

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

                for ($j = 0; $j < count($data); $j++) {

                    $data[$j]['portrait_img'] = $this->common->getImage($this->folder_content, $data[$j]['portrait_img']);
                    $data[$j]['landscape_img'] = $this->common->getImage($this->folder_content, $data[$j]['landscape_img']);
                    if ($data[$j]['content_upload_type'] == 'server_video') {
                        $data[$j]['content'] = $this->common->getVideo($this->folder_content, $data[$j]['content']);
                    }

                    $data[$j]['user_id'] = $this->common->getUserId($data[$j]['channel_id']);
                    $data[$j]['channel_name'] = $this->common->getChannelName($data[$j]['channel_id']);
                    $data[$j]['channel_image'] = $this->common->getChannelImage($data[$j]['channel_id']);
                    $data[$j]['category_name'] = $this->common->getCategoryName($data[$j]['category_id']);
                    $data[$j]['artist_name'] = $this->common->getArtistName($data[$j]['artist_id']);
                    $data[$j]['language_name'] = $this->common->getLanguageName($data[$j]['language_id']);
                    $data[$j]['is_subscribe'] = $this->common->is_subscribe(1, $user_id, $data[$j]['user_id']); // Type 1- Channel, 2- Artist
                    $data[$j]['total_comment'] = $this->common->getTotalComment($data[$j]['id']);
                    $data[$j]['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$j]['content_type'], $data[$j]['id'], 0);
                    $data[$j]['total_subscriber'] = $this->common->total_subscriber($data[$j]['user_id']);
                    $data[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $data[$j]['stop_time'] = $this->common->getContentStopTime($user_id, $data[$j]['content_type'], $data[$j]['id'], 0);
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_releted_music(Request $request)
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

                $data = Content::where('id', '!=', $content['id'])->where('content_type', 2)->where('is_rent', 0)->where('category_id', $content['category_id'])->where('status', 1)->orderby('total_view', 'desc')->orderBy('total_like', 'desc');

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
