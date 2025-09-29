<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\File;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class Common extends Model
{
    private $folder_content = "content";
    private $folder_post = "/public/post/";
    
    // Image Functions
    public function saveImage($org_name, $folder)
    {
        try {
            $img_ext = $org_name->getClientOriginalExtension();
            $filename = date('d_m_Y_') . rand(0, 99) . '_' . uniqid() . '.' . $img_ext;
            $path = $org_name->move(base_path('storage/app/public/' . $folder), $filename);
            return $filename;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function getImage($folder = "", $name = "")
    {
        try {

            $appName = Config::get('app.image_url');

            if ($folder != "" && $name != "") {
                if ($folder == "user" || $folder == "artist") {

                    if (Storage::disk('public')->exists($folder . '/' . $name)) {
                        $name = $appName . $folder . '/' . $name;
                    } else {
                        $name = asset('assets/imgs/default.png');
                    }
                } else {

                    if (Storage::disk('public')->exists($folder . '/' . $name)) {
                        $name = $appName . $folder . '/' . $name;
                    } else {
                        $name = asset('assets/imgs/no_img.png');
                    }
                }
            } else {
                if ($folder == "user" || $folder == "artist") {
                    $name = asset('assets/imgs/default.png');
                } else {
                    $name = asset('assets/imgs/no_img.png');
                }
            }
            return $name;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function getVideo($folder = "", $name = "")
    {
        try {

            $appName = Config::get('app.image_url');

            if ($folder != "" && $name != "") {
                if (Storage::disk('public')->exists($folder . '/' . $name)) {
                    $name = $appName . $folder . '/' . $name;
                } else {
                    $name = "";
                }
            } else {
                $name = "";
            }
            return $name;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function imageNameToUrl($array, $column, $folder)
    {
        try {

            foreach ($array as $key => $value) {

                $appName = Config::get('app.image_url');

                if (isset($value[$column]) && $value[$column] != "") {

                    if ($folder == "user" || $folder == "artist") {

                        if (Storage::disk('public')->exists($folder . '/' . $value[$column])) {
                            $value[$column] = $appName . $folder . '/' . $value[$column];
                        } else {
                            $value[$column] = asset('assets/imgs/default.png');
                        }
                    } else {

                        if (Storage::disk('public')->exists($folder . '/' . $value[$column])) {
                            $value[$column] = $appName . $folder . '/' . $value[$column];
                        } else {
                            $value[$column] = asset('assets/imgs/no_img.png');
                        }
                    }
                } else {
                    if ($folder == "user" || $folder == "artist") {
                        $value[$column] = asset('assets/imgs/default.png');
                    } else {
                        $value[$column] = asset('assets/imgs/no_img.png');
                    }
                }
            }
            return $array;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function videoNameToUrl($array, $column, $folder)
    {
        try {

            foreach ($array as $key => $value) {

                $appName = Config::get('app.image_url');

                if (isset($value[$column]) && $value[$column] != "") {

                    if (Storage::disk('public')->exists($folder . '/' . $value[$column])) {
                        $value[$column] = $appName . $folder . '/' . $value[$column];
                    } else {
                        $value[$column] = "";
                    }
                } else {

                    $value[$column] = "";
                }
            }
            return $array;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function deleteImageToFolder($folder, $name)
    {
        try {

            Storage::disk('public')->delete($folder . '/' . $name);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function dummyImageMove($old_file, $folder)
    {
        try {

            $fileExtension = pathinfo(parse_url($old_file, PHP_URL_PATH), PATHINFO_EXTENSION);
            $filename = date('d_m_Y_') . rand(0, 99) . '_' . uniqid() . '.' . $fileExtension;

            $new_file = storage_path('app/public/') . $folder . '/' . $filename;

            File::copy($old_file, $new_file);

            return $filename;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    // API's Functions
    public function API_Response($status_code, $message, $array = [], $pagination = '')
    {
        try {
            $data['status'] = $status_code;
            $data['message'] = $message;

            if ($status_code == 200) {
                $data['result'] = $array;
            }

            if ($pagination) {
                $data['total_rows'] = $pagination['total_rows'];
                $data['total_page'] = $pagination['total_page'];
                $data['current_page'] = $pagination['current_page'];
                $data['more_page'] = $pagination['more_page'];
            }
            return $data;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function more_page($current_page, $page_size)
    {
        try {
            $more_page = false;
            if ($current_page < $page_size) {
                $more_page = true;
            }
            return $more_page;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function pagination_array($total_rows, $page_size, $current_page, $more_page)
    {
        try {
            $array['total_rows'] = $total_rows;
            $array['total_page'] = $page_size;
            $array['current_page'] = (int) $current_page;
            $array['more_page'] = $more_page;

            return $array;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function Pagination($data, $page_no)
    {
        try {
            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $total_rows = $data->count();
            $total_page = env('PAGE_LIMIT');
            $page_size = ceil($total_rows / $total_page);
            $current_page = $page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->more_page($current_page, $page_size);
            $pagination = $this->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get();

            $return['data'] = $data;
            $return['pagination'] = $pagination;
            return $return;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    // Common Functions
    public function createChannelName($name)
    {
        $channel = User::where('channel_name', $name)->first();
        if ($channel != null & isset($channel)) {
            $name = $name . Str::random(3);
        }
        return $name;
    }
    public function artist_tag_line()
    {
        $line = "Hey, I am artist on " . App_Name() . " App.";
        return $line;
    }
    public function user_tag_line()
    {
        $line = "Hey, I am user on " . App_Name() . " App.";
        return $line;
    }
    public function checkHashTag($hashTag)
    {
        try {

            if (strpos($hashTag, '#') !== false) {

                $remove = substr($hashTag, strpos($hashTag, '#'));
                $tag = explode('#', $remove);

                $id = [];
                if (count($tag) > 0) {
                    foreach ($tag as $key => $value) {

                        if ($value && $value != "") {

                            $value = ltrim($value);
                            $tag = explode(' ', $value)[0];

                            $row = Hashtag::where('name', 'like', '%' . $tag . '%')->first();
                            if (isset($row->id)) {

                                $id[] = $row->id;
                                $row->increment('total_used', 1);
                            } else {

                                $data['name'] = $tag;
                                $data['total_used'] = 1;
                                $hashtag_id = Hashtag::insertGetId($data);
                                $id[] = $hashtag_id;
                            }
                        }
                    }
                    return $id;
                }
            } else {
                return array();
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function getFileSize($name, $folder)
    {
        try {

            $appName = Config::get('app.image_url');
            if ($folder != "" && $name != "") {

                if (Storage::disk('public')->exists($folder . '/' . $name)) {

                    $size = Storage::disk('public')->size($folder . '/' . $name);
                    $size = round($size / 1024, 2);
                    return $size;
                } else {
                    return 0;
                }
            }
            return 0;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function package_expiry()
    {
        $all_data = Transaction::where('status', 1)->get();
        for ($i = 0; $i < count($all_data); $i++) {

            if ($all_data[$i]['expiry_date'] <= date("Y-m-d")) {
                $all_data[$i]->status = 0;
                $all_data[$i]->save();
            }
        }
        return true;
    }
    public function is_package_buy($user_id, $package_id)
    {
        $this->package_expiry();

        $is_buy = Transaction::where('user_id', $user_id)->where('package_id', $package_id)->where('status', 1)->first();
        if (!empty($is_buy)) {
            return 1;
        } else {
            return 0;
        }
    }
    public function is_any_package_buy($user_id)
    {
        $this->package_expiry();

        $is_buy = Transaction::where('user_id', $user_id)->where('status', 1)->first();
        if (!empty($is_buy)) {
            return 1;
        } else {
            return 0;
        }
    }
    public function get_block_channel($user_id)
    {
        $data = Block_Channel::where('user_id', $user_id)->where('status', 1)->get();

        $user_data = array();
        foreach ($data as $key => $value) {
            $user_data[] = $value->block_channel_id;
        }
        return $user_data;
    }
    public function get_content_report($user_id)
    {
        $data = Content_Report::where('user_id', $user_id)->where('status', 1)->get();

        $content_data = array();
        foreach ($data as $key => $value) {
            $content_data[] = $value->content_id;
        }
        return $content_data;
    }
    public function get_subscriber($user_id)
    {
        $data = Subscriber::where('user_id', $user_id)->where('type', 1)->where('status', 1)->with('to_user')->get();

        $user_data = array();
        foreach ($data as $key => $value) {

            if ($value['to_user'] != null && isset($value['to_user'])) {
                $user_data[] = $value['to_user']['channel_id'];
            }
        }
        return $user_data;
    }
    public function get_interests_category($user_id)
    {
        $data = Interests_Category::where('user_id', $user_id)->orderBy('count', 'desc')->get();

        $category_data = array();
        foreach ($data as $key => $value) {
            $category_data[] = $value['category_id'];
        }
        return $category_data;
    }
    public function add_interests_category($user_id, $content_id, $status)
    {
        $content = Content::where('id', $content_id)->first();
        if (isset($content) && $content != null) {

            $interestes = Interests_Category::where('user_id', $user_id)->where('category_id', $content['category_id'])->first();
            if (isset($interestes) && $interestes != null) {

                if ($status == 1) {
                    $interestes->increment('count', 1);
                } else if ($status == 2) {
                    $interestes->decrement('count', 1);
                }
            } else {

                $inset['user_id'] = $user_id;
                $inset['category_id'] = $content['category_id'];
                $inset['count'] = 1;
                Interests_Category::insertGetId($inset);
            }
        }
        return true;
    }
    public function add_interests_hashtag($content_type, $user_id)
    {
        $total_like = Like::where('user_id', $user_id)->where('content_type', $content_type)->where('status', 1)->with('content')->latest()->get();

        $tag_id = array();
        foreach ($total_like as $key => $value) {

            if ($value['content'] != null && isset($value['content']) && $value['content']['hashtag_id'] != "0") {

                $hashtag_id = explode(",", $value['content']['hashtag_id']);
                foreach ($hashtag_id as $key => $value) {
                    $tag_id[] = $value;
                }
            }
        }

        $final_tag_id = array();
        foreach ($tag_id as $key => $value) {

            $final_tag_id[$value] = count(array_keys($tag_id, $value));
        }
        arsort($final_tag_id);
        $final_tag_id = array_slice($final_tag_id, 0, 5, true);

        if (sizeof($final_tag_id) > 0) {
            foreach ($final_tag_id as $key => $value) {

                $check_tag_id = Interests_Hashtag::where('user_id', $user_id)->where('hashtag_id', $key)->first();
                if (isset($check_tag_id) && $check_tag_id != null) {

                    Interests_Hashtag::where('user_id', $user_id)->where('hashtag_id', $key)->update(['count' => $value]);
                } else {

                    $inset = new Interests_Hashtag();
                    $inset['user_id'] = $user_id;
                    $inset['hashtag_id'] = $key;
                    $inset['count'] = $value;
                    $inset->save();
                }
            }

            // Delete Interests
            $data = Interests_Hashtag::where('user_id', $user_id)->orderBy('count', 'desc')->orderBy('id', 'desc')->limit(5)->get();
            $interestes_ids = array();
            foreach ($data as $key => $value) {
                $interestes_ids[] = $value['id'];
            }
            Interests_Hashtag::whereNotIn('id', $interestes_ids)->delete();
        }
        return true;
    }
    public function get_user_interests($user_id)
    {
        return Interests_Hashtag::where('user_id', $user_id)->orderBy('count', 'desc')->latest()->get();
    }
    public function SetSmtpConfig()
    {
        $smtp = Smtp_Setting::latest()->first();
        if (isset($smtp) && $smtp != null && $smtp['status'] == 1) {

            if ($smtp) {
                $data = [
                    'driver' => 'smtp',
                    'host' => $smtp->host,
                    'port' => $smtp->port,
                    'encryption' => 'tls',
                    'username' => $smtp->user,
                    'password' => $smtp->pass,
                    'from' => [
                        'address' => $smtp->from_email,
                        'name' => $smtp->from_name
                    ]
                ];
                Config::set('mail', $data);
            }
        }
        return true;
    }
    public function Send_Mail($type, $email, $content_title = "", $message = "") // Type = 1- Register, 2- Transaction, 3- Report, 4- User Penal Active
    {
        try {

            $this->SetSmtpConfig();

            $smtp = Smtp_Setting::latest()->first();
            if (isset($smtp) && $smtp != false && $smtp['status'] == 1) {

                if ($type == 1) {

                    $title = "Welcome to " . App_Name() . "! Your Login is Successful";
                    $body = "Welcome to " . App_Name() . " App & Enjoy this app.";
                    $view = 'mail.register';
                } else if ($type == 2) {

                    $title = App_Name() . " - Transaction";
                    $body = "Welcome to " . App_Name() . " App & Enjoy this app. You have Successfully Transaction.";
                    $view = 'mail.transaction';
                } else if ($type == 3) {

                    $title = App_Name() . " - Content Report";
                    $body = "Alert, Your " . $content_title . " Content on Report. Report Reason is " . $message . ".";
                    $view = 'mail.report';
                } else if ($type == 4) {

                    $title = "Welcome to " . App_Name() . " Your User Panel is Now Active";
                    $body = "Congratulations, Your User Penal is Actived.";
                    $view = 'mail.user_panel_active';
                } else {
                    return true;
                }
                $details = [
                    'title' => $title,
                    'body' => $body
                ];

                // Send Mail
                try {
                    Mail::to($email)->send(new \App\Mail\mail($details, $view));
                    return true;
                } catch (\Swift_TransportException $e) {
                    return true;
                }
            } else {
                return true;
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function music_section_query($user_id, $content_type, $category_id, $language_id, $artist_id, $order_by_view, $order_by_like, $order_by_upload, $no_of_content)
    {
        try {

            // Remove Not Episode & Content in Podcasts, Radio, Playlist 
            if ($content_type == 4) {

                $episode = Episode::select('podcasts_id')->where('status', 1)->groupBy('podcasts_id')->get();
                $podcasts_id = [];
                for ($i = 0; $i < count($episode); $i++) {
                    $podcasts_id[$i] = $episode[$i]['podcasts_id'];
                }

                $content = Content::whereIn('id', $podcasts_id)->where('content_type', $content_type)->where('status', 1)->where('is_rent', 0);
            } else if ($content_type == 6) {

                $radio_content = Radio_Content::select('radio_id')->where('status', 1)->groupBy('radio_id')->get();
                $radio_id = [];
                for ($i = 0; $i < count($radio_content); $i++) {
                    $radio_id[$i] = $radio_content[$i]['radio_id'];
                }

                $content = Content::whereIn('id', $radio_id)->where('content_type', $content_type)->where('status', 1)->where('is_rent', 0);
            } else if ($content_type == 5) {

                $playlist = Playlist_Content::select('playlist_id')->where('status', 1)->groupBy('playlist_id')->get();
                $playlist_id = [];
                for ($i = 0; $i < count($playlist); $i++) {
                    $playlist_id[$i] = $playlist[$i]['playlist_id'];
                }
                $content = Content::whereIn('id', $playlist_id)->where('content_type', $content_type)->where('status', 1)->where('is_rent', 0);
            } else {
                $content = Content::where('content_type', $content_type)->where('status', 1)->where('is_rent', 0);
            }

            if ($category_id != 0) {
                $content->where('category_id', $category_id);
            }
            if ($language_id != 0) {
                $content->where('language_id', $language_id);
            }
            if ($artist_id != 0) {
                $content->where('artist_id', $artist_id);
            }
            if ($order_by_view == 2) {
                $content->orderBy('total_view', 'desc');
            }
            if ($order_by_like == 2) {
                $content->orderBy('total_like', 'desc');
            }
            if ($order_by_upload == 2) {
                $content->orderBy('id', 'desc');
            }

            $query =  $content->take($no_of_content)->get();

            for ($j = 0; $j < count($query); $j++) {

                $query[$j]['portrait_img'] = $this->getImage($this->folder_content, $query[$j]['portrait_img']);
                $query[$j]['landscape_img'] = $this->getImage($this->folder_content, $query[$j]['landscape_img']);
                if ($query[$j]['content_upload_type'] == 'server_video') {
                    $query[$j]['content'] = $this->getVideo($this->folder_content, $query[$j]['content']);
                }

                $query[$j]['user_id'] = $this->getUserId($query[$j]['channel_id']);
                $query[$j]['channel_name'] = $this->getChannelName($query[$j]['channel_id']);
                $query[$j]['channel_image'] = $this->getChannelImage($query[$j]['channel_id']);
                $query[$j]['category_name'] = $this->getCategoryName($query[$j]['category_id']);
                $query[$j]['artist_name'] = $this->getArtistName($query[$j]['artist_id']);
                $query[$j]['language_name'] = $this->getLanguageName($query[$j]['language_id']);
                $query[$j]['is_subscribe'] = $this->is_subscribe(1, $user_id, $query[$j]['user_id']); // Type 1- Channel, 2- Artist
                $query[$j]['total_comment'] = $this->getTotalComment($query[$j]['id']);
                $query[$j]['is_user_like_dislike'] = $this->getUserLikeDislike($user_id, $query[$j]['content_type'], $query[$j]['id'], 0);
                $query[$j]['total_subscriber'] = $this->total_subscriber($query[$j]['user_id']);
                $query[$j]['total_episode'] = $this->getTotalEpisode($query[$j]['id']);
                $query[$j]['is_buy'] = $this->is_any_package_buy($user_id);
                $query[$j]['stop_time'] = $this->getContentStopTime($user_id, $query[$j]['content_type'], $query[$j]['id'], 0);
            }

            return $query;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function music_section_details_query($content_type, $category_id, $language_id, $artist_id, $order_by_view, $order_by_like, $order_by_upload)
    {
        try {

            // Remove Not Episode & Content in Podcasts, Radio, Playlist 
            if ($content_type == 4) {

                $episode = Episode::select('podcasts_id')->where('status', 1)->groupBy('podcasts_id')->get();
                $podcasts_id = [];
                for ($i = 0; $i < count($episode); $i++) {
                    $podcasts_id[$i] = $episode[$i]['podcasts_id'];
                }

                $content = Content::whereIn('id', $podcasts_id)->where('content_type', $content_type)->where('status', 1)->where('is_rent', 0);
            } else if ($content_type == 6) {

                $radio_content = Radio_Content::select('radio_id')->where('status', 1)->groupBy('radio_id')->get();
                $radio_id = [];
                for ($i = 0; $i < count($radio_content); $i++) {
                    $radio_id[$i] = $radio_content[$i]['radio_id'];
                }

                $content = Content::whereIn('id', $radio_id)->where('content_type', $content_type)->where('status', 1)->where('is_rent', 0);
            } else if ($content_type == 5) {

                $playlist = Playlist_Content::select('playlist_id')->where('status', 1)->groupBy('playlist_id')->get();
                $playlist_id = [];
                for ($i = 0; $i < count($playlist); $i++) {
                    $playlist_id[$i] = $playlist[$i]['playlist_id'];
                }
                $content = Content::whereIn('id', $playlist_id)->where('content_type', $content_type)->where('status', 1)->where('is_rent', 0);
            } else {
                $content = Content::where('content_type', $content_type)->where('status', 1)->where('is_rent', 0);
            }
            if ($category_id != 0) {
                $content->where('category_id', $category_id);
            }
            if ($language_id != 0) {
                $content->where('language_id', $language_id);
            }
            if ($artist_id != 0) {
                $content->where('artist_id', $artist_id);
            }
            if ($order_by_view == 2) {
                $content->orderBy('total_view', 'desc');
            }
            if ($order_by_like == 2) {
                $content->orderBy('total_like', 'desc');
            }
            if ($order_by_upload == 2) {
                $content->orderBy('id', 'desc');
            }
            $query =  $content;
            return $query;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function getCategoryName($category_id)
    {
        $category_name = "";
        $category = Category::where('id', $category_id)->first();
        if ($category != null & isset($category)) {
            $category_name = $category['name'];
        }
        return $category_name;
    }
    public function getArtistName($artist_id)
    {
        $artist_name = "";
        $artist = Artist::where('id', $artist_id)->first();
        if ($artist != null & isset($artist)) {
            $artist_name = $artist['name'];
        }
        return $artist_name;
    }
    public function getLanguageName($language_id)
    {
        $language_name = "";
        $language = Language::where('id', $language_id)->first();
        if ($language != null & isset($language)) {
            $language_name = $language['name'];
        }
        return $language_name;
    }
    public function getChannelName($channel_id)
    {
        $channel_name = "";
        $channel = User::where('channel_id', $channel_id)->first();
        if ($channel != null & isset($channel)) {
            $channel_name = $channel['channel_name'];
        }
        return $channel_name;
    }
    public function getChannelImage($channel_id)
    {
        $channel_image = asset('assets/imgs/default.png');

        $channel = User::where('channel_id', $channel_id)->first();
        if ($channel != null & isset($channel) && $channel['image'] != "") {

            $appName = Config::get('app.image_url');
            if (Storage::disk('public')->exists('user/' . $channel['image'])) {
                $channel_image = $appName . 'user/' . $channel['image'];
            }
        }
        return $channel_image;
    }
    public function getTotalComment($content_id)
    {
        $total_comment = Comment::where('content_id', $content_id)->where('comment_id', 0)->where('status', 1)->count();
        return $total_comment;
    }
    public function getTotalEpisode($podcasts_id)
    {
        $total_episode = Episode::where('podcasts_id', $podcasts_id)->where('status', 1)->count();
        return $total_episode;
    }
    public function is_subscribe($type, $user_id, $to_user_id)
    {
        try {

            $is_subscribe = 0;
            if ($type == 1) {
                $subscribe = Subscriber::where('user_id', $user_id)->where('to_user_id', $to_user_id)->where('status', 1)->first();
                if ($subscribe != null && isset($subscribe)) {
                    $is_subscribe = 1;
                }
            }

            return $is_subscribe;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function is_block($user_id, $to_user_id)
    {
        try {

            $is_block = 0;
            $block = Block_Channel::where('user_id', $user_id)->where('block_user_id', $to_user_id)->where('status', 1)->first();
            if ($block != null && isset($block)) {
                $is_block = 1;
            }

            return $is_block;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function getUserLikeDislike($user_id, $content_type, $content_id, $episode_id)
    {
        try {

            $is_user_like_dislike = 0;
            $like_dislike = Like::where('user_id', $user_id)->where('content_type', $content_type)->where('content_id', $content_id)->where('episode_id', $episode_id)->first();
            if ($like_dislike != null && isset($like_dislike)) {
                $is_user_like_dislike = $like_dislike['status'];
            }
            return $is_user_like_dislike;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function getUserId($channel_id)
    {
        $user_id = 0;
        $user = User::where('channel_id', $channel_id)->first();
        if ($user != null & isset($user)) {
            $user_id = $user['id'];
        }
        return $user_id;
    }
    public function is_total_content($channel_id)
    {
        $total_contents = 0;
        $total_content = Content::where('channel_id', $channel_id)->where('status', 1)->count();
        $total_post = Post::where('channel_id', $channel_id)->where('status', 1)->count();
        
        $total_contents = $total_content + $total_post;
        
        return $total_contents;
    }
    public function total_subscriber($to_user_id)
    {
        $total_subscriber = 0;
        $total_subscriber = Subscriber::where('to_user_id', $to_user_id)->where('type', 1)->where('status', 1)->count();
        return $total_subscriber;
    }
    public function getRentBuy($user_id, $content_id)
    {
        try {
            $is_rent_buy = 0;

            $transaction = Rent_Transaction::where('user_id', $user_id)->where('content_id', $content_id)->where('status', 1)->first();
            if ($transaction != null && isset($transaction)) {
                $is_rent_buy = 1;
            }
            return $is_rent_buy;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function save_notification($type = 0, $title = "", $user_id = 0, $from_user_id = 0, $content_id = 0) // 1-Admin, 2-Like, 3-Comment, 4-Subscribe, 5-Hide_Content	
    {
        try {

            $data = [
                'type' => $type,
                'title' => $title,
                'message' => "",
                'image' => "",
                'user_id' => $user_id,
                'from_user_id' => $from_user_id,
                'content_id' => $content_id,
            ];
            Notification::insertGetId($data);

            $user = User::find($from_user_id);
            if ($user && isset($user['device_token'])) {
                $toUser = [$user['device_token']];

                $setting = Setting_Data();
                $ONESIGNAL_APP_ID = $setting['onesignal_apid'];
                $ONESIGNAL_REST_KEY = $setting['onesignal_rest_key'];

                $fields = [
                    'app_id' => $ONESIGNAL_APP_ID,
                    'headings' => ['en' => App_Name()],
                    'contents' => ['en' => $title],
                    'channel_for_external_user_ids' => 'push'
                ];

                if ($user['device_type'] == 1) {
                    $fields['include_android_reg_ids'] = $toUser;
                    $fields['isAndroid'] = true;
                } elseif ($user['device_type'] == 2) {
                    $fields['include_player_ids'] = $toUser;
                    $fields['isIos'] = true;
                } elseif ($user['device_type'] == 3) {
                    $fields['include_player_ids'] = $toUser;
                    $fields['isAnyWeb'] = true;
                }

                $fields = json_encode($fields);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Basic ' . $ONESIGNAL_REST_KEY
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                $response = curl_exec($ch);

                curl_close($ch);
                return true;
            }

            return true;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function getContentStopTime($user_id, $content_type, $content_id, $episode_id)
    {
        try {
            $stop_time = 0;
            $stop_time_data = History::where('user_id', $user_id)->where('content_type', $content_type)->where('content_id', $content_id)->where('episode_id', $episode_id)->first();
            if ($stop_time_data != null && isset($stop_time_data)) {
                $stop_time = $stop_time_data['stop_time'];
            }
            return $stop_time;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_user_budget($user_id)
    {
        $budget = 0;
        $data = User::where('id', $user_id)->first();
        if ($data != null && isset($data)) {
            $budget = $data['wallet_balance'];
        }
        return $budget;
    }
    public function get_total_view_click_coin($ads_id)
    {
        $total_coin = 0;
        $sum = Ads_View_Click_Count::where('ads_id', $ads_id)->sum('total_coin');
        if ($sum != null && isset($sum)) {
            $total_coin = $sum;
        }
        return $total_coin;
    }
    public function InactiveAds($ads_id = 0)
    {
        if ($ads_id != 0) {
            $ads = Ads::where('id', $ads_id)->where('status', 1)->get();
        } else {
            $ads = Ads::where('status', 1)->get();
        }
        $settingData = Setting_Data();
        for ($i = 0; $i < count($ads); $i++) {

            if ($ads[$i]['type'] == 1) {

                $ads_cpv = $settingData['banner_ads_cpv'];
                $user_wallet_coin = $this->get_user_budget($ads[$i]['user_id']);
                if ($ads_cpv > $user_wallet_coin) {
                    $ads[$i]['status'] = 0;
                    $ads[$i]->save();
                } else {

                    $total_view_click_coin = $this->get_total_view_click_coin($ads[$i]['id']);
                    $remening_budget = $ads[$i]['budget'] - $total_view_click_coin;
                    if ($ads_cpv > $remening_budget) {
                        $ads[$i]['status'] = 0;
                        $ads[$i]->save();
                    }
                }
            } else if ($ads[$i]['type'] == 2) {

                $ads_cpv = $settingData['interstital_ads_cpv'];
                $user_wallet_coin = $this->get_user_budget($ads[$i]['user_id']);
                if ($ads_cpv > $user_wallet_coin) {
                    $ads[$i]['status'] = 0;
                    $ads[$i]->save();
                } else {

                    $total_view_click_coin = $this->get_total_view_click_coin($ads[$i]['id']);
                    $remening_budget = $ads[$i]['budget'] - $total_view_click_coin;
                    if ($ads_cpv > $remening_budget) {
                        $ads[$i]['status'] = 0;
                        $ads[$i]->save();
                    }
                }
            } else if ($ads[$i]['type'] == 3) {

                $ads_cpv = $settingData['reward_ads_cpv'];
                $user_wallet_coin = $this->get_user_budget($ads[$i]['user_id']);
                if ($ads_cpv > $user_wallet_coin) {
                    $ads[$i]['status'] = 0;
                    $ads[$i]->save();
                } else {

                    $total_view_click_coin = $this->get_total_view_click_coin($ads[$i]['id']);
                    $remening_budget = $ads[$i]['budget'] - $total_view_click_coin;
                    if ($ads_cpv > $remening_budget) {
                        $ads[$i]['status'] = 0;
                        $ads[$i]->save();
                    }
                }
            }
        }
    }
    public function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $oldValue = env($envKey);

        if (strpos($str, $envKey) !== false) {
            $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
        } else {
            $str .= "{$envKey}={$envValue}\n";
        }

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
        return $envValue;
    }
    public function Delete_Reels()
    {

        // $delete_day = Delete_Reels_Day();
        // if (isset($delete_day) && $delete_day != "" && $delete_day != 0) {

        //     $currentDate = date('Y-m-d', strtotime('-' . $delete_day . ' days'));

        //     $content = Content::where('content_type', 3)->where('created_at', '<=', $currentDate)->get();
        //     for ($i = 0; $i < count($content); $i++) {

        //         $old_hashtag = explode(',', $content[$i]['hashtag_id']);
        //         Hashtag::whereIn('id', $old_hashtag)->decrement('total_used', 1);

        //         $this->deleteImageToFolder($this->folder, $content[$i]['portrait_img']);
        //         $this->deleteImageToFolder($this->folder, $content[$i]['content']);
        //         $content[$i]->delete();

        //         // Content Releted Data Delete
        //         Comment::where('content_id', $content[$i])->delete();
        //         Content_Report::where('content_id', $content[$i])->delete();
        //         History::where('content_id', $content[$i])->delete();
        //         Like::where('content_id', $content[$i])->delete();
        //         Notification::where('content_id', $content[$i])->delete();
        //         View::where('content_id', $content[$i])->delete();
        //         Watch_later::where('content_id', $content[$i])->delete();
        //     }
        // }
        return true;
    }
    public function is_user_download_content($user_id)
    {
        try {

            $is_user_download_content = 0;
            $package_data = Transaction::where('user_id', $user_id)->where('status', 1)->with('package')->latest()->first();
            if (isset($package_data) && $package_data != null && $package_data['package'] != null) {
                $is_user_download_content = $package_data['package']['download'];
            }
            return $is_user_download_content;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function getimagefromvideo($video)
    {
        try {
            $videoPath = $this->folder_post . $video; // Path to the input video

            $filename = "ffmpeg_" . date('d_m_Y_') . rand(1111, 9999) . '.jpg';
            $imageOutputPath =  $this->folder_post . $filename; // Path to save the output image

            FFMpeg::fromDisk('local') // Specify your disk if using Laravel's filesystem
                ->open($videoPath) // Open the video file
                ->getFrameFromSeconds(01) // Get the frame at the 1 second
                ->export() // Prepare for export
                ->toDisk('local') // Specify your disk for the output
                ->save($imageOutputPath);
            return $filename;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function Delete_All_Data($id)
    {
        Post_Comment::where('post_id', $id)->delete();
        Post_Like::where('post_id', $id)->delete();
        Post_View::where('post_id', $id)->delete();
        Post_Report::where('post_id', $id)->delete();
    }
    public function getHashTag($hashTag)
    {
        try {
            $tag_id = explode(',', $hashTag);
            $hashtage = [];
            if (count($tag_id) > 0) {
                foreach ($tag_id as $key => $value) {
                    $row = Hashtag::where('id', $value)->first();
                    if (isset($row->id)) {

                        $hashtage[] = $row;
                    }
                }
                return $hashtage;
            } else {
                return $hashtage;
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_all_count_for_post($array, $user_id)
    {

        // Comment
        $array['total_comment'] = Post_Comment::where('post_id', $array['id'])->count();

        // Like
        $array['total_like'] = Post_Like::where('post_id', $array['id'])->where('status', 1)->count();

        $is_like = Post_Like::where('post_id', $array['id'])->where('user_id', $user_id)->where('status', 1)->first();
        $array['is_like'] = 0;
        if ($is_like) {
            $array['is_like'] = 1;
        }

        // Subscriber
        $array['is_subscriber'] = 0;
        if ($user_id > 0 && $array['channel']['id'] > 0) {

            $is_favorite = Subscriber::where('user_id', $user_id)->where('to_user_id', $array['channel']['id'])->first();
            if ($is_favorite) {
                $array['is_subscriber'] = 1;
            }
        }

        return $array;
    }
    public function gift_buy($user_id, $gift_id)
    {
        $is_buy = Gift_Transaction::where('user_id', $user_id)->where('gift_id', $gift_id)->first();
        if (!empty($is_buy)) {
            return 1;
        } else {
            return 0;
        }
    }
    public function goLiveSendNotification($user_id)
    {
        try {

            $subscribe_user = Subscriber::where('type', 1)->where('to_user_id', $user_id)->with('user')->get();
            $user = User::where('id', $user_id)->first();
            if (count($subscribe_user) > 0 && isset($user) && $user != null) {

                // Separate device tokens by device type
                $androidTokens = [];
                $iosTokens = [];
                $webTokens = [];

                foreach ($subscribe_user as $subscriber) {
                    if ($subscriber['user'] && !empty($subscriber['user']['device_token'])) {
                        switch ($subscriber['user']['device_type']) {
                            case 1: // Android
                                $androidTokens[] = $subscriber['user']['device_token'];
                                break;
                            case 2: // iOS
                                $iosTokens[] = $subscriber['user']['device_token'];
                                break;
                            case 3: // Web
                                $webTokens[] = $subscriber['user']['device_token'];
                                break;
                        }
                    }
                }

                $message = "{$user->channel_name} is now live! Join the stream and interact.";

                // Notification content
                $setting = Setting_Data();
                $ONESIGNAL_APP_ID = $setting['onesignal_apid'];
                $ONESIGNAL_REST_KEY = $setting['onesignal_rest_key'];
                $fieldsTemplate = [
                    'app_id' => $ONESIGNAL_APP_ID,
                    'headings' => ['en' => App_Name()],
                    'contents' => ['en' => $message],
                    'channel_for_external_user_ids' => 'push',
                ];

                // Send notifications for Android
                if (!empty($androidTokens)) {
                    $fields = array_merge($fieldsTemplate, [
                        'include_android_reg_ids' => $androidTokens,
                        'isAndroid' => true,
                    ]);
                    $this->sendNotification($fields, $ONESIGNAL_REST_KEY);
                }
                // Send notifications for iOS
                if (!empty($iosTokens)) {
                    $fields = array_merge($fieldsTemplate, [
                        'include_player_ids' => $iosTokens,
                        'isIos' => true,
                    ]);
                    $this->sendNotification($fields, $ONESIGNAL_REST_KEY);
                }
                // Send notifications for Web
                if (!empty($webTokens)) {
                    $fields = array_merge($fieldsTemplate, [
                        'include_player_ids' => $webTokens,
                        'isAnyWeb' => true,
                    ]);
                    $this->sendNotification($fields, $ONESIGNAL_REST_KEY);
                }
            }
            return true;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    function sendNotification($fields, $restKey)
    {
        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $restKey,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return true;
    }
}
