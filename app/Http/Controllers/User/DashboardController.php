<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Exception;
use App\Models\Common;

class DashboardController extends Controller
{
    private $folder_content = "content";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {

            $user = User_Data();
            $channel_id = $user['channel_id'];

            // First Card
            $params['VideoCount'] = Content::where('channel_id', $channel_id)->where('content_type', 1)->count();
            $params['ReelsCount'] = Content::where('channel_id', $channel_id)->where('content_type', 3)->count();
            $params['PodcastsCount'] = Content::where('channel_id', $channel_id)->where('content_type', 4)->count();
            $params['PlaylistCount'] = Content::where('channel_id', $channel_id)->where('content_type', 5)->count();

            // Most View Content
            $params['top_video_view'] = Content::where('channel_id', $channel_id)->where('content_type', 1)->orderBy('total_view', 'desc')->where('status', 1)->take(5)->get();
            $params['top_reels_view'] = Content::where('channel_id', $channel_id)->where('content_type', 3)->orderBy('total_view', 'desc')->where('status', 1)->take(5)->get();
            $params['top_podcasts_view'] = Content::where('channel_id', $channel_id)->where('content_type', 4)->orderBy('total_view', 'desc')->where('status', 1)->take(5)->get();
            $this->common->imageNameToUrl($params['top_video_view'], 'portrait_img', $this->folder_content);
            $this->common->imageNameToUrl($params['top_reels_view'], 'portrait_img', $this->folder_content);
            $this->common->imageNameToUrl($params['top_podcasts_view'], 'portrait_img', $this->folder_content);

            // Most Like Content
            $params['top_video_like'] = Content::where('channel_id', $channel_id)->where('content_type', 1)->orderBy('total_like', 'desc')->where('status', 1)->take(5)->get();
            $params['top_reels_like'] = Content::where('channel_id', $channel_id)->where('content_type', 3)->orderBy('total_like', 'desc')->where('status', 1)->take(5)->get();
            $params['top_podcasts_like'] = Content::where('channel_id', $channel_id)->where('content_type', 4)->orderBy('total_like', 'desc')->where('status', 1)->take(5)->get();
            $this->common->imageNameToUrl($params['top_video_like'], 'portrait_img', $this->folder_content);
            $this->common->imageNameToUrl($params['top_reels_like'], 'portrait_img', $this->folder_content);
            $this->common->imageNameToUrl($params['top_podcasts_like'], 'portrait_img', $this->folder_content);

            return view('user.dashboard', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
