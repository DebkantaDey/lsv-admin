<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Content;
use App\Models\Like;
use App\Models\User;
use App\Models\View;
use CURLFile;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ReelsController extends Controller
{
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

    public function get_reels_list(Request $request)
    {
        try {

            $this->common->Delete_Reels();

            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'numeric',
                    'page_no' => 'numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;
            $page_no = $request->page_no ?? 1;

            if ($user_id  != 0) {

                $block_channel_list = $this->common->get_block_channel($user_id);
                $get_subscriber = $this->common->get_subscriber($user_id);
                $now = date("Y-m-d H:i:s");
                $last_24_hours = date("Y-m-d H:i:s", strtotime('-24 hours', strtotime($now)));

                // Get Content
                $content = Content::where('content_type', 3)->where('status', 1)->whereNotIn('channel_id', $block_channel_list)->latest()->get();
                $content_ids = array();
                foreach ($content as $key => $value) {
                    $content_ids[] = $value['id'];
                }

                // Get Recent Content
                $recent_data = Content::whereIn('id', $content_ids)->whereIn('channel_id', $get_subscriber)
                    ->where('created_at', ">", $last_24_hours)->orderBy('total_view', 'desc')->latest()->get()->toArray();
                $recent_data_ids = array();
                foreach ($recent_data as $key => $value) {
                    $recent_data_ids[] = $value['id'];
                }

                // Get Interests Content
                $interests_data = array();
                $user_interests = $this->common->get_user_interests($user_id);
                if (sizeof($user_interests) > 0) {

                    $interest_data = Content::whereIn('id', $content_ids)->whereNotIn('id', $recent_data_ids)->orderBy('total_view', 'desc')->latest()->get();
                    foreach ($user_interests as $key => $value) {

                        $hashtag_id = $value['hashtag_id'];

                        foreach ($interest_data as $key1 => $value1) {

                            $tag_ids = explode(',', $value1['hashtag_id']);
                            if (in_array($hashtag_id, $tag_ids)) {
                                $interests_data[] = $value1;
                            }
                        }
                    }

                    $interests_data = array_unique($interests_data);
                    $interests_data = array_values($interests_data);
                }
                $interests_data_ids = array();
                foreach ($interests_data as $key => $value) {
                    $interests_data_ids[] = $value['id'];
                }

                // Get Relatio Content (like, view)
                $like_data = Like::where('user_id', $user_id)->where('content_type', 3)->where('status', 1)
                    ->whereNotIn('content_id', $recent_data_ids)->whereNotIn('content_id', $interests_data_ids)->latest()->get();
                $like_data_ids = array();
                foreach ($like_data as $key => $value) {
                    $like_data_ids[] = $value['content_id'];
                }

                $view_data = View::where('user_id', $user_id)->where('content_type', 3)->where('status', 1)
                    ->whereNotIn('content_id', $recent_data_ids)->whereNotIn('content_id', $interests_data_ids)
                    ->whereNotIn('content_id', $like_data_ids)->latest()->get();
                $view_data_ids = array();
                foreach ($view_data as $key => $value) {
                    $view_data_ids[] = $value['content_id'];
                }

                $relation_data = Content::whereIn('id', $like_data_ids)->whereIn('id', $view_data_ids)
                    ->orderBy('total_view', 'desc')->where('status', 1)->latest()->get()->toArray();
                $relation_data_ids = array();
                foreach ($relation_data as $key => $value) {
                    $relation_data_ids[] = $value['id'];
                }

                // Get Other Content
                $other_data = Content::whereIn('id', $content_ids)->whereNotIn('id', $recent_data_ids)->whereNotIn('id', $interests_data_ids)
                    ->whereNotIn('id', $relation_data_ids)->orderBy('total_view', 'desc')->where('status', 1)->latest()->get()->toArray();

                // Marge All Array 
                $final_array = array_merge($recent_data, $interests_data, $relation_data, $other_data);
            } else {

                $final_array = Content::where('content_type', 3)->where('status', 1)->latest()->get()->toArray();
            }

            // Pagination
            $currentItems = array_slice($final_array, $this->page_limit * ($page_no - 1), $this->page_limit);
            $paginator = new LengthAwarePaginator($currentItems, count($final_array), $this->page_limit, $page_no);

            $more_page = $this->common->more_page($page_no, $paginator->lastPage());
            $pagination = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
            $data = $paginator->items();

            if (count($data) > 0) {

                $return = array();
                foreach ($data as $key) {

                    $key['portrait_img'] = $this->common->getImage($this->folder_content, $key['portrait_img']);
                    $key['landscape_img'] = $this->common->getImage($this->folder_content, $key['landscape_img']);
                    $key['content'] = $this->common->getVideo($this->folder_content, $key['content']);
                    $key['user_id'] = $this->common->getUserId($key['channel_id']);
                    $key['channel_name'] = $this->common->getChannelName($key['channel_id']);
                    $key['channel_image'] = $this->common->getChannelImage($key['channel_id']);
                    $key['total_comment'] = $this->common->getTotalComment($key['id']);
                    $key['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $key['content_type'], $key['id'], 0);
                    $key['is_subscribe'] = 0;
                    $to_user_data = User::where('channel_id', $key['channel_id'])->first();
                    if ($to_user_data != null && isset($to_user_data)) {
                        $key['is_subscribe'] = $this->common->is_subscribe(1, $user_id, $to_user_data['id']); // Type 1- Channel, 2- Artist
                    }
                    $key['is_buy'] = $this->common->is_any_package_buy($user_id);

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
    public function upload_reels(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'title' => 'required',
                'channel_id' => 'required',
                'portrait_img' => 'required|image|mimes:jpeg,png,jpg|max:10240',
                'video' => 'required|max:51200',
            ]);
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $requestData = $request->all();

            $insert = new Content();
            $insert['content_type'] = 3;
            $insert['channel_id'] = $requestData['channel_id'];
            $insert['category_id'] = 0;
            $insert['language_id'] = 0;
            $insert['artist_id'] = 0;
            $hashtag_id = $this->common->checkHashTag($requestData['title']);
            $hashtagId = 0;
            if (count($hashtag_id) > 0) {
                $hashtagId = implode(',', $hashtag_id);
            }
            $insert['hashtag_id'] = $hashtagId;
            $insert['title'] = $requestData['title'];
            $insert['description'] = '';
            $files1 = $requestData['portrait_img'];
            $insert['portrait_img'] = $this->common->saveImage($files1, $this->folder_content);
            $insert['landscape_img'] = '';
            $insert['content_upload_type'] = 'server_video';
            $files2 = $requestData['video'];

            $setting_data = Setting_Data();
            if ($setting_data['sight_engine_status'] == 1) { // sight engine video Redaction

                $user_key = $setting_data['sight_engine_user_key'];
                $secret_key = $setting_data['sight_engine_secret_key'];
                $concepts = $setting_data['sight_engine_concepts'];

                $video = $request->file('video');

                $params = array(
                    'media' => new CURLFile($video),
                    'concepts' => $concepts,
                    'api_user' => $user_key,
                    'api_secret' => $secret_key,
                );

                $ch = curl_init('https://api.sightengine.com/1.0/video/transform.json');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
                $response = curl_exec($ch);

                // Check for CURL errors
                if (curl_errno($ch)) {
                    $curl_error = curl_error($ch);
                    curl_close($ch);
                    return response()->json(['status' => 500, 'errors' => 'CURL Error: ' . $curl_error]);
                }

                curl_close($ch);

                $output = json_decode($response, true);

                if (isset($output['status']) && $output['status'] == "success") {
                    $media_id = $output['media']['id'];

                    $params1 = array(
                        'id' => $media_id,
                        'api_user' => $user_key,
                        'api_secret' => $secret_key,
                    );

                    $maxAttempts = 100; // Set the maximum number of attempts

                    for ($attempts = 0; $attempts < $maxAttempts; $attempts++) {
                        $ch = curl_init('https://api.sightengine.com/1.0/video/byid.json?' . http_build_query($params1));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($ch);

                        // Check for CURL errors 
                        if (curl_errno($ch)) {
                            $curl_error = curl_error($ch);
                            curl_close($ch);
                            return response()->json(['status' => 500, 'errors' => 'CURL Error: ' . $curl_error]);
                        }

                        curl_close($ch);
                        $output2 = json_decode($response, true);

                        if (isset($output2['output']['data']['status'])) {
                            $status = $output2['output']['data']['status'];

                            if ($status === 'finished') {

                                $videoUrl = $output2['output']['data']['transform']['location'];

                                // Get the video content
                                $video_get = Http::get($videoUrl);
                                if ($video_get->successful()) {

                                    $filename = 'vid_' . date('d_m_Y_') . rand(1111, 9999) . '.mp4';
                                    $path = $this->folder_content . '/' . $filename;
                                    Storage::disk('public')->put($path, $video_get->body());

                                    $insert['content'] = $filename;
                                } else {

                                    $error = 'Error on getting video from Sight Engine';
                                    return response()->json(['status' => 400, 'errors' => $error]);
                                }
                                break; // Break the loop if processing is successful

                            } elseif ($status === 'ongoing') {
                                sleep(5);
                                $attempts++;
                                if ($attempts >= $maxAttempts - 1) {
                                    // Reset the counter after reaching max attempts
                                    $attempts = 0;
                                }
                            }
                        } elseif ($output2['status'] == "failure") {
                            // Handle failure case
                            $error = isset($output2['error']['message']) ? $output2['error']['message'] : 'Unknown error';
                            return response()->json(['status' => 400, 'errors' => $error]);
                        }
                    }
                } else {
                    $error = isset($output['error']['message']) ? $output['error']['message'] : 'Unknown error';
                    return response()->json(['status' => 400, 'errors' => $error]);
                }
            } else {
                $insert['content'] = $this->common->saveImage($files2, $this->folder_content);
            }
            $insert['content_size'] = $this->common->getFileSize($insert['content'], $this->folder_content);
            $insert['content_duration'] = 0;
            $insert['is_rent'] = 0;
            $insert['rent_price'] = 0;
            $insert['is_comment'] = 1;
            $insert['is_download'] = 1;
            $insert['is_like'] = 1;
            $insert['total_view'] = 0;
            $insert['total_like'] = 0;
            $insert['total_dislike'] = 0;
            $insert['playlist_type'] = 0;
            $insert['is_admin_added'] = 1;

            if ($insert->save()) {
                return $this->common->API_Response(200, __('Label.data_add_successfully'));
            } else {
                return $this->common->API_Response(400, __('Label.data_not_added'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
