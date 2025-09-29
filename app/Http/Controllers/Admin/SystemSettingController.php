<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\Ads_Package;
use App\Models\Ads_Transaction;
use App\Models\Ads_View_Click_Count;
use App\Models\Artist;
use App\Models\Block_Channel;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Comment_Report;
use App\Models\Common;
use App\Models\Content;
use App\Models\Content_Report;
use App\Models\Episode;
use App\Models\Gift;
use App\Models\Gift_Transaction;
use App\Models\Hashtag;
use App\Models\History;
use App\Models\Interests_Category;
use App\Models\Interests_Hashtag;
use App\Models\Language;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Onboarding_Screen;
use App\Models\Package;
use App\Models\Package_Detail;
use App\Models\Page;
use App\Models\Playlist_Content;
use App\Models\Post;
use App\Models\Post_Comment;
use App\Models\Post_Content;
use App\Models\Post_Like;
use App\Models\Post_Report;
use App\Models\Post_View;
use App\Models\Radio_Content;
use App\Models\Read_Notification;
use App\Models\Rent_Section;
use App\Models\Rent_Transaction;
use App\Models\Report_Reason;
use App\Models\Section;
use App\Models\Social_Link;
use App\Models\Subscriber;
use App\Models\Transaction;
use App\Models\User;
use App\Models\View;
use App\Models\Watch_later;
use App\Models\Withdrawal_Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class SystemSettingController extends Controller
{
    public $common;
    private $folder_category = "category";
    private $folder_language = "language";
    private $folder_content = "content";
    private $folder_artist = "artist";
    private $folder_user = "user";
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {

            $params['data'] = [];
            return view('admin.system_setting.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function ClearData()
    {
        try {

            // Folder Name
            $ads = 'public/ads';
            $app = 'public/app';
            $artist = 'public/artist';
            $category = 'public/category';
            $content = 'public/content';
            $database = 'public/database';
            $language = 'public/language';
            $notification = 'public/notification';
            $package = 'public/package';
            $user = 'public/user';
            $post = 'public/post';
            $gift = 'public/gift';

            // Name Array
            $ads_name = [];
            $app_name = [];
            $artist_name = [];
            $category_name = [];
            $content_name = [];
            $database_name = [];
            $language_name = [];
            $notification_name = [];
            $package_name = [];
            $user_name = [];
            $post_name = [];
            $gift_name = [];

            // Get Files
            $ads_file = Storage::allFiles($ads);
            $app_file = Storage::allFiles($app);
            $artist_file = Storage::allFiles($artist);
            $category_file = Storage::allFiles($category);
            $content_file = Storage::allFiles($content);
            $database_file = Storage::allFiles($database);
            $language_file = Storage::allFiles($language);
            $notification_file = Storage::allFiles($notification);
            $package_file = Storage::allFiles($package);
            $user_file = Storage::allFiles($user);
            $post_file = Storage::allFiles($post);
            $gift_file = Storage::allFiles($gift);

            // Add Name In Array
            foreach ($ads_file as $file_name) {
                array_push($ads_name, pathinfo($file_name)['basename']);
            }
            foreach ($category_file as $file_name) {
                array_push($category_name, pathinfo($file_name)['basename']);
            }
            foreach ($language_file as $language_file) {
                array_push($language_name, pathinfo($language_file)['basename']);
            }
            foreach ($artist_file as $artist_file) {
                array_push($artist_name, pathinfo($artist_file)['basename']);
            }
            foreach ($user_file as $user_file) {
                array_push($user_name, pathinfo($user_file)['basename']);
            }
            foreach ($content_file as $content_file) {
                array_push($content_name, pathinfo($content_file)['basename']);
            }
            foreach ($package_file as $package_file) {
                array_push($package_name, pathinfo($package_file)['basename']);
            }
            foreach ($app_file as $app_file) {
                array_push($app_name, pathinfo($app_file)['basename']);
            }
            foreach ($database_file as $database_file) {
                array_push($database_name, pathinfo($database_file)['basename']);
            }
            foreach ($notification_file as $notification_file) {
                array_push($notification_name, pathinfo($notification_file)['basename']);
            }
            foreach ($post_file as $post_file) {
                array_push($post_name, pathinfo($post_file)['basename']);
            }
            foreach ($gift_file as $gift_file) {
                array_push($gift_name, pathinfo($gift_file)['basename']);
            }

            // Delete File In Folder
            foreach ($ads_name as $key => $value) {

                $ads_file_check = Ads::select('id')->where('image', $value)->orwhere('video', $value)->first();
                if ($ads_file_check == null) {
                    $this->common->deleteImageToFolder('ads', $value);
                }
            }
            foreach ($category_name as $key => $value) {

                $category_file_check = Category::select('id')->where('image', $value)->first();
                if ($category_file_check == null) {
                    $this->common->deleteImageToFolder('category', $value);
                }
            }
            foreach ($language_name as $key => $value) {

                $language_file_check = Language::select('id')->where('image', $value)->first();
                if ($language_file_check == null) {
                    $this->common->deleteImageToFolder('language', $value);
                }
            }
            foreach ($artist_name as $key => $value) {

                $artist_file_check = Artist::select('id')->where('image', $value)->first();
                if ($artist_file_check == null) {
                    $this->common->deleteImageToFolder('artist', $value);
                }
            }
            foreach ($package_name as $key => $value) {

                $package_file_check = Package::select('id')->where('image', $value)->first();
                $adspackage_file_check = Ads_Package::select('id')->where('image', $value)->first();
                if ($package_file_check == null && $adspackage_file_check == null) {
                    $this->common->deleteImageToFolder('package', $value);
                }
            }
            foreach ($user_name as $key => $value) {

                $user_file_check = User::select('id')->where('image', $value)->orwhere('id_proof', $value)->orwhere('cover_img', $value)->first();
                if ($user_file_check == null) {
                    $this->common->deleteImageToFolder('user', $value);
                }
            }
            foreach ($content_name as $key => $value) {

                $content_file_check = Content::select('id')->where('portrait_img', $value)->orwhere('landscape_img', $value)->orwhere('content', $value)->first();
                $content_file_check_1 = Episode::select('id')->where('portrait_img', $value)->orwhere('landscape_img', $value)->orwhere('episode_audio', $value)->first();

                if ($content_file_check == null && $content_file_check_1 == null) {
                    $this->common->deleteImageToFolder('content', $value);
                }
            }
            foreach ($app_name as $key => $value) {

                $app_file_check = Page::select('id')->where('icon', $value)->first();
                $app_file_check_1 = Social_Link::select('id')->where('image', $value)->first();
                $app_file_check_2 = Onboarding_Screen::select('id')->where('image', $value)->first();

                $settingData = Setting_Data();
                $app_file_check_2 = 'yes';
                if ($settingData['app_logo'] != $value) {
                    $app_file_check_2 = 'no';
                }

                if ($app_file_check == null && $app_file_check_1 == null && $app_file_check_2 == null && $app_file_check_2 == 'no') {
                    $this->common->deleteImageToFolder('app', $value);
                }
            }
            foreach ($database_name as $key => $value) {
                $this->common->deleteImageToFolder('database', $value);
            }
            foreach ($notification_name as $key => $value) {

                $notification_file_check = Notification::select('id')->where('image', $value)->first();
                if ($notification_file_check == null) {
                    $this->common->deleteImageToFolder('notification', $value);
                }
            }
            foreach ($post_name as $key => $value) {

                $post_file_check = Post_Content::select('id')->where('content_url', $value)->orwhere('thumbnail_image', $value)->first();
                if ($post_file_check == null) {
                    $this->common->deleteImageToFolder('post', $value);
                }
            }
            foreach ($gift_name as $key => $value) {

                $gift_file_check = Gift::select('id')->where('image', $value)->first();
                if ($gift_file_check == null) {
                    $this->common->deleteImageToFolder('gift', $value);
                }
            }

            return response()->json(array('status' => 200, 'success' => 'Data Clear Successfully.'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function DownloadSqlFile()
    {
        try {

            Artisan::call('config:clear');

            $storageAt = storage_path() . "/app/public/database";
            if (!file_exists($storageAt)) {
                File::makeDirectory($storageAt, 0755, true, true);
            }

            $mysqlHostName = env('DB_HOST');
            $mysqlUserName = env('DB_USERNAME');
            $mysqlPassword = env('DB_PASSWORD');
            $DbName = env('DB_DATABASE');

            // get all table name
            $result = DB::select("SHOW TABLES");
            $prep = "Tables_in_$DbName";

            foreach ($result as $res) {
                $tables[] =  $res->$prep;
            }

            $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword", array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $statement = $connect->prepare("SHOW TABLES");
            $statement->execute();
            $result = $statement->fetchAll();

            $output = '';
            foreach ($tables as $table) {

                $show_table_query = "SHOW CREATE TABLE " . $table . "";
                $statement = $connect->prepare($show_table_query);
                $statement->execute();
                $show_table_result = $statement->fetchAll();

                foreach ($show_table_result as $show_table_row) {
                    $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
                }
                $select_query = "SELECT * FROM " . $table . "";
                $statement = $connect->prepare($select_query);
                $statement->execute();
                $total_row = $statement->rowCount();

                for ($count = 0; $count < $total_row; $count++) {
                    $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
                    $table_column_array = array_keys($single_result);
                    $table_value_array = array_values($single_result);
                    $output .= "\nINSERT INTO $table (";
                    $output .= "`" . implode("`, `", $table_column_array) . "`) VALUES (";
                    $output .= "'" . implode("', '", $table_value_array) . "');\n";
                }
            }

            $file_name = App_Name() . '_db_' . date('d_m_Y') . '.sql';
            $file_handle = fopen(storage_path() . '/app/public/database/' . $file_name, 'w+');
            fwrite($file_handle, $output);
            fclose($file_handle);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file_name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize(storage_path() . '/app/public/database/' . $file_name));
            ob_clean();
            flush();
            readfile(storage_path() . '/app/public/database/' . $file_name);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function DummyData()
    {
        try {

            $category = [
                ['name' => 'Bollywood', 'image' => $this->common->dummyImageMove(asset('assets/dummy_imgs/cat_1.jpg'), $this->folder_category), 'type' => 1, 'status' => 1],
                ['name' => 'Romance	', 'image' => $this->common->dummyImageMove(asset('assets/dummy_imgs/cat_2.jpg'), $this->folder_category), 'type' => 1, 'status' => 1],
                ['name' => 'Sleep	', 'image' => $this->common->dummyImageMove(asset('assets/dummy_imgs/cat_3.jpg'), $this->folder_category), 'type' => 2, 'status' => 1],
                ['name' => 'Workout	', 'image' => $this->common->dummyImageMove(asset('assets/dummy_imgs/cat_4.jpg'), $this->folder_category), 'type' => 2, 'status' => 1],
            ];
            Category::insert($category);

            $language = [
                ['name' => 'Hindi', 'image' => $this->common->dummyImageMove(asset('assets/dummy_imgs/lang_1.jpg'), $this->folder_language), 'status' => 1],
                ['name' => 'English', 'image' => $this->common->dummyImageMove(asset('assets/dummy_imgs/lang_2.jpg'), $this->folder_language), 'status' => 1],
            ];
            Language::insert($language);

            $artist = [
                ['name' => 'Arijit Singh', 'image' => $this->common->dummyImageMove(asset('assets/dummy_imgs/art_1.jpg'), $this->folder_artist), 'bio' => $this->common->artist_tag_line(), 'status' => 1],
                ['name' => 'A. R. Rahman', 'image' => $this->common->dummyImageMove(asset('assets/dummy_imgs/art_2.jpg'), $this->folder_artist), 'bio' => $this->common->artist_tag_line(), 'status' => 1],
            ];
            Artist::insert($artist);

            $channel_name = $this->common->createChannelName('henry');
            $user = [
                [
                    'channel_id' => $channel_name,
                    'channel_name' => 'Thoughts of Devloper',
                    'full_name' => 'Henry',
                    'email' => 'henry@dt.com',
                    'password' => Hash::make('henry'),
                    'mobile_number' => '0123456789',
                    'type' => 4,
                    'image' => $this->common->dummyImageMove(asset('assets/dummy_imgs/user_1.jpg'), $this->folder_user),
                    'cover_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/user_1.jpg'), $this->folder_user),
                    'description' => $this->common->user_tag_line(),
                    'device_type' => 0,
                    'device_token' => "",
                    'website' => "",
                    'facebook_url' => "",
                    'instagram_url' => "",
                    'twitter_url' => "",
                    'wallet_balance' => 0,
                    'wallet_earning' => 0,
                    'bank_name' => "",
                    'bank_code' => "",
                    'bank_address' => "",
                    'ifsc_no' => "",
                    'account_no' => "",
                    'id_proof' => "",
                    'address' => "",
                    'city' => "",
                    'state' => "",
                    'country' => "",
                    'pincode' => 0,
                    'user_penal_status' => 1,
                    'status' => 1
                ],
                [
                    'channel_id' => $this->common->createChannelName('jack'),
                    'channel_name' => 'Devloper Planet',
                    'full_name' => 'Jack',
                    'email' => 'jack@dt.com',
                    'password' => Hash::make('jack'),
                    'mobile_number' => '7845120369',
                    'type' => 4,
                    'image' => $this->common->dummyImageMove(asset('assets/dummy_imgs/user_2.jpg'), $this->folder_user),
                    'cover_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/user_2.jpg'), $this->folder_user),
                    'description' => $this->common->user_tag_line(),
                    'device_type' => 0,
                    'device_token' => "",
                    'website' => "",
                    'facebook_url' => "",
                    'instagram_url' => "",
                    'twitter_url' => "",
                    'wallet_balance' => 0,
                    'wallet_earning' => 0,
                    'bank_name' => "",
                    'bank_code' => "",
                    'bank_address' => "",
                    'ifsc_no' => "",
                    'account_no' => "",
                    'id_proof' => "",
                    'address' => "",
                    'city' => "",
                    'state' => "",
                    'country' => "",
                    'pincode' => 0,
                    'user_penal_status' => 0,
                    'status' => 1
                ],
            ];
            User::insert($user);

            $content = [
                [
                    'content_type' => 1,
                    'channel_id' => $channel_name,
                    'category_id' => 1,
                    'language_id' => 1,
                    'artist_id' => 0,
                    'hashtag_id' => 0,
                    'title' => 'Thoughts of Devloper',
                    'description' => 'Thoughts of Devloper',
                    'portrait_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_vid_1.jpg'), $this->folder_content),
                    'landscape_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_vid_1.jpg'), $this->folder_content),
                    'content_upload_type' => 'external_url',
                    'content' => 'http://media.w3.org/2010/05/sintel/trailer.mp4',
                    'content_size' => '0',
                    'content_duration' => 0,
                    'is_rent' => 0,
                    'rent_price' => 0,
                    'is_comment' => 1,
                    'is_download' => 1,
                    'is_like' => 1,
                    'total_view' => 4578,
                    'total_like' => 2700,
                    'total_dislike' => 15,
                    'playlist_type' => 0,
                    'is_admin_added' => 1,
                    'status' => 1,
                ],
                [
                    'content_type' => 1,
                    'channel_id' => $channel_name,
                    'category_id' => 1,
                    'language_id' => 1,
                    'artist_id' => 0,
                    'hashtag_id' => 0,
                    'title' => 'Devloper Planet',
                    'description' => 'Devloper Planet',
                    'portrait_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_vid_2.jpg'), $this->folder_content),
                    'landscape_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_vid_2.jpg'), $this->folder_content),
                    'content_upload_type' => 'external_url',
                    'content' => 'http://media.w3.org/2010/05/sintel/trailer.mp4',
                    'content_size' => '0',
                    'content_duration' => 0,
                    'is_rent' => 0,
                    'rent_price' => 0,
                    'is_comment' => 1,
                    'is_download' => 1,
                    'is_like' => 1,
                    'total_view' => 9789,
                    'total_like' => 2485,
                    'total_dislike' => 25,
                    'playlist_type' => 0,
                    'is_admin_added' => 1,
                    'status' => 1,
                ],
                [
                    'content_type' => 2,
                    'channel_id' => 0,
                    'category_id' => 1,
                    'language_id' => 1,
                    'artist_id' => 1,
                    'hashtag_id' => 0,
                    'title' => 'Music - The Language of Feelings.',
                    'description' => 'Music - The Language of Feelings.',
                    'portrait_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_music_1.jpg'), $this->folder_content),
                    'landscape_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_music_1.jpg'), $this->folder_content),
                    'content_upload_type' => 'server_video',
                    'content' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_music.mp3'), $this->folder_content),
                    'content_size' => '0',
                    'content_duration' => 0,
                    'is_rent' => 0,
                    'rent_price' => 0,
                    'is_comment' => 1,
                    'is_download' => 1,
                    'is_like' => 1,
                    'total_view' => 9578,
                    'total_like' => 7200,
                    'total_dislike' => 5,
                    'playlist_type' => 0,
                    'is_admin_added' => 1,
                    'status' => 1,
                ],
                [
                    'content_type' => 2,
                    'channel_id' => 0,
                    'category_id' => 1,
                    'language_id' => 1,
                    'artist_id' => 1,
                    'hashtag_id' => 0,
                    'title' => 'Music - Part of Life',
                    'description' => 'Music - Part of Life',
                    'portrait_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_music_2.jpg'), $this->folder_content),
                    'landscape_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_music_2.jpg'), $this->folder_content),
                    'content_upload_type' => 'server_video',
                    'content' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_music.mp3'), $this->folder_content),
                    'content_size' => '0',
                    'content_duration' => 0,
                    'is_rent' => 0,
                    'rent_price' => 0,
                    'is_comment' => 1,
                    'is_download' => 1,
                    'is_like' => 1,
                    'total_view' => 2789,
                    'total_like' => 1156,
                    'total_dislike' => 25,
                    'playlist_type' => 0,
                    'is_admin_added' => 1,
                    'status' => 1,
                ],
                [
                    'content_type' => 3,
                    'channel_id' => $channel_name,
                    'category_id' => 0,
                    'language_id' => 0,
                    'artist_id' => 0,
                    'hashtag_id' => 0,
                    'title' => 'Types of Devloper',
                    'description' => 'Types of Devloper',
                    'portrait_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_reels_1.jpg'), $this->folder_content),
                    'landscape_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_reels_1.jpg'), $this->folder_content),
                    'content_upload_type' => 'server_video',
                    'content' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_reels.mp4'), $this->folder_content),
                    'content_size' => '0',
                    'content_duration' => 0,
                    'is_rent' => 0,
                    'rent_price' => 0,
                    'is_comment' => 1,
                    'is_download' => 1,
                    'is_like' => 1,
                    'total_view' => 6578,
                    'total_like' => 3500,
                    'total_dislike' => 66,
                    'playlist_type' => 0,
                    'is_admin_added' => 1,
                    'status' => 1,
                ],
                [
                    'content_type' => 3,
                    'channel_id' => $channel_name,
                    'category_id' => 0,
                    'language_id' => 0,
                    'artist_id' => 0,
                    'hashtag_id' => 0,
                    'title' => 'Planet Life',
                    'description' => 'Planet Life',
                    'portrait_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_reels_2.jpg'), $this->folder_content),
                    'landscape_img' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_reels_2.jpg'), $this->folder_content),
                    'content_upload_type' => 'server_video',
                    'content' => $this->common->dummyImageMove(asset('assets/dummy_imgs/con_reels.mp4'), $this->folder_content),
                    'content_size' => '0',
                    'content_duration' => 0,
                    'is_rent' => 0,
                    'rent_price' => 0,
                    'is_comment' => 1,
                    'is_download' => 1,
                    'is_like' => 1,
                    'total_view' => 9876,
                    'total_like' => 5432,
                    'total_dislike' => 45,
                    'playlist_type' => 0,
                    'is_admin_added' => 1,
                    'status' => 1,
                ],
            ];
            Content::insert($content);

            return response()->json(array('status' => 200, 'success' => 'Data Insert Successfully.'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function CleanDatabase()
    {
        try {

            Ads::query()->truncate();
            Ads_Package::query()->truncate();
            Ads_Transaction::query()->truncate();
            Ads_View_Click_Count::query()->truncate();
            Artist::query()->truncate();
            Block_Channel::query()->truncate();
            Category::query()->truncate();
            Comment::query()->truncate();
            Comment_Report::query()->truncate();
            Content::query()->truncate();
            Content_Report::query()->truncate();
            Episode::query()->truncate();
            Gift::query()->truncate();
            Gift_Transaction::query()->truncate();
            Hashtag::query()->truncate();
            History::query()->truncate();
            Interests_Category::query()->truncate();
            Interests_Hashtag::query()->truncate();
            Language::query()->truncate();
            Like::query()->truncate();
            Notification::query()->truncate();
            Package::query()->truncate();
            Package_Detail::query()->truncate();
            Post::query()->truncate();
            Post_Content::query()->truncate();
            Post_Comment::query()->truncate();
            Post_Like::query()->truncate();
            Post_View::query()->truncate();
            Post_Report::query()->truncate();
            Playlist_Content::query()->truncate();
            Radio_Content::query()->truncate();
            Read_Notification::query()->truncate();
            Rent_Section::query()->truncate();
            Rent_Transaction::query()->truncate();
            Report_Reason::query()->truncate();
            Section::query()->truncate();
            Subscriber::query()->truncate();
            Transaction::query()->truncate();
            User::query()->truncate();
            View::query()->truncate();
            Watch_later::query()->truncate();
            Withdrawal_Request::query()->truncate();

            return response()->json(array('status' => 200, 'success' => 'Data Clean Successfully.'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
