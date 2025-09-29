<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Comment_Report;
use App\Models\Common;
use App\Models\Content;
use App\Models\Content_Report;
use App\Models\Episode;
use App\Models\General_Setting;
use App\Models\Gift;
use App\Models\Gift_Transaction;
use App\Models\History;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Onboarding_Screen;
use App\Models\Package;
use App\Models\Package_Detail;
use App\Models\Page;
use App\Models\Payment_Option;
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
use App\Models\Social_Link;
use App\Models\Subscriber;
use App\Models\Transaction;
use App\Models\User;
use App\Models\View;
use App\Models\Watch_later;
use App\Models\Withdrawal_Request;
use CURLFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

// 1-Video, 2-Music, 3-Reels, 4-Podcasts, 5-Playlist
class HomeController extends Controller
{
    private $folder_app = "app";
    private $folder_user = "user";
    private $folder_package = "package";
    private $folder_content = "content";
    private $folder_notification = "notification";
    private $folder_post = "post";
    private $folder_gift = "gift";
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

    public function general_setting()
    {
        try {

            $list = General_Setting::get();
            foreach ($list as $key => $value) {

                if ($value['key'] == 'app_logo') {
                    $value['value'] = $this->common->getImage($this->folder_app, $value['value']);
                }
            }

            return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $list);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_payment_option()
    {
        try {

            $return['status'] = 200;
            $return['message'] = __('api_msg.get_record_successfully');
            $return['result'] = [];

            $Option_data = Payment_Option::get();
            foreach ($Option_data as $key => $value) {
                $return['result'][$value['name']] = $value;
            }

            return $return;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_pages()
    {
        try {

            $return['status'] = 200;
            $return['message'] = __('api_msg.get_record_successfully');
            $return['result'] = [];

            $data = Page::get();
            for ($i = 0; $i < count($data); $i++) {
                $return['result'][$i]['page_name'] = $data[$i]['page_name'];
                $return['result'][$i]['title'] = $data[$i]['title'];
                $return['result'][$i]['url'] = env('APP_URL') . '/public/pages/' . $data[$i]['page_name'];
                $return['result'][$i]['icon'] = $this->common->getImage($this->folder_app, $data[$i]['icon']);
            }
            return $return;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_social_links()
    {
        try {
            $data = Social_Link::get();
            if (sizeof($data) > 0) {

                $this->common->imageNameToUrl($data, 'image', $this->folder_app);
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_onboarding_screen()
    {
        try {
            $data = Onboarding_Screen::get();
            if (sizeof($data) > 0) {

                $this->common->imageNameToUrl($data, 'image', $this->folder_app);
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_package(Request $request)
    {
        try {
            $this->common->package_expiry();

            $user_id = isset($request->user_id) ? $request->user_id : 0;

            $data['status'] = 200;
            $data['message'] = __('api_msg.get_record_successfully');
            $data['result'] = [];

            $package_data = Package::select('id', 'name', 'price', 'image', 'time', 'type', 'android_product_package', 'ios_product_package', 'web_product_package', 'status', 'created_at', 'updated_at')->orderBy('price', 'asc')->latest()->get();
            $this->common->imageNameToUrl($package_data, 'image', $this->folder_package);

            foreach ($package_data as $key => $value) {

                $value['is_buy'] = $this->common->is_package_buy($user_id, $value['id']);

                $detail = Package_Detail::select('id', 'package_Id', 'package_key', 'package_value')->where('package_id', $value['id'])->get();
                $value['data'] = $detail;

                $data['result'][] = $value;
            }
            return $data;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_content_report(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'report_user_id' => 'required|numeric',
                    'content_type' => 'required|numeric',
                    'content_id' => 'required',
                    'message' => 'required',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'report_user_id.required' => __('api_msg.report_user_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                    'content_id.required' => __('api_msg.content_id_is_required'),
                    'message.required' => __('api_msg.message_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $report_user_id = $request['report_user_id'];
            $content_type = $request['content_type'];
            $content_id = $request['content_id'];
            $episode_id = isset($request->episode_id) ? $request->episode_id : 0;
            $message = $request['message'];

            $report = Content_Report::where('user_id', $user_id)->where('report_user_id', $report_user_id)->where('episode_id', $episode_id)->where('content_type', $content_type)->where('content_id', $content_id)->where('status', 1)->first();
            if (!isset($report['id']) && $report == null) {

                $insert['user_id'] = $user_id;
                $insert['report_user_id'] = $report_user_id;
                $insert['content_type'] = $content_type;
                $insert['content_id'] = $content_id;
                $insert['episode_id'] = $episode_id;
                $insert['message'] = $message;
                $insert['status'] = 1;
                Content_Report::insertGetId($insert);

                // Send Mail (Type = 1- Register, 2- Transaction, 3- Report, 4- User Penal Active)
                $user = User::where('id', $report_user_id)->first();
                $content = Content::where('id', $content_id)->first();
                if ($user != null && isset($user) && $user['email'] != "" && isset($user['email']) && $content != null && isset($content)) {
                    $this->common->Send_Mail(3, $user['email'], $content['title'], $message);
                }
            }
            return $this->common->API_Response(200, __('api_msg.report_add_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_remove_like_dislike(Request $request) // 0-Remove, 1-Like, 2-Dislike
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'content_type' => 'required|numeric',
                    'content_id' => 'required',
                    'status' => 'required|numeric',
                    'episode_id' => 'numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                    'content_id.required' => __('api_msg.content_id_is_required'),
                    'status.required' => __('api_msg.status_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $content_type = $request['content_type'];
            $content_id = $request['content_id'];
            $episode_id = isset($request->episode_id) ? $request->episode_id : 0;
            $status = $request['status'];

            if ($content_type == 1 || $content_type == 2 || $content_type == 3) {

                $like = Like::where('user_id', $user_id)->where('content_type', $content_type)->where('content_id', $content_id)->first();
                if ($like != null && isset($like)) {
                    $old_status = $like['status'];

                    $like['status'] = $status;
                    if ($like->save()) {

                        if ($old_status == 1) {

                            if ($status == 2) {
                                Content::where('id', $content_id)->decrement('total_like', 1);
                                Content::where('id', $content_id)->increment('total_dislike', 1);
                            } else if ($status == 0) {
                                Content::where('id', $content_id)->decrement('total_like', 1);
                            }
                        } else if ($old_status == 2) {

                            if ($status == 1) {
                                Content::where('id', $content_id)->increment('total_like', 1);
                                Content::where('id', $content_id)->decrement('total_dislike', 1);
                            } else if ($status == 0) {
                                Content::where('id', $content_id)->decrement('total_dislike', 1);
                            }
                        } else if ($old_status == 0) {

                            if ($status == 1) {
                                Content::where('id', $content_id)->increment('total_like', 1);
                            } else if ($status == 2) {
                                Content::where('id', $content_id)->increment('total_dislike', 1);
                            }
                        }

                        // Interests
                        if ($content_type == 1 || $content_type == 2) {
                            $this->common->add_interests_category($user_id, $content_id, $status);
                        }
                        if ($content_type == 3) {
                            $this->common->add_interests_hashtag($content_type, $user_id);
                        }

                        if ($status == 1) {
                            return $this->common->API_Response(200, __('api_msg.Like_Successfully'));
                        } else if ($status == 2) {
                            return $this->common->API_Response(200, __('api_msg.Dislike_Successfully'));
                        } else {
                            return $this->common->API_Response(200, __('api_msg.Remove_Successfully'));
                        }
                    } else {
                        return $this->common->API_Response(400, __('api_msg.status_not_update_successfully'));
                    }
                } else {

                    $insert['user_id'] = $user_id;
                    $insert['content_type'] = $content_type;
                    $insert['content_id'] = $content_id;
                    $insert['episode_id'] = 0;
                    $insert['status'] = $status;
                    Like::insertGetId($insert);

                    if ($status == 1) {
                        Content::where('id', $content_id)->increment('total_like', 1);

                        // Send Notification
                        if ($content_type == 1 || $content_type == 3) {

                            $user = User::where('id', $user_id)->first();
                            $content = Content::where('id', $content_id)->with('channel')->first();
                            if (isset($user) && $user != null && $content != null && isset($content) && $content['channel'] != null && $user_id != $content['channel']['id']) {

                                $title = $user['channel_name'] . ' Liked your Post.';
                                $this->common->save_notification(2, $title, $user_id, $content['channel']['id'], $content_id);
                            }
                        }
                    } else if ($status == 2) {
                        Content::where('id', $content_id)->increment('total_dislike', 1);
                    }

                    // Interests
                    if ($content_type == 1 || $content_type == 2) {
                        $this->common->add_interests_category($user_id, $content_id, $status);
                    }
                    if ($content_type == 3) {
                        $this->common->add_interests_hashtag($content_type, $user_id);
                    }

                    if ($status == 1) {
                        return $this->common->API_Response(200, __('api_msg.Like_Successfully'));
                    } else if ($status == 2) {
                        return $this->common->API_Response(200, __('api_msg.Dislike_Successfully'));
                    } else {
                        return $this->common->API_Response(200, __('api_msg.Remove_Successfully'));
                    }
                }
            } else if ($content_type == 4) {

                $like = Like::where('user_id', $user_id)->where('content_type', $content_type)->where('content_id', $content_id)->where('episode_id', $episode_id)->first();
                if ($like != null && isset($like)) {
                    $old_status = $like['status'];

                    $like['status'] = $status;
                    if ($like->save()) {

                        if ($old_status == 1) {

                            if ($status == 2) {
                                Content::where('id', $content_id)->decrement('total_like', 1);
                                Content::where('id', $content_id)->increment('total_dislike', 1);

                                Episode::where('id', $episode_id)->decrement('total_like', 1);
                                Episode::where('id', $episode_id)->increment('total_dislike', 1);
                            } else if ($status == 0) {
                                Content::where('id', $content_id)->decrement('total_like', 1);
                                Episode::where('id', $episode_id)->decrement('total_like', 1);
                            }
                        } else if ($old_status == 2) {

                            if ($status == 1) {
                                Content::where('id', $content_id)->increment('total_like', 1);
                                Content::where('id', $content_id)->decrement('total_dislike', 1);

                                Episode::where('id', $episode_id)->increment('total_like', 1);
                                Episode::where('id', $episode_id)->decrement('total_dislike', 1);
                            } else if ($status == 0) {
                                Content::where('id', $content_id)->decrement('total_dislike', 1);
                                Episode::where('id', $episode_id)->decrement('total_dislike', 1);
                            }
                        } else if ($old_status == 0) {

                            if ($status == 1) {
                                Content::where('id', $content_id)->increment('total_like', 1);
                                Episode::where('id', $episode_id)->increment('total_like', 1);
                            } else if ($status == 2) {
                                Content::where('id', $content_id)->increment('total_dislike', 1);
                                Episode::where('id', $episode_id)->increment('total_dislike', 1);
                            }
                        }

                        if ($status == 1) {
                            return $this->common->API_Response(200, __('api_msg.Like_Successfully'));
                        } else if ($status == 2) {
                            return $this->common->API_Response(200, __('api_msg.Dislike_Successfully'));
                        } else {
                            return $this->common->API_Response(200, __('api_msg.Remove_Successfully'));
                        }
                    } else {
                        return $this->common->API_Response(400, __('api_msg.status_not_update_successfully'));
                    }
                } else {

                    $insert['user_id'] = $user_id;
                    $insert['content_type'] = $content_type;
                    $insert['content_id'] = $content_id;
                    $insert['episode_id'] = $episode_id;
                    $insert['status'] = $status;
                    Like::insertGetId($insert);

                    if ($status == 1) {
                        Content::where('id', $content_id)->increment('total_like', 1);
                        Episode::where('id', $episode_id)->increment('total_like', 1);

                        // Send Notification
                        $user = User::where('id', $user_id)->first();
                        $content = Content::where('id', $content_id)->with('channel')->first();
                        if (isset($user) && $user != null && $content != null && isset($content) && $content['channel'] != null && $user_id != $content['channel']['id']) {

                            $title = $user['channel_name'] . ' Liked your Post.';
                            $this->common->save_notification(2, $title, $user_id, $content['channel']['id'], $content_id);
                        }
                    } else if ($status == 2) {
                        Content::where('id', $content_id)->increment('total_dislike', 1);
                        Episode::where('id', $episode_id)->increment('total_dislike', 1);
                    }

                    if ($status == 1) {
                        return $this->common->API_Response(200, __('api_msg.Like_Successfully'));
                    } else if ($status == 2) {
                        return $this->common->API_Response(200, __('api_msg.Dislike_Successfully'));
                    } else {
                        return $this->common->API_Response(200, __('api_msg.Remove_Successfully'));
                    }
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_view(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'content_type' => 'required|numeric',
                    'content_id' => 'required',
                    'episode_id' => 'numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                    'content_id.required' => __('api_msg.content_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $content_type = $request['content_type'];
            $content_id = $request['content_id'];
            $episode_id = isset($request->episode_id) ? $request->episode_id : 0;

            if ($content_type == 1 || $content_type == 2 || $content_type == 3 || $content_type == 5 || $content_type == 6) {

                $view = View::where('user_id', $user_id)->where('content_type', $content_type)->where('content_id', $content_id)->first();
                if ($view == null && !isset($view)) {

                    $insert['user_id'] = $user_id;
                    $insert['content_type'] = $content_type;
                    $insert['content_id'] = $content_id;
                    $insert['episode_id'] = 0;
                    $insert['status'] = 1;
                    View::insertGetId($insert);

                    Content::where('id', $content_id)->increment('total_view', 1);
                }
            } else if ($content_type == 4) {

                $view = View::where('user_id', $user_id)->where('content_type', $content_type)->where('content_id', $content_id)->where('episode_id', $episode_id)->first();
                if ($view == null && !isset($view)) {

                    $insert['user_id'] = $user_id;
                    $insert['content_type'] = $content_type;
                    $insert['content_id'] = $content_id;
                    $insert['episode_id'] = $episode_id;
                    $insert['status'] = 1;
                    View::insertGetId($insert);

                    Content::where('id', $content_id)->increment('total_view', 1);
                    Episode::where('id', $episode_id)->increment('total_view', 1);
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }

            return $this->common->API_Response(200, __('api_msg.content_view_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    // Backup
    // public function add_remove_watch_later(Request $request) // Old
    // {
    //     try {
    //         $validation = Validator::make(
    //             $request->all(),
    //             [
    //                 'user_id' => 'required|numeric',
    //                 'content_type' => 'required|numeric',
    //                 'content_id' => 'required',
    //                 'episode_id' => 'numeric',
    //             ],
    //             [
    //                 'user_id.required' => __('api_msg.user_id_is_required'),
    //                 'content_type.required' => __('api_msg.content_type_is_required'),
    //                 'content_id.required' => __('api_msg.content_id_is_required'),
    //             ]
    //         );
    //         if ($validation->fails()) {
    //             $data['status'] = 400;
    //             $data['message'] = $validation->errors()->first();
    //             return $data;
    //         }

    //         $user_id = $request['user_id'];
    //         $content_type = $request['content_type'];
    //         $content_id = $request['content_id'];
    //         $episode_id = isset($request->episode_id) ? $request->episode_id : 0;

    //         $watch_later = Watch_later::where('user_id', $user_id)->where('content_type', $content_type)->where('content_id', $content_id)->where('episode_id', $episode_id)->first();
    //         if (!isset($watch_later['id']) && $watch_later == null) {

    //             $insert['user_id'] = $user_id;
    //             $insert['content_type'] = $content_type;
    //             $insert['content_id'] = $content_id;
    //             $insert['episode_id'] = $episode_id;
    //             $insert['status'] = 1;
    //             Watch_later::insertGetId($insert);
    //             return $this->common->API_Response(200, __('api_msg.watch_later_add_successfully'));
    //         } else {

    //             $watch_later->delete();
    //             return $this->common->API_Response(200, __('api_msg.remove_watch_later_successfully'));
    //         }
    //     } catch (Exception $e) {
    //         return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
    //     }
    // }
    public function add_remove_watch_later(Request $request) // Type = 0-Remove, 1-Add
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'content_type' => 'required|numeric',
                    'content_id' => 'required',
                    'episode_id' => 'numeric',
                    'type' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
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

            $user_id = $request['user_id'];
            $content_type = $request['content_type'];
            $content_id = $request['content_id'];
            $type = $request['type'];
            $episode_id = isset($request->episode_id) ? $request->episode_id : 0;

            if ($type == 1) {

                $watch_later = Watch_later::where('user_id', $user_id)->where('content_type', $content_type)->where('content_id', $content_id)->where('episode_id', $episode_id)->first();
                if (!isset($watch_later['id']) && $watch_later == null) {

                    $insert['user_id'] = $user_id;
                    $insert['content_type'] = $content_type;
                    $insert['content_id'] = $content_id;
                    $insert['episode_id'] = $episode_id;
                    $insert['status'] = 1;
                    Watch_later::insertGetId($insert);
                }
                return $this->common->API_Response(200, __('api_msg.watch_later_add_successfully'));
            } else if ($type == 0) {

                $watch_later = Watch_later::where('user_id', $user_id)->where('content_type', $content_type)->where('content_id', $content_id)->where('episode_id', $episode_id)->first();
                if (isset($watch_later['id']) && $watch_later != null) {
                    $watch_later->delete();
                }
                return $this->common->API_Response(200, __('api_msg.remove_watch_later_successfully'));
            } else {
                return $this->common->API_Response(200, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'content_type' => 'required|numeric',
                    'content_id' => 'required',
                    'episode_id' => 'numeric',
                    'comment_id' => 'numeric',
                    'comment' => 'required',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                    'content_id.required' => __('api_msg.content_id_is_required'),
                    'comment.required' => __('api_msg.comment_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $comment_id = isset($request->comment_id) ? $request->comment_id : 0;
            $user_id = $request['user_id'];
            $content_type = $request['content_type'];
            $content_id = $request['content_id'];
            $episode_id = isset($request->episode_id) ? $request->episode_id : 0;
            $comment = $request['comment'];

            $insert = new Comment();
            $insert['comment_id'] = $comment_id;
            $insert['user_id'] = $user_id;
            $insert['content_type'] = $content_type;
            $insert['content_id'] = $content_id;
            $insert['episode_id'] = $episode_id;
            $insert['comment'] = $comment;
            $insert->save();

            // Interests
            if ($content_type == 1 || $content_type == 2) {
                $this->common->add_interests_category($user_id, $content_id, 1);
            }
            if ($content_type == 3) {
                $this->common->add_interests_hashtag($content_type, $user_id);
            }

            // Send Notification
            if ($content_type == 1 || $content_type == 3 || $content_type == 4) {

                $user = User::where('id', $user_id)->first();
                $content = Content::where('id', $content_id)->with('channel')->first();
                if (isset($user) && $user != null && $content != null && isset($content) && $content['channel'] != null && $user_id != $content['channel']['id']) {

                    $title = $user['channel_name'] . ' Commented on your Post.';
                    $this->common->save_notification(3, $title, $user_id, $content['channel']['id'], $content_id);
                }
            }

            return $this->common->API_Response(200, __('api_msg.comment_add_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function edit_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'comment_id' => 'required|numeric',
                    'comment' => 'required',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'comment.required' => __('api_msg.comment_is_required'),
                    'comment_id.required' => __('api_msg.comment_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $comment_id = $request['comment_id'];
            $comment = $request['comment'];

            $update = Comment::where('id', $comment_id)->first();
            if (isset($update) && $update != null) {

                $update['user_id'] = $user_id;
                $update['comment_id'] = $comment_id;
                $update['comment'] = $comment;
                $update->save();
                return $this->common->API_Response(200, __('api_msg.comment_edit_successfully'));
            }
            return $this->common->API_Response(200, __('api_msg.comment_not_found'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function delete_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'comment_id' => 'required|numeric',
                ],
                [
                    'comment_id.required' => __('api_msg.comment_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $comment_id = $request['comment_id'];
            Comment::where('id', $comment_id)->delete();
            return $this->common->API_Response(200, __('api_msg.comment_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'content_type' => 'required|numeric',
                    'content_id' => 'required|numeric',
                    'episode_id' => 'numeric',
                ],
                [
                    'content_type.required' => __('api_msg.content_type_is_required'),
                    'content_id.required' => __('api_msg.content_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $content_type = $request['content_type'];
            $content_id = $request['content_id'];
            $episode_id = isset($request->episode_id) ? $request->episode_id : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            if ($content_type == 1 || $content_type == 2 || $content_type == 3) {
                $data = Comment::where('comment_id', 0)->where('content_type', $content_type)->where('content_id', $content_id)->where('status', 1)->orderBy('id', 'desc')->with('user');
            } else if ($content_type == 4) {
                $data = Comment::where('comment_id', 0)->where('content_type', $content_type)->where('content_id', $content_id)->where('episode_id', $episode_id)->where('status', 1)->orderBy('id', 'desc')->with('user');
            }

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

                    $data[$i]['channel_name'] = "";
                    $data[$i]['full_name'] = "";
                    $data[$i]['email'] = "";
                    $data[$i]['image'] = "";
                    if ($data[$i]['user'] != null) {
                        $data[$i]['channel_name'] = $data[$i]['user']['channel_name'];
                        $data[$i]['full_name'] = $data[$i]['user']['full_name'];
                        $data[$i]['email'] = $data[$i]['user']['email'];
                        $data[$i]['image'] = $this->common->getImage($this->folder_user, $data[$i]['user']['image']);
                    }
                    unset($data[$i]['user']);

                    $data[$i]['is_reply'] = 0;
                    $data[$i]['total_reply'] = 0;
                    $reply = Comment::where('comment_id', $data[$i]['id'])->count();
                    if ($reply != 0) {
                        $data[$i]['is_reply'] = 1;
                        $data[$i]['total_reply'] = $reply;
                    }
                }
                return $this->common->API_Response(200, __('api_msg.comment_add_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_reply_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'comment_id' => 'required|numeric',
                ],
                [
                    'comment_id.required' => __('api_msg.comment_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $comment_id = $request['comment_id'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Comment::where('comment_id', $comment_id)->where('status', 1)->orderBy('id', 'desc')->with('user');

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

                    $data[$i]['channel_name'] = "";
                    $data[$i]['full_name'] = "";
                    $data[$i]['email'] = "";
                    $data[$i]['image'] = "";
                    if ($data[$i]['user'] != null) {
                        $data[$i]['channel_name'] = $data[$i]['user']['channel_name'];
                        $data[$i]['full_name'] = $data[$i]['user']['full_name'];
                        $data[$i]['email'] = $data[$i]['user']['email'];
                        $data[$i]['image'] = $this->common->getImage($this->folder_user, $data[$i]['user']['image']);
                    }
                    unset($data[$i]['user']);

                    $data[$i]['is_reply'] = 0;
                    $data[$i]['total_reply'] = 0;
                    $reply = Comment::where('comment_id', $data[$i]['id'])->count();
                    if ($reply != 0) {
                        $data[$i]['is_reply'] = 1;
                        $data[$i]['total_reply'] = $reply;
                    }
                }
                return $this->common->API_Response(200, __('api_msg.comment_add_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_comment_report(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'report_user_id' => 'required|numeric',
                    'comment_id' => 'required|numeric',
                    'message' => 'required',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'report_user_id.required' => __('api_msg.report_user_id_is_required'),
                    'comment_id.required' => __('api_msg.comment_id_is_required'),
                    'message.required' => __('api_msg.message_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $report_user_id = $request['report_user_id'];
            $comment_id = $request['comment_id'];
            $message = $request['message'];

            $report = Comment_Report::where('user_id', $user_id)->where('report_user_id', $report_user_id)->where('comment_id', $comment_id)->where('status', 1)->first();
            if (!isset($report['id']) && $report == null) {

                $insert['user_id'] = $user_id;
                $insert['report_user_id'] = $report_user_id;
                $insert['comment_id'] = $comment_id;
                $insert['message'] = $message;
                $insert['status'] = 1;
                Comment_Report::insertGetId($insert);
            }
            return $this->common->API_Response(200, __('api_msg.report_add_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_content_detail(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'numeric',
                    'content_type' => 'required|numeric',
                    'content_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                    'content_id.required' => __('api_msg.content_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;
            $content_type = $request['content_type'];
            $content_id = $request['content_id'];

            $content = Content::where('content_type', $content_type)->where('id', $content_id)->where('is_rent', 0)->where('status', 1)->with('channel')->first();
            if (isset($content) && $content != null) {

                $this->common->imageNameToUrl(array($content), 'portrait_img', $this->folder_content);
                $this->common->imageNameToUrl(array($content), 'landscape_img', $this->folder_content);
                if ($content['content_upload_type'] == 'server_video') {
                    $this->common->videoNameToUrl(array($content), 'content', $this->folder_content);
                }

                $content['user_id'] = $this->common->getUserId($content['channel_id']);
                $content['channel_name'] = $this->common->getChannelName($content['channel_id']);
                $content['channel_image'] = $this->common->getChannelImage($content['channel_id']);
                $content['category_name'] = $this->common->getCategoryName($content['category_id']);
                $content['artist_name'] = $this->common->getArtistName($content['artist_id']);
                $content['language_name'] = $this->common->getLanguageName($content['language_id']);
                $content['is_subscribe'] = 0;
                if ($content['channel'] != null) {
                    $content['is_subscribe'] = $this->common->is_subscribe(1, $user_id, $content['channel']['id']); // Type 1- Channel, 2- Artist
                }
                unset($content['channel']);
                $content['total_comment'] = $this->common->getTotalComment($content['id']);
                $content['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $content['content_type'], $content['id'], 0);
                $content['total_subscriber'] = $this->common->total_subscriber($content['user_id']);
                $content['is_buy'] = $this->common->is_any_package_buy($user_id);
                $content['stop_time'] = $this->common->getContentStopTime($user_id, $content['content_type'], $content['id'], 0);
                $content['is_user_download'] = $this->common->is_user_download_content($user_id);

                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), array($content));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_report_reason(Request $request) // Type : 1- Comment, 2- Content
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'type' => 'required|numeric',
                ],
                [
                    'type.required' => __('api_msg.type_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $type = $request['type'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Report_Reason::where('type', $type)->orderBy('id', 'desc');

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
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_like_content(Request $request)
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

            $data = Like::where('user_id', $user_id)->where('content_type', $content_type)->where('status', 1)->with('content', 'episode')->orderBy('id', 'desc')->latest();

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get()->toArray();

            if (count($data) > 0) {

                $content_data = [];
                for ($i = 0; $i < count($data); $i++) {

                    if ($data[$i]['content'] != null && isset($data[$i]['content'])) {

                        $data[$i]['content']['portrait_img'] = $this->common->getImage($this->folder_content, $data[$i]['content']['portrait_img']);
                        $data[$i]['content']['landscape_img'] = $this->common->getImage($this->folder_content, $data[$i]['content']['landscape_img']);
                        if ($data[$i]['content']['content_upload_type'] == 'server_video') {
                            $data[$i]['content']['content'] = $this->common->getVideo($this->folder_content, $data[$i]['content']['content']);
                        }

                        $data[$i]['content']['user_id'] = $this->common->getUserId($data[$i]['content']['channel_id']);
                        $data[$i]['content']['channel_name'] = $this->common->getChannelName($data[$i]['content']['channel_id']);
                        $data[$i]['content']['channel_image'] = $this->common->getChannelImage($data[$i]['content']['channel_id']);
                        $data[$i]['content']['category_name'] = $this->common->getCategoryName($data[$i]['content']['category_id']);
                        $data[$i]['content']['artist_name'] = $this->common->getArtistName($data[$i]['content']['artist_id']);
                        $data[$i]['content']['language_name'] = $this->common->getLanguageName($data[$i]['content']['language_id']);
                        $data[$i]['content']['is_subscribe'] = $this->common->is_subscribe(1, $user_id, $data[$i]['content']['user_id']); // Type 1- Channel, 2- Artist
                        $data[$i]['content']['total_comment'] = $this->common->getTotalComment($data[$i]['content']['id']);
                        $data[$i]['content']['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$i]['content']['content_type'], $data[$i]['content']['id'], 0);
                        $data[$i]['content']['total_subscriber'] = $this->common->total_subscriber($data[$i]['content']['user_id']);
                        $data[$i]['content']['is_buy'] = $this->common->is_any_package_buy($user_id);
                        $data[$i]['content']['stop_time'] = $this->common->getContentStopTime($user_id, $data[$i]['content']['content_type'], $data[$i]['content']['id'], 0);

                        $data[$i]['content']['episode'] = [];
                        if ($data[$i]['episode'] != null && isset($data[$i]['episode'])) {

                            $data[$i]['episode']['portrait_img'] = $this->common->getImage($this->folder_content, $data[$i]['episode']['portrait_img']);
                            $data[$i]['episode']['landscape_img'] = $this->common->getImage($this->folder_content, $data[$i]['episode']['landscape_img']);
                            if ($data[$i]['episode']['episode_upload_type'] == 'server_video') {
                                $data[$i]['episode']['episode_audio'] = $this->common->getVideo($this->folder_content, $data[$i]['episode']['episode_audio']);
                            }
                            $data[$i]['episode']['podcast_name'] = $data[$i]['content']['title'];
                            $data[$i]['episode']['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$i]['content']['content_type'], $data[$i]['content']['id'], $data[$i]['episode']['id']);
                            $data[$i]['episode']['is_buy'] = $this->common->is_any_package_buy($user_id);

                            $data[$i]['content']['episode'][] = $data[$i]['episode'];
                        }

                        $content_data[] = $data[$i]['content'];
                    }
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $content_data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_watch_later_content(Request $request)
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

            $data = Watch_later::where('user_id', $user_id)->where('content_type', $content_type)->where('status', 1)->with('content', 'episode')->orderBy('id', 'desc')->latest();

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get()->toArray();

            if (count($data) > 0) {

                $content_data = [];
                for ($i = 0; $i < count($data); $i++) {

                    if ($data[$i]['content'] != null && isset($data[$i]['content'])) {

                        $data[$i]['content']['portrait_img'] = $this->common->getImage($this->folder_content, $data[$i]['content']['portrait_img']);
                        $data[$i]['content']['landscape_img'] = $this->common->getImage($this->folder_content, $data[$i]['content']['landscape_img']);
                        if ($data[$i]['content']['content_upload_type'] == 'server_video') {
                            $data[$i]['content']['content'] = $this->common->getVideo($this->folder_content, $data[$i]['content']['content']);
                        }

                        $data[$i]['content']['user_id'] = $this->common->getUserId($data[$i]['content']['channel_id']);
                        $data[$i]['content']['channel_name'] = $this->common->getChannelName($data[$i]['content']['channel_id']);
                        $data[$i]['content']['channel_image'] = $this->common->getChannelImage($data[$i]['content']['channel_id']);
                        $data[$i]['content']['category_name'] = $this->common->getCategoryName($data[$i]['content']['category_id']);
                        $data[$i]['content']['artist_name'] = $this->common->getArtistName($data[$i]['content']['artist_id']);
                        $data[$i]['content']['language_name'] = $this->common->getLanguageName($data[$i]['content']['language_id']);
                        $data[$i]['content']['is_subscribe'] = $this->common->is_subscribe(1, $user_id, $data[$i]['content']['user_id']); // Type 1- Channel, 2- Artist
                        $data[$i]['content']['total_comment'] = $this->common->getTotalComment($data[$i]['content']['id']);
                        $data[$i]['content']['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$i]['content']['content_type'], $data[$i]['content']['id'], 0);
                        $data[$i]['content']['total_subscriber'] = $this->common->total_subscriber($data[$i]['content']['user_id']);
                        $data[$i]['content']['is_buy'] = $this->common->is_any_package_buy($user_id);
                        $data[$i]['content']['stop_time'] = $this->common->getContentStopTime($user_id, $data[$i]['content']['content_type'], $data[$i]['content']['id'], 0);

                        $data[$i]['content']['episode'] = [];
                        if ($data[$i]['episode'] != null && isset($data[$i]['episode'])) {

                            $data[$i]['episode']['portrait_img'] = $this->common->getImage($this->folder_content, $data[$i]['episode']['portrait_img']);
                            $data[$i]['episode']['landscape_img'] = $this->common->getImage($this->folder_content, $data[$i]['episode']['landscape_img']);
                            if ($data[$i]['episode']['episode_upload_type'] == 'server_video') {
                                $data[$i]['episode']['episode_audio'] = $this->common->getVideo($this->folder_content, $data[$i]['episode']['episode_audio']);
                            }
                            $data[$i]['episode']['podcast_name'] = $data[$i]['content']['title'];
                            $data[$i]['episode']['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$i]['content']['content_type'], $data[$i]['content']['id'], $data[$i]['episode']['id']);
                            $data[$i]['episode']['stop_time'] = $this->common->getContentStopTime($user_id, $data[$i]['content']['content_type'], $data[$i]['content']['id'], $data[$i]['episode']['id']);

                            $data[$i]['content']['episode'][] = $data[$i]['episode'];
                        }

                        $content_data[] = $data[$i]['content'];
                    }
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $content_data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_content_by_channel(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'channel_id' => 'required',
                    'content_type' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'channel_id.required' => __('api_msg.channel_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $channel_id = $request['channel_id'];
            $content_type = $request['content_type'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Content::where('content_type', $content_type)->where('channel_id', $channel_id)->where('status', 1)->where('is_rent', 0)->orderby('id', 'desc');

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

                    // Playlist Image array
                    $image_array = [];
                    if ($data[$i]['content_type'] == 5) {

                        $playlist_content = Playlist_Content::where('playlist_id', $data[$i]['id'])->where('content_type', 2)->orderBy('sortable', 'asc')->with('Content')->latest()->get();
                        if (count($playlist_content) > 0) {
                            $img_count = 0;
                            for ($j = 0; $j < count($playlist_content); $j++) {

                                if ($playlist_content[$j]['Content'] != null & isset($playlist_content[$j]['Content'])) {

                                    $this->common->imageNameToUrl(array($playlist_content[$j]['Content']), 'portrait_img', $this->folder_content);
                                    $image_array[] = $playlist_content[$j]['Content']['portrait_img'];

                                    $img_count = $img_count + 1;
                                    if ($img_count == 4) {
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    $data[$i]['playlist_image'] = $image_array;
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_content_to_history(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'content_type' => 'required|numeric',
                    'content_id' => 'required|numeric',
                    'episode_id' => 'numeric',
                    'stop_time' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                    'content_id.required' => __('api_msg.content_id_is_required'),
                    'stop_time.required' => __('api_msg.stop_time_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $content_type = $request['content_type'];
            $content_id = $request['content_id'];
            $episode_id = isset($request['episode_id']) ? $request['episode_id'] : 0;
            $stop_time = $request['stop_time'];

            $content = History::where('user_id', $user_id)->where('content_type', $content_type)->where('content_id', $content_id)->where('episode_id', $episode_id)->first();
            if ($content != null && isset($content)) {

                if ($content_type == 1 || $content_type == 2 || $content_type == 4) {

                    $content['stop_time'] = $stop_time;
                    $content->save();
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            } else {

                if ($content_type == 1 || $content_type == 2 || $content_type == 4) {

                    $insert = new History();
                    $insert['user_id'] = $user_id;
                    $insert['content_type'] = $content_type;
                    $insert['content_id'] = $content_id;
                    $insert['episode_id'] = $episode_id;
                    $insert['stop_time'] = $stop_time;
                    $insert['status'] = 1;
                    $insert->save();
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            }
            return $this->common->API_Response(200, __('api_msg.content_add_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function remove_content_to_history(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'content_type' => 'required|numeric',
                    'content_id' => 'required|numeric',
                    'episode_id' => 'numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                    'content_id.required' => __('api_msg.content_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $content_type = $request['content_type'];
            $content_id = $request['content_id'];
            $episode_id = isset($request['episode_id']) ? $request['episode_id'] : 0;

            History::where('user_id', $user_id)->where('content_type', $content_type)->where('content_id', $content_id)->where('episode_id', $episode_id)->delete();
            return $this->common->API_Response(200, __('api_msg.content_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_content_to_history(Request $request)
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

            $data = History::where('user_id', $user_id)->where('content_type', $content_type)->where('status', 1)->with('content', 'episode')->orderBy('id', 'desc')->latest();

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get()->toArray();

            if (count($data) > 0) {

                $content_data = [];
                for ($i = 0; $i < count($data); $i++) {

                    if ($data[$i]['content'] != null && isset($data[$i]['content'])) {

                        $data[$i]['content']['portrait_img'] = $this->common->getImage($this->folder_content, $data[$i]['content']['portrait_img']);
                        $data[$i]['content']['landscape_img'] = $this->common->getImage($this->folder_content, $data[$i]['content']['landscape_img']);
                        if ($data[$i]['content']['content_upload_type'] == 'server_video') {
                            $data[$i]['content']['content'] = $this->common->getVideo($this->folder_content, $data[$i]['content']['content']);
                        }

                        $data[$i]['content']['user_id'] = $this->common->getUserId($data[$i]['content']['channel_id']);
                        $data[$i]['content']['channel_name'] = $this->common->getChannelName($data[$i]['content']['channel_id']);
                        $data[$i]['content']['channel_image'] = $this->common->getChannelImage($data[$i]['content']['channel_id']);
                        $data[$i]['content']['category_name'] = $this->common->getCategoryName($data[$i]['content']['category_id']);
                        $data[$i]['content']['artist_name'] = $this->common->getArtistName($data[$i]['content']['artist_id']);
                        $data[$i]['content']['language_name'] = $this->common->getLanguageName($data[$i]['content']['language_id']);
                        $data[$i]['content']['is_subscribe'] = $this->common->is_subscribe(1, $user_id, $data[$i]['content']['user_id']); // Type 1- Channel, 2- Artist
                        $data[$i]['content']['total_comment'] = $this->common->getTotalComment($data[$i]['content']['id']);
                        $data[$i]['content']['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$i]['content']['content_type'], $data[$i]['content']['id'], 0);
                        $data[$i]['content']['total_subscriber'] = $this->common->total_subscriber($data[$i]['content']['user_id']);
                        $data[$i]['content']['stop_time'] = $data[$i]['stop_time'];
                        $data[$i]['content']['is_buy'] = $this->common->is_any_package_buy($user_id);

                        $data[$i]['content']['episode'] = [];
                        if ($data[$i]['episode'] != null && isset($data[$i]['episode'])) {

                            $data[$i]['episode']['portrait_img'] = $this->common->getImage($this->folder_content, $data[$i]['episode']['portrait_img']);
                            $data[$i]['episode']['landscape_img'] = $this->common->getImage($this->folder_content, $data[$i]['episode']['landscape_img']);
                            if ($data[$i]['episode']['episode_upload_type'] == 'server_video') {
                                $data[$i]['episode']['episode_audio'] = $this->common->getVideo($this->folder_content, $data[$i]['episode']['episode_audio']);
                            }
                            $data[$i]['episode']['podcast_name'] = $data[$i]['content']['title'];
                            $data[$i]['episode']['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$i]['content']['content_type'], $data[$i]['content']['id'], $data[$i]['episode']['id']);
                            $data[$i]['episode']['stop_time'] = $data[$i]['stop_time'];

                            $data[$i]['content']['episode'][] = $data[$i]['episode'];
                        }

                        $content_data[] = $data[$i]['content'];
                    }
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $content_data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_episode_by_podcasts(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'numeric',
                    'podcasts_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'podcasts_id.required' => __('api_msg.podcasts_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;
            $podcasts_id = $request['podcasts_id'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Episode::where('podcasts_id', $podcasts_id)->with('Content')->orderBy('sortable', 'asc')->latest();

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get();

            foreach ($data as $key => $value) {

                $this->common->imageNameToUrl(array($value), 'portrait_img', $this->folder_content);
                $this->common->imageNameToUrl(array($value), 'landscape_img', $this->folder_content);
                $value['episode_audio'] = $value['episode_audio'];
                if ($value['episode_upload_type'] == 'server_video') {
                    $value['episode_audio'] = $this->common->getVideo($this->folder_content, $value['episode_audio']);
                }
                $value['podcasts_name'] = "";
                $value['is_user_like_dislike'] = 0;
                $value['user_id'] = 0;
                $value['stop_time'] = 0;
                if ($value['Content'] != null) {
                    $value['podcasts_name'] = $value['Content']['title'];
                    $value['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $value['Content']['content_type'], $value['Content']['id'], $value['id']);
                    $value['user_id'] = $this->common->getUserId($value['Content']['channel_id']);
                    $value['stop_time'] = $this->common->getContentStopTime($user_id, $value['Content']['content_type'], $value['Content']['id'], 0);
                }
                unset($value['Content']);
                $value['is_buy'] = $this->common->is_any_package_buy($user_id);
            }

            return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_radio_content(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'numeric',
                    'radio_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'radio_id.required' => __('api_msg.radio_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = isset($request['user_id']) ? $request['user_id'] : 0;
            $radio_id = $request['radio_id'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $radio_content = Radio_Content::where('radio_id', $radio_id)->orderBy('sortable', 'asc')->get();

            $content_id = [];
            for ($i = 0; $i < count($radio_content); $i++) {
                $content_id[] = $radio_content[$i]['content_id'];
            }

            if (count($content_id) > 0) {

                $ids_ordered = implode(',', $content_id);
                $data = Content::whereIn('id', $content_id)->orderByRaw("FIELD(id, $ids_ordered)");

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
                        $data[$i]['category_name'] = $this->common->getCategoryName($data[$i]['category_id']);
                        $data[$i]['artist_name'] = $this->common->getArtistName($data[$i]['artist_id']);
                        $data[$i]['language_name'] = $this->common->getLanguageName($data[$i]['language_id']);
                        $data[$i]['total_comment'] = $this->common->getTotalComment($data[$i]['id']);
                        $data[$i]['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$i]['content_type'], $data[$i]['id'], 0);
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
    public function add_transaction(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'package_id' => 'required|numeric',
                    'price' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'package_id.required' => __('api_msg.package_id_is_required'),
                    'price.required' => __('api_msg.price_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request->user_id;
            $package_id = $request->package_id;
            $price = $request->price;
            $description = isset($request->description) ? $request->description : "";
            $transaction_id = isset($request->transaction_id) ? $request->transaction_id : "";

            // Expriy
            Transaction::where('user_id', $user_id)->update(['status' => 0]);

            $Pdata = Package::where('id', $package_id)->where('status', '1')->first();
            if (!empty($Pdata)) {
                $Edate = date("Y-m-d", strtotime("$Pdata->time $Pdata->type"));
            } else {
                return $this->common->API_Response(400, __('api_msg.please_enter_right_package_id'));
            }

            $insert = new Transaction();
            $insert->user_id = $user_id;
            $insert->package_id = $package_id;
            $insert->transaction_id = $transaction_id;
            $insert->price = $price;
            $insert->description = $description;
            $insert->expiry_date = $Edate;
            $insert->status = 1;

            if ($insert->save()) {

                // Send Mail (Type = 1- Register, 2- Transaction, 3- Report, 4- User Penal Active)
                $user_email = User::where('id', $user_id)->first();
                if ($user_email != null && isset($user_email)) {
                    $this->common->Send_Mail(2, $user_email);
                }

                return $this->common->API_Response(200, __('api_msg.transaction_successfully'), []);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function search_content(Request $request) // Type = 1- Video, 2- Music
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ],
                [
                    'name.required' => __('api_msg.name_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $name = $request->name;
            $user_id = isset($request->user_id) ? $request->user_id : 0;
            $type = isset($request->type) ? $request->type : 0;

            $data['status'] = 200;
            $data['message'] = __('api_msg.get_record_successfully');
            $data['result'] = array();
            $data['video'] = array();
            $data['channel'] = array();
            $data['music'] = array();
            $data['podcast'] = array();
            $data['radio'] = array();

            if ($type == 1) {

                $video = Content::where('content_type', 1)->where('is_rent', 0)->where('title', 'LIKE', "%{$name}%")->where('status', 1)->where('is_rent', 0)->orderBy('total_view', 'desc')->orderBy('total_like', 'desc')->latest()->take(10)->get();
                for ($j = 0; $j < count($video); $j++) {

                    $video[$j]['portrait_img'] = $this->common->getImage($this->folder_content, $video[$j]['portrait_img']);
                    $video[$j]['landscape_img'] = $this->common->getImage($this->folder_content, $video[$j]['landscape_img']);
                    if ($video[$j]['content_upload_type'] == 'server_video') {
                        $video[$j]['content'] = $this->common->getVideo($this->folder_content, $video[$j]['content']);
                    }

                    $video[$j]['user_id'] = $this->common->getUserId($video[$j]['channel_id']);
                    $video[$j]['channel_name'] = $this->common->getChannelName($video[$j]['channel_id']);
                    $video[$j]['channel_image'] = $this->common->getChannelImage($video[$j]['channel_id']);
                    $video[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);

                    $data['video'][] = $video[$j];
                }

                $channel = User::where('channel_name', 'LIKE', "%{$name}%")->where('status', 1)->orderBy('id', 'desc')->latest()->take(10)->get();
                for ($i = 0; $i < count($channel); $i++) {

                    $channel[$i]['image'] = $this->common->getImage($this->folder_user, $channel[$i]['image']);
                    $channel[$i]['cover_img'] = $this->common->getImage($this->folder_user, $channel[$i]['cover_img']);
                    $channel[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);

                    $data['channel'][] = $channel[$i];
                }
            } else if ($type == 2) {

                $music = Content::where('content_type', 2)->where('title', 'LIKE', "%{$name}%")->where('status', 1)->where('is_rent', 0)->orderBy('total_view', 'desc')->orderBy('total_like', 'desc')->latest()->take(10)->get();
                for ($j = 0; $j < count($music); $j++) {

                    $music[$j]['portrait_img'] = $this->common->getImage($this->folder_content, $music[$j]['portrait_img']);
                    $music[$j]['landscape_img'] = $this->common->getImage($this->folder_content, $music[$j]['landscape_img']);
                    if ($music[$j]['content_upload_type'] == 'server_video') {
                        $music[$j]['content'] = $this->common->getVideo($this->folder_content, $music[$j]['content']);
                    }
                    $music[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);

                    $data['music'][] = $music[$j];
                }

                $podcast = Content::where('content_type', 4)->where('title', 'LIKE', "%{$name}%")->where('status', 1)->where('is_rent', 0)->orderBy('total_view', 'desc')->orderBy('total_like', 'desc')->latest()->take(10)->get();
                for ($j = 0; $j < count($podcast); $j++) {

                    $podcast[$j]['portrait_img'] = $this->common->getImage($this->folder_content, $podcast[$j]['portrait_img']);
                    $podcast[$j]['landscape_img'] = $this->common->getImage($this->folder_content, $podcast[$j]['landscape_img']);

                    $podcast[$j]['user_id'] = $this->common->getUserId($podcast[$j]['channel_id']);
                    $podcast[$j]['channel_name'] = $this->common->getChannelName($podcast[$j]['channel_id']);
                    $podcast[$j]['channel_image'] = $this->common->getChannelImage($podcast[$j]['channel_id']);
                    $podcast[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);

                    $data['podcast'][] = $podcast[$j];
                }

                $radio = Content::where('content_type', 6)->where('title', 'LIKE', "%{$name}%")->where('status', 1)->where('is_rent', 0)->orderBy('total_view', 'desc')->orderBy('total_like', 'desc')->latest()->take(10)->get();
                for ($j = 0; $j < count($radio); $j++) {

                    $radio[$j]['portrait_img'] = $this->common->getImage($this->folder_content, $radio[$j]['portrait_img']);
                    $radio[$j]['landscape_img'] = $this->common->getImage($this->folder_content, $radio[$j]['landscape_img']);
                    $radio[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);

                    $data['radio'][] = $radio[$j];
                }
            } else {

                $video = Content::where('content_type', 1)->where('is_rent', 0)->where('title', 'LIKE', "%{$name}%")->where('status', 1)->orderBy('total_view', 'desc')->orderBy('total_like', 'desc')->latest()->take(10)->get();
                for ($j = 0; $j < count($video); $j++) {

                    $video[$j]['portrait_img'] = $this->common->getImage($this->folder_content, $video[$j]['portrait_img']);
                    $video[$j]['landscape_img'] = $this->common->getImage($this->folder_content, $video[$j]['landscape_img']);
                    if ($video[$j]['content_upload_type'] == 'server_video') {
                        $video[$j]['content'] = $this->common->getVideo($this->folder_content, $video[$j]['content']);
                    }

                    $video[$j]['user_id'] = $this->common->getUserId($video[$j]['channel_id']);
                    $video[$j]['channel_name'] = $this->common->getChannelName($video[$j]['channel_id']);
                    $video[$j]['channel_image'] = $this->common->getChannelImage($video[$j]['channel_id']);
                    $video[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);

                    $data['video'][] = $video[$j];
                }

                $channel = User::where('channel_name', 'LIKE', "%{$name}%")->where('status', 1)->orderBy('id', 'desc')->latest()->take(10)->get();
                for ($i = 0; $i < count($channel); $i++) {

                    $channel[$i]['image'] = $this->common->getImage($this->folder_user, $channel[$i]['image']);
                    $channel[$i]['cover_img'] = $this->common->getImage($this->folder_user, $channel[$i]['cover_img']);
                    $channel[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $data['channel'][] = $channel[$i];
                }

                $music = Content::where('content_type', 2)->where('title', 'LIKE', "%{$name}%")->where('status', 1)->where('is_rent', 0)->orderBy('total_view', 'desc')->orderBy('total_like', 'desc')->latest()->take(10)->get();
                for ($j = 0; $j < count($music); $j++) {

                    $music[$j]['portrait_img'] = $this->common->getImage($this->folder_content, $music[$j]['portrait_img']);
                    $music[$j]['landscape_img'] = $this->common->getImage($this->folder_content, $music[$j]['landscape_img']);
                    if ($music[$j]['content_upload_type'] == 'server_video') {
                        $music[$j]['content'] = $this->common->getVideo($this->folder_content, $music[$j]['content']);
                    }
                    $music[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    $data['music'][] = $music[$j];
                }

                $podcast = Content::where('content_type', 4)->where('title', 'LIKE', "%{$name}%")->where('status', 1)->where('is_rent', 0)->orderBy('total_view', 'desc')->orderBy('total_like', 'desc')->latest()->take(10)->get();
                for ($j = 0; $j < count($podcast); $j++) {

                    $podcast[$j]['portrait_img'] = $this->common->getImage($this->folder_content, $podcast[$j]['portrait_img']);
                    $podcast[$j]['landscape_img'] = $this->common->getImage($this->folder_content, $podcast[$j]['landscape_img']);

                    $podcast[$j]['user_id'] = $this->common->getUserId($podcast[$j]['channel_id']);
                    $podcast[$j]['channel_name'] = $this->common->getChannelName($podcast[$j]['channel_id']);
                    $podcast[$j]['channel_image'] = $this->common->getChannelImage($podcast[$j]['channel_id']);
                    $podcast[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);

                    $data['podcast'][] = $podcast[$j];
                }

                $radio = Content::where('content_type', 6)->where('title', 'LIKE', "%{$name}%")->where('status', 1)->where('is_rent', 0)->orderBy('total_view', 'desc')->orderBy('total_like', 'desc')->latest()->take(10)->get();
                for ($j = 0; $j < count($radio); $j++) {

                    $radio[$j]['portrait_img'] = $this->common->getImage($this->folder_content, $radio[$j]['portrait_img']);
                    $radio[$j]['landscape_img'] = $this->common->getImage($this->folder_content, $radio[$j]['landscape_img']);
                    $radio[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);

                    $data['radio'][] = $radio[$j];
                }
            }

            return $data;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_rent_section(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'numeric',
                ],
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = isset($request->user_id) ? $request->user_id : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Rent_Section::where('status', 1)->orderBy('sortable', 'asc')->latest();

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

                    $query = Content::where('content_type', 1)->where('is_rent', 1)->where('category_id', $data[$i]['category_id'])->where('status', 1)->orderBy('id', 'desc')->latest()->take($data[$i]['no_of_content'])->get();

                    for ($j = 0; $j < count($query); $j++) {

                        $query[$j]['portrait_img'] = $this->common->getImage($this->folder_content, $query[$j]['portrait_img']);
                        $query[$j]['landscape_img'] = $this->common->getImage($this->folder_content, $query[$j]['landscape_img']);
                        if ($query[$j]['content_upload_type'] == 'server_video') {
                            $query[$j]['content'] = $this->common->getVideo($this->folder_content, $query[$j]['content']);
                        }

                        $query[$j]['user_id'] = $this->common->getUserId($query[$j]['channel_id']);
                        $query[$j]['channel_name'] = $this->common->getChannelName($query[$j]['channel_id']);
                        $query[$j]['channel_image'] = $this->common->getChannelImage($query[$j]['channel_id']);
                        $query[$j]['category_name'] = $this->common->getCategoryName($query[$j]['category_id']);
                        $query[$j]['language_name'] = $this->common->getLanguageName($query[$j]['language_id']);
                        $query[$j]['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $query[$j]['content_type'], $query[$j]['id'], 0);
                        $query[$j]['is_rent_buy'] = $this->common->getRentBuy($user_id, $query[$j]['id']);
                        $query[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);
                    }
                    $data[$i]['data'] = $query;
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_rent_section_detail(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'numeric',
                    'section_id' => 'required|numeric',
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

            $section_id = $request['section_id'];
            $user_id = isset($request->user_id) ? $request->user_id : 0;

            $section = Rent_Section::where('id', $section_id)->first();
            if ($section != null && isset($section)) {

                $page_size = 0;
                $current_page = 0;
                $more_page = false;

                $data = Content::where('content_type', 1)->where('is_rent', 1)->where('category_id', $section['category_id'])->where('status', 1)->orderBy('id', 'desc')->latest();

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
                        $data[$j]['language_name'] = $this->common->getLanguageName($data[$j]['language_id']);
                        $data[$j]['is_user_like_dislike'] = $this->common->getUserLikeDislike($user_id, $data[$j]['content_type'], $data[$j]['id'], 0);
                        $data[$j]['is_rent_buy'] = $this->common->getRentBuy($user_id, $data[$j]['id']);
                        $data[$j]['is_buy'] = $this->common->is_any_package_buy($user_id);
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
    public function add_rent_transaction(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'content_id' => 'required|numeric',
                    'price' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'content_id.required' => __('api_msg.content_id_is_required'),
                    'price.required' => __('api_msg.price_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request->user_id;
            $content_id = $request->content_id;
            $price = $request->price;
            $description = isset($request->description) ? $request->description : "";
            $transaction_id = isset($request->transaction_id) ? $request->transaction_id : "";

            $Cdata = Content::where('id', $content_id)->where('status', '1')->where('is_rent', 1)->first();
            if ($Cdata == null && !isset($Cdata)) {
                return $this->common->API_Response(400, __('api_msg.please_enter_right_content_id'));
            }

            $insert = new Rent_Transaction();
            $insert->user_id = $user_id;
            $insert->content_id = $content_id;
            $insert->transaction_id = $transaction_id;
            $insert->price = $price;
            $insert->description = $description;
            $insert->status = 1;

            $setting = Setting_Data();
            $admin_commission = $setting['rent_commission'];
            $persentage = round(($admin_commission / 100) * $price);
            $user_wallet_amount = $price - $persentage;

            $insert->admin_commission = $persentage;
            $insert->user_wallet_amount = $user_wallet_amount;

            if ($insert->save()) {

                // Send Mail (Type = 1- Register, 2- Transaction, 3- Report, 4- User Penal Active)
                $user_email = User::where('id', $user_id)->first();
                if ($user_email != null && isset($user_email)) {
                    $this->common->Send_Mail(2, $user_email);
                }

                // User Wallet Add Amount
                User::where('channel_id', $Cdata['channel_id'])->increment('wallet_earning', $user_wallet_amount);

                return $this->common->API_Response(200, __('api_msg.transaction_successfully'), []);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_user_rent_content(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
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

            $user_id = $request['user_id'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $rent_id = Rent_Transaction::where('user_id', $user_id)->where('status', 1)->orderBy('id', 'desc')->latest()->get();
            $content_id = [];
            for ($i = 0; $i < count($rent_id); $i++) {
                $content_id[] = $rent_id[$i]['content_id'];
            }

            if (count($content_id) > 0) {

                $ids_ordered = implode(',', $content_id);
                $data = Content::whereIn('id', $content_id)->orderByRaw("FIELD(id, $ids_ordered)");

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
                        $data[$i]['is_rent_buy'] = $this->common->getRentBuy($user_id, $data[$i]['id']);
                        $data[$i]['is_buy'] = $this->common->is_any_package_buy($user_id);
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
    public function get_rent_content_by_channel(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'channel_id' => 'required',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'channel_id.required' => __('api_msg.channel_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $channel_id = $request['channel_id'];
            $content_type = 1;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Content::where('content_type', $content_type)->where('channel_id', $channel_id)->where('status', 1)->where('is_rent', 0)->orderby('id', 'desc')->latest();

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
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_notification(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
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

            $user_id = $request['user_id'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $user_notification_id = Read_Notification::where('user_id', $user_id)->where('status', 1)->get();
            $NotiIds = [];
            foreach ($user_notification_id as $key => $value) {
                $NotiIds[] = $value['notification_id'];
            }
            $data = Notification::where('from_user_id', $user_id)->where('status', 1)->orwhere('type', 1)->whereNotIn('id', $NotiIds)->with('user', 'content')->orderBy('id', 'desc')->latest();

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get();

            $this->common->imageNameToUrl($data, 'image', $this->folder_notification);

            foreach ($data as $key => $value) {

                $value['user_name'] = "";
                $value['user_image'] = asset('assets/imgs/default.png');;
                if ($value['user'] != null) {

                    $this->common->imageNameToUrl(array($value['user']), 'image', $this->folder_user);
                    $value['user_name'] = $value['user']['channel_name'];
                    $value['user_image'] = $value['user']['image'];
                }

                $value['content_name'] = "";
                $value['content_image'] = asset('assets/imgs/no_img.png');
                if ($value['content'] != null) {

                    $this->common->imageNameToUrl(array($value['content']), 'portrait_img', $this->folder_content);
                    $value['content_name'] = $value['content']['title'];
                    $value['content_image'] = $value['content']['portrait_img'];
                }
                unset($value['user'], $value['content']);
            }

            return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function read_notification(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'notification_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'notification_id.required' => __('api_msg.notification_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $notification_id = $request['notification_id'];

            $get_data = Notification::where('id', $notification_id)->first();
            if ($get_data != null && isset($get_data)) {

                if ($get_data['type'] == 1) {

                    $check_read = Read_Notification::where('user_id', $user_id)->where('notification_id', $notification_id)->where('status', 1)->first();
                    if ($check_read == null && !isset($check_read)) {

                        $insert = new Read_Notification();
                        $insert['user_id'] = $user_id;
                        $insert['notification_id'] = $notification_id;
                        $insert['status'] = 1;
                        $insert->save();
                    }
                } else {
                    $get_data->delete();
                }
            }
            return $this->common->API_Response(200, __('api_msg.read_notification_successfully'), []);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function delete_content(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'content_type' => 'required|numeric',
                    'content_id' => 'required|numeric',
                    'episode_id' => 'numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'content_type.required' => __('api_msg.content_type_is_required'),
                    'content_id.required' => __('api_msg.content_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $content_id = $request['content_id'];
            $content_type = $request['content_type'];
            $episode_id = isset($request->episode_id) ? $request->episode_id : 0;

            $content = Content::where('content_type', $content_type)->where('id', $content_id)->first();
            if ($content != null && isset($content)) {

                if ($content_type == 1 || $content_type == 3) { // 1- Video, 3- Reels

                    // Content Delete
                    $this->common->deleteImageToFolder($this->folder_content, $content['portrait_img']);
                    $this->common->deleteImageToFolder($this->folder_content, $content['landscape_img']);
                    $this->common->deleteImageToFolder($this->folder_content, $content['content']);
                    $content->delete();

                    // Content Releted Data Delete
                    Comment::where('content_id', $content_id)->delete();
                    Content_Report::where('content_id', $content_id)->delete();
                    History::where('content_id', $content_id)->delete();
                    Like::where('content_id', $content_id)->delete();
                    Notification::where('content_id', $content_id)->delete();
                    View::where('content_id', $content_id)->delete();
                    Watch_later::where('content_id', $content_id)->delete();

                    return $this->common->API_Response(200, __('api_msg.content_delete_successfully'), []);
                } else if ($content_type == 4) { // 4- Podcasts-Episode

                    if ($episode_id != 0) {

                        // Content Releted Data Delete
                        Comment::where('content_id', $content_id)->where('episode_id', $episode_id)->delete();
                        Content_Report::where('content_id', $content_id)->where('episode_id', $episode_id)->delete();
                        $episode = Episode::where('podcasts_id', $content_id)->where('id', $episode_id)->first();
                        if ($episode != null & isset($episode)) {
                            $this->common->deleteImageToFolder($this->folder_content, $episode['portrait_img']);
                            $this->common->deleteImageToFolder($this->folder_content, $episode['landscape_img']);
                            $this->common->deleteImageToFolder($this->folder_content, $episode['episode_audio']);
                            $episode->delete();
                        }
                        History::where('content_id', $content_id)->where('episode_id', $episode_id)->delete();
                        Like::where('content_id', $content_id)->where('episode_id', $episode_id)->delete();
                        View::where('content_id', $content_id)->where('episode_id', $episode_id)->delete();
                        Watch_later::where('content_id', $content_id)->where('episode_id', $episode_id)->delete();
                    } else {

                        // Content Delete
                        $this->common->deleteImageToFolder($this->folder_content, $content['portrait_img']);
                        $this->common->deleteImageToFolder($this->folder_content, $content['landscape_img']);
                        $this->common->deleteImageToFolder($this->folder_content, $content['content']);
                        $content->delete();

                        // Content Releted Data Delete
                        Comment::where('content_id', $content_id)->delete();
                        Content_Report::where('content_id', $content_id)->delete();
                        $episode = Episode::where('podcasts_id', $content_id)->get();
                        for ($i = 0; $i < count($episode); $i++) {

                            $this->common->deleteImageToFolder($this->folder_content, $episode[$i]['portrait_img']);
                            $this->common->deleteImageToFolder($this->folder_content, $episode[$i]['landscape_img']);
                            $this->common->deleteImageToFolder($this->folder_content, $episode[$i]['episode_audio']);
                            $episode[$i]->delete();
                        }
                        History::where('content_id', $content_id)->delete();
                        Like::where('content_id', $content_id)->delete();
                        Notification::where('content_id', $content_id)->delete();
                        View::where('content_id', $content_id)->delete();
                        Watch_later::where('content_id', $content_id)->delete();
                    }
                    return $this->common->API_Response(200, __('api_msg.content_delete_successfully'), []);
                } else if ($content_type == 5) { // 5- Playlist

                    // Content Delete
                    $this->common->deleteImageToFolder($this->folder_content, $content['portrait_img']);
                    $this->common->deleteImageToFolder($this->folder_content, $content['landscape_img']);
                    $this->common->deleteImageToFolder($this->folder_content, $content['content']);
                    $content->delete();

                    // Content Releted Data Delete
                    Comment::where('content_id', $content_id)->delete();
                    Content_Report::where('content_id', $content_id)->delete();
                    History::where('content_id', $content_id)->delete();
                    Like::where('content_id', $content_id)->delete();
                    Notification::where('content_id', $content_id)->delete();
                    Playlist_Content::where('playlist_id', $content_id)->delete();
                    View::where('content_id', $content_id)->delete();
                    Watch_later::where('content_id', $content_id)->delete();

                    return $this->common->API_Response(200, __('api_msg.content_delete_successfully'), []);
                } else {
                    return $this->common->API_Response(200, __('api_msg.content_delete_successfully'), []);
                }
            } else {
                return $this->common->API_Response(200, __('api_msg.content_delete_successfully'), []);
            }
            return $this->common->API_Response(200, __('api_msg.read_notification_successfully'), []);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_withdrawal_request_list(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
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

            $user_id = $request->user_id;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Withdrawal_Request::where('user_id', $user_id)->with('user')->latest()->orderBy('id', 'desc');

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

                    $data[$i]['channel_name'] = "";
                    $data[$i]['full_name'] = "";
                    $data[$i]['email'] = "";
                    $data[$i]['mobile_number'] = "";
                    $data[$i]['image'] = asset('assets/imgs/default.png');
                    if ($data[$i]['user'] != null && isset($data[$i]['user'])) {
                        $data[$i]['channel_name'] = $data[$i]['user']['channel_name'];
                        $data[$i]['full_name'] = $data[$i]['user']['full_name'];
                        $data[$i]['email'] = $data[$i]['user']['email'];
                        $data[$i]['mobile_number'] = $data[$i]['user']['mobile_number'];
                        $data[$i]['image'] = $this->common->getImage($this->folder_user, $data[$i]['user']['image']);
                        unset($data[$i]['user']);
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
    public function get_transaction_list(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
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

            $user_id = $request->user_id;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Transaction::where('user_id', $user_id)->with('user')->latest()->orderBy('id', 'desc');

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

                    $data[$i]['channel_name'] = "";
                    $data[$i]['full_name'] = "";
                    $data[$i]['email'] = "";
                    $data[$i]['mobile_number'] = "";
                    $data[$i]['image'] = asset('assets/imgs/default.png');
                    if ($data[$i]['user'] != null && isset($data[$i]['user'])) {
                        $data[$i]['channel_name'] = $data[$i]['user']['channel_name'];
                        $data[$i]['full_name'] = $data[$i]['user']['full_name'];
                        $data[$i]['email'] = $data[$i]['user']['email'];
                        $data[$i]['mobile_number'] = $data[$i]['user']['mobile_number'];
                        $data[$i]['image'] = $this->common->getImage($this->folder_user, $data[$i]['user']['image']);
                        unset($data[$i]['user']);
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
    // public function get_post_backup(Request $request)
    // {
    //     try {
    //         $this->common->package_expiry();

    //         $validation = Validator::make($request->all(), [
    //             'user_id' => 'numeric',
    //         ]);
    //         if ($validation->fails()) {
    //             $data['status'] = 400;
    //             $data['message'] = $validation->errors()->first();
    //             return $data;
    //         }

    //         $user_id = isset($request->user_id) ? $request->user_id : 0;
    //         $category_id = isset($request->category_id) ? $request->category_id : 0;

    //         $page_size = 0;
    //         $current_page = 0;
    //         $more_page = false;

    //         if ($category_id == 0) {
    //             $data = Post::where('status', 1)->with('channel');
    //         } else {
    //             $data = Post::where('category_id', $category_id)->where('status', 1)->with('channel');
    //         }

    //         $total_rows = $data->count();
    //         $total_page = $this->page_limit;
    //         $page_size = ceil($total_rows / $total_page);
    //         $current_page = $request->page_no ?? 1;
    //         $offset = $current_page * $total_page - $total_page;

    //         $more_page = $this->common->more_page($current_page, $page_size);
    //         $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);
    //         $data->take($total_page)->offset($offset);
    //         $data = $data->get()->toArray();

    //         if (count($data) > 0) {
    //             $rk = [];
    //             foreach ($data as &$ra) {
    //                 // Image Video Full Path
    //                 $post_content = Post_Content::where('post_id', $ra['id'])->get();
    //                 $ra['post_content'] = [];
    //                 foreach ($post_content as $post) {
    //                     if ($post['content_type'] == 1) {
    //                         $post['content_url'] = $this->common->getImage($this->folder_post, $post['content_url']);
    //                     } else {
    //                         $post['content_url'] = $this->common->getVideo($this->folder_post, $post['content_url']);
    //                         $post['thumbnail_image'] = $this->common->getImage($this->folder_post, $post['thumbnail_image']);
    //                     }
    //                     $ra['post_content'][] = $post;
    //                 }

    //                 $ra['hastegs'] = $this->common->getHashTag($ra['hashtag_id']);

    //                 // User
    //                 $ra['user_id'] = "";
    //                 $ra['firebase_id'] = "";
    //                 $ra['channel_name'] = "";
    //                 $ra['full_name'] = "";
    //                 $ra['email'] = "";
    //                 $ra['country_code'] = "";
    //                 $ra['mobile_number'] = "";
    //                 $ra['country_name'] = "";
    //                 $ra['profile_img'] = asset('/assets/imgs/default.png');
    //                 if (isset($ra['channel'])) {
    //                     $ra['user_id'] = $ra['channel']['id'];
    //                     $ra['firebase_id'] = $ra['channel']['firebase_id'];
    //                     $ra['channel_name'] = $ra['channel']['channel_name'];
    //                     $ra['full_name'] = $ra['channel']['full_name'];
    //                     $ra['email'] = $ra['channel']['email'];
    //                     $ra['country_code'] = $ra['channel']['country_code'];
    //                     $ra['mobile_number'] = $ra['channel']['mobile_number'];
    //                     $ra['country_name'] = $ra['channel']['country_name'];
    //                     $ra['profile_img'] = $this->common->getImage($this->folder_user, $ra['channel']['image']);
    //                 }

    //                 // All Count
    //                 $ra = $this->common->get_all_count_for_post($ra, $user_id);

    //                 // Is Buy 
    //                 $ra['is_buy'] = $this->common->is_any_package_buy($user_id);

    //                 unset($ra['channel']);
    //                 $rk[] = $ra;
    //             }
    //             shuffle($rk);
    //             return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $rk, $pagination);
    //         } else {
    //             return $this->common->API_Response(400, __('api_msg.data_not_found'));
    //         }
    //     } catch (Exception $e) {
    //         return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
    //     }
    // }
    public function get_post(Request $request)
    {
        try {
            $this->common->package_expiry();

            $validation = Validator::make($request->all(), [
                'user_id' => 'numeric',
            ]);
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request->user_id;
            $category_id = isset($request->category_id) ? $request->category_id : 0;
            $page_no = $request->page_no ?? 1;

            $subscribed_users = Subscriber::where('user_id', $user_id)->with('to_user')->get();

            // Extract channel IDs
            $sub_channel_ids = [];
            foreach ($subscribed_users as $user) {
                if (isset($user['to_user']) && $user['to_user'] != null) {
                    $sub_channel_ids[] = $user['to_user']['channel_id'];
                }
            }

            if ($user_id != 0) {
                // ======================= Recent For Subscribed channel Data =======================
                $now = date("Y-m-d H:i:s");
                $last_24_hours = date("Y-m-d H:i:s", strtotime('-24 hours', strtotime($now)));

                $sub_recent_data_query = Post::where('created_at', ">", $last_24_hours)
                    ->whereIn('channel_id', $sub_channel_ids)
                    ->where('status', 1)
                    ->with('channel');

                if ($category_id != 0) {
                    $sub_recent_data_query->where('category_id', $category_id);
                }

                $sub_recent_data = $sub_recent_data_query->get()->toArray();

                // ======================= Recent Data =======================
                $recent_sub_data_ids = array_column($sub_recent_data, 'id');

                $recent_data_query = Post::where('created_at', ">", $last_24_hours)
                    ->whereNotIn('id', $recent_sub_data_ids)
                    ->where('status', 1)
                    ->with('channel')
                    ->latest();

                if ($category_id != 0) {
                    $recent_data_query->where('category_id', $category_id);
                }

                $recent_data = $recent_data_query->get()->toArray();
                $recent_data_ids = array_column($recent_data, 'id');

                // ======================= other Data =======================
                $other_data_query = Post::whereNotIn('id', $recent_sub_data_ids)
                    ->whereNotIn('id', $recent_data_ids)
                    ->where('status', 1)
                    ->with('channel')
                    ->orderBy('view', 'desc')
                    ->latest();

                if ($category_id != 0) {
                    $other_data_query->where('category_id', $category_id);
                }

                $other_data = $other_data_query->get()->toArray();

                $final_array = array_merge($sub_recent_data, $recent_data, $other_data);
            } else {
                $posts_query = Post::where('status', 1)
                    ->with('channel')
                    ->latest();

                if ($category_id != 0) {
                    $posts_query->where('category_id', $category_id);
                }

                $final_array = $posts_query->get()->toArray();
            }

            $currentItems = array_slice($final_array, $this->page_limit * ($page_no - 1), $this->page_limit);
            $paginator = new LengthAwarePaginator($currentItems, count($final_array), $this->page_limit, $page_no);

            $more_page = $this->common->more_page($page_no, $paginator->lastPage());
            $pagination = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);

            $data = $paginator->items();

            if (count($data) > 0) {
                $rk = array();
                foreach ($data as $ra) {
                    // Image Video Full Path
                    $post_content = Post_Content::where('post_id', $ra['id'])->get();
                    $ra['post_content'] = [];
                    foreach ($post_content as $post) {
                        if ($post['content_type'] == 1) {
                            $post['content_url'] = $this->common->getImage($this->folder_post, $post['content_url']);
                        } else {
                            $post['content_url'] = $this->common->getVideo($this->folder_post, $post['content_url']);
                            $post['thumbnail_image'] = $this->common->getImage($this->folder_post, $post['thumbnail_image']);
                        }
                        $ra['post_content'][] = $post;
                    }

                    $ra['hastegs'] = $this->common->getHashTag($ra['hashtag_id']);

                    // User
                    $ra['firebase_id'] = "";
                    $ra['channel_name'] = "";
                    $ra['full_name'] = "";
                    $ra['email'] = "";
                    $ra['country_code'] = "";
                    $ra['mobile_number'] = "";
                    $ra['country_name'] = "";
                    $ra['profile_img'] = asset('/assets/imgs/default.png');
                    if (isset($ra['channel'])) {
                        $ra['user_id'] = $ra['channel']['id'];
                        $ra['channel_name'] = $ra['channel']['channel_name'];
                        $ra['full_name'] = $ra['channel']['full_name'];
                        $ra['email'] = $ra['channel']['email'];
                        $ra['country_code'] = $ra['channel']['country_code'];
                        $ra['mobile_number'] = $ra['channel']['mobile_number'];
                        $ra['country_name'] = $ra['channel']['country_name'];
                        $ra['profile_img'] = $this->common->getImage($this->folder_user, $ra['channel']['image']);
                    }

                    // All Count
                    $ra = $this->common->get_all_count_for_post($ra, $user_id);

                    // Is Buy 
                    $ra['is_buy'] = $this->common->is_any_package_buy($user_id);

                    unset($ra['channel']);
                    $rk[] = $ra;
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $rk, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_channel_post(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'channel_id' => 'required',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'channel_id.required' => __('api_msg.channel_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request->user_id;
            $channel_id = $request->channel_id;
            $category_id = isset($request->category_id) ? $request->category_id : 0;

            if ($category_id != 0) {
                $data = Post::where('category_id', $category_id)->where('channel_id', $channel_id)->where('status', 1)->orderBy('created_at', "DESC")->with('channel');
            } else {
                $data = Post::where('channel_id', $channel_id)->where('status', 1)->orderBy('created_at', "DESC")->with('channel');
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get()->toArray();

            if (count($data) > 0) {

                $rk = array();
                foreach ($data as $ra) {

                    $ra['is_ads'] = 0;

                    // Image Video Full Path
                    $post_content = Post_Content::where('post_id', $ra)->get();
                    $ra['post_content'] = [];
                    foreach ($post_content as $post) {
                        if ($post['content_type'] == 1) {
                            $post['content_url'] = $this->common->getImage($this->folder_post, $post['content_url']);
                        } else {
                            $post['content_url'] = $this->common->getVideo($this->folder_post, $post['content_url']);
                            $post['thumbnail_image'] = $this->common->getImage($this->folder_post, $post['thumbnail_image']);
                        }
                        $ra['post_content'][] = $post;
                    }

                    $ra['hastegs'] = $this->common->getHashTag($ra['hashtag_id']);

                    // User
                    $ra['user_id'] = "";
                    $ra['channel_name'] = "";
                    $ra['full_name'] = "";
                    $ra['email'] = "";
                    $ra['country_code'] = "";
                    $ra['mobile_number'] = "";
                    $ra['country_name'] = "";
                    $ra['profile_img'] = asset('/assets/imgs/default.png');
                    if (isset($ra['channel'])) {
                        $ra['user_id'] = $ra['channel']['id'];
                        $ra['channel_name'] = $ra['channel']['channel_name'];
                        $ra['full_name'] = $ra['channel']['full_name'];
                        $ra['email'] = $ra['channel']['email'];
                        $ra['country_code'] = $ra['channel']['country_code'];
                        $ra['mobile_number'] = $ra['channel']['mobile_number'];
                        $ra['country_name'] = $ra['channel']['country_name'];
                        $ra['profile_img'] = $this->common->getImage($this->folder_user, $ra['channel']['image']);
                    }

                    // All Count
                    $ra = $this->common->get_all_count_for_post($ra, $ra['channel']['id']);

                    $ra['is_buy'] = $this->common->is_any_package_buy($user_id);
                    unset($ra['channel']);
                    $rk[] = $ra;
                }
                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $rk, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function post_content_upload(Request $request)  // 1- image, 2- Video	
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'content_type' => 'required',
                    'content' => 'required|max:51200',
                ],
                [
                    'content_type.required' => __('api_msg.content_type_is_required'),
                    'content.required' => __('api_msg.content_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $content_type = $request['content_type'];
            $content = $request['content'];
            $thumbnail_image = "";
            if ($content_type == 1) {

                $content = $this->common->saveImage($content, $this->folder_post, 'img_');
            } elseif ($content_type == 2) {

                $setting_data = Setting_Data();
                if ($setting_data['sight_engine_status'] == 1) { // sight engine video Redaction

                    $user_key = $setting_data['sight_engine_user_key'];
                    $secret_key = $setting_data['sight_engine_secret_key'];
                    $concepts = $setting_data['sight_engine_concepts'];

                    $video = $request->file('content');

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
                                        $path = $this->folder_post . '/' . $filename;
                                        Storage::disk('public')->put($path, $video_get->body());

                                        // // Delete the old video file
                                        // $this->common->deleteImageToFolder($this->folder_post, $content);
                                        $content = $filename;
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
                    $content = $this->common->saveImage($content, $this->folder_post, 'vid_');
                }

                $thumbnail_image =  $this->common->getimagefromvideo($content);
            }

            if ($content) {
                $data = [];
                if ($content_type == 1) {

                    $data['content_type'] = $content_type;
                    $data['content_name'] = $content;
                    $data['content_url'] = $this->common->getImage($this->folder_post, $content);
                    $data['thumbnail_image'] = $thumbnail_image;
                    $data['thumbnail_image_url'] =  $this->common->getImage($this->folder_post, $thumbnail_image);
                } else {

                    $data['content_type'] = $content_type;
                    $data['content_name'] = $content;
                    $data['content_url'] = $this->common->getVideo($this->folder_post, $content);
                    $data['thumbnail_image'] = $thumbnail_image;
                    $data['thumbnail_image_url'] =  $this->common->getImage($this->folder_post, $thumbnail_image);
                }

                if ($data != null) {
                    return $this->common->API_Response(200, __('api_msg.content_save'), $data);
                } else {
                    return $this->common->API_Response(400, __('api_msg.content_not_saved'));
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.content_not_saved'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function upload_post(Request $request) // 1- image, 2- Video	
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'channel_id' => 'required',
                    'title' => 'required',
                    'is_comment' => 'required|numeric',
                ],
                [
                    'channel_id.required' => __('api_msg.channel_id_is_required'),
                    'title.required' => __('api_msg.title_is_required'),
                    'is_comment.required' => __('api_msg.is_comment_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $data['channel_id'] = $request['channel_id'];
            $data['category_id'] = 0;
            $hashtag_id = $this->common->checkHashTag($request['title']);
            $hashtagId = 0;
            if (count($hashtag_id) > 0) {
                $hashtagId = implode(',', $hashtag_id);
            }

            $data['hashtag_id'] = $hashtagId;
            $data['title'] = $request['title'];
            $data['descripation'] = $request['descripation'] != null ? $request['descripation'] : "";
            $data['is_comment'] = $request['is_comment'];
            $data['view'] = 0;

            $post_id = Post::insertGetId($data);

            if (isset($post_id)) {

                $post_content = $request['post_content'];

                if (is_string($post_content)) {
                    $post_content = json_decode($post_content, true);
                }

                if (!empty($post_content)) {

                    foreach ($post_content as $item) {
                        $content_type = $item['content_type'];
                        $content_url = $item['content_url'];
                        $thumbnail_image = isset($item['thumbnail_image']) ? $item['thumbnail_image'] : "";

                        if (!is_null($content_url)) {
                            Post_Content::Create([
                                'post_id' => $post_id,
                                'content_type' => $content_type,
                                'content_url' => $content_url,
                                'thumbnail_image' => $thumbnail_image,
                            ]);
                        }
                    }
                }
                return $this->common->API_Response(200, __('api_msg.post_upload_successfully'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function delete_post(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'channel_id' => 'required',
                    'post_id' => 'required|numeric',
                ],
                [
                    'channel_id.required' => __('api_msg.channel_id_is_required'),
                    'post_id.required' => __('api_msg.post_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $channel_id = $request->channel_id;
            $post_id = $request->post_id;

            $post = Post::where('id', $post_id)->first();
            if (isset($post) && $post != null) {

                $post_content = Post_Content::where('post_id', $post['id'])->get();
                foreach ($post_content as $data) {
                    $this->common->deleteImageToFolder($this->folder_post, $data['content_url']);
                    $this->common->deleteImageToFolder($this->folder_post, $data['thumbnail_image']);
                    $data->delete();
                }
                $this->common->Delete_All_Data($data['id']);
                $post->delete();
            }
            return $this->common->API_Response(200, __('api_msg.content_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_post_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'post_id' => 'required|numeric',
                    'comment' => 'required',
                    'comment_id' => 'numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'post_id.required' => __('api_msg.post_id_is_required'),
                    'comment.required' => __('api_msg.comment_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $comment_id = isset($request->comment_id) ? $request->comment_id : 0;
            $user_id = $request['user_id'];
            $post_id = $request['post_id'];
            $comment = $request['comment'];

            $insert = new Post_Comment();
            $insert['comment_id'] = $comment_id;
            $insert['user_id'] = $user_id;
            $insert['post_id'] = $post_id;
            $insert['comment'] = $comment;

            if ($insert->save()) {

                $user = User::where('id', $user_id)->first();
                $content = Post::where('id', $post_id)->with('channel')->first();
                if (isset($user) && $user != null && $content != null && isset($content) && $content['channel'] != null && $user_id != $content['channel']['id']) {

                    $title = $user['channel_name'] . ' Commented on your Post.';
                    $this->common->save_notification(3, $title, $user_id, $content['channel']['id'], $post_id);
                }
                return $this->common->API_Response(200, __('api_msg.comment_add_successfully'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function edit_post_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'comment_id' => 'required|numeric',
                    'comment' => 'required',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'comment.required' => __('api_msg.comment_is_required'),
                    'comment_id.required' => __('api_msg.comment_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $comment_id = $request['comment_id'];
            $comment = $request['comment'];

            $update = Post_Comment::where('id', $comment_id)->first();
            if (isset($update) && $update != null) {

                $update['comment'] = $comment;
                $update->save();
                return $this->common->API_Response(200, __('api_msg.comment_edit_successfully'));
            }
            return $this->common->API_Response(200, __('api_msg.comment_not_found'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function delete_post_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'comment_id' => 'required|numeric',
                ],
                [
                    'comment_id.required' => __('api_msg.comment_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $comment_id = $request['comment_id'];
            Post_Comment::where('id', $comment_id)->delete();
            return $this->common->API_Response(200, __('api_msg.comment_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_post_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'post_id' => 'required|numeric',
                ],
                [
                    'post_id.required' => __('api_msg.post_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $post_id = $request['post_id'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Post_Comment::where('post_id', $post_id)->where('comment_id', 0)->where('status', 1)->with('user')->orderBy('id', 'desc');

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

                    $data[$i]['channel_name'] = "";
                    $data[$i]['full_name'] = "";
                    $data[$i]['email'] = "";
                    $data[$i]['image'] = "";
                    if ($data[$i]['user'] != null) {
                        $data[$i]['channel_name'] = $data[$i]['user']['channel_name'];
                        $data[$i]['full_name'] = $data[$i]['user']['full_name'];
                        $data[$i]['email'] = $data[$i]['user']['email'];
                        $data[$i]['image'] = $this->common->getImage($this->folder_user, $data[$i]['user']['image']);
                    }
                    unset($data[$i]['user']);

                    $data[$i]['is_reply'] = 0;
                    $data[$i]['total_reply'] = 0;
                    $reply = Post_Comment::where('comment_id', $data[$i]['id'])->count();
                    if ($reply != 0) {
                        $data[$i]['is_reply'] = 1;
                        $data[$i]['total_reply'] = $reply;
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
    public function get_post_reply_comment(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'comment_id' => 'required|numeric',
                ],
                [
                    'comment_id.required' => __('api_msg.comment_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $comment_id = $request['comment_id'];

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Post_Comment::where('comment_id', $comment_id)->where('status', 1)->orderBy('id', 'desc')->with('user');

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

                    $data[$i]['channel_name'] = "";
                    $data[$i]['full_name'] = "";
                    $data[$i]['email'] = "";
                    $data[$i]['image'] = "";
                    if ($data[$i]['user'] != null) {
                        $data[$i]['channel_name'] = $data[$i]['user']['channel_name'];
                        $data[$i]['full_name'] = $data[$i]['user']['full_name'];
                        $data[$i]['email'] = $data[$i]['user']['email'];
                        $data[$i]['image'] = $this->common->getImage($this->folder_user, $data[$i]['user']['image']);
                    }
                    unset($data[$i]['user']);

                    $data[$i]['is_reply'] = 0;
                    $data[$i]['total_reply'] = 0;
                    $reply = Post_Comment::where('comment_id', $data[$i]['id'])->count();
                    if ($reply != 0) {
                        $data[$i]['is_reply'] = 1;
                        $data[$i]['total_reply'] = $reply;
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
    public function like_unlike_post(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'post_id' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'post_id.required' => __('api_msg.post_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $user_id = $request['user_id'];
            $post_id = $request['post_id'];

            $data = Post_Like::where('user_id', $user_id)->where('post_id', $post_id)->first();

            if (isset($data['id'])) {

                Post_Like::where('id', $data['id'])->delete();
                return $this->common->API_Response(200, __('api_msg.unlike_successfully'));
            } else {

                $data['user_id'] = $user_id;
                $data['post_id'] = $post_id;

                $added_id = Post_Like::insertGetId($data);

                $user = User::where('id', $user_id)->first();
                $content = Post::where('id', $post_id)->with('channel')->first();
                if (isset($user) && $user != null && $content != null && isset($content) && $content['channel'] != null && $user_id != $content['channel']['id']) {

                    $title = $user['channel_name'] . ' Liked your Post.';
                    $this->common->save_notification(2, $title, $user_id, $content['channel']['id'], $post_id);
                }

                return $this->common->API_Response(200, __('api_msg.like_successfully'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_post_view(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'post_id' => 'required|numeric',
                    'user_id' => 'required|numeric',
                ],
                [
                    'post_id.required' => __('api_msg.post_id_is_required'),
                    'user_id.required' => __('api_msg.user_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $check = Post_View::where('user_id', $request['user_id'])->where('post_id', $request['post_id'])->latest()->first();
            if (!isset($check) && $check == null) {

                $insert = new Post_View();
                $insert['user_id'] = $request['user_id'];
                $insert['post_id'] = $request['post_id'];
                $insert['status'] = 1;
                if ($insert->save()) {
                    Post::where('id', $request->post_id)->increment('view', 1);
                }
                return $this->common->API_Response(200, __('api_msg.post_view_successfully'));
            } else {
                return $this->common->API_Response(400, __('api_msg.already_viewed'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function add_post_report(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'report_user_id' => 'required|numeric',
                    'post_id' => 'required',
                    'message' => 'required',
                ],
                [
                    'report_user_id.required' => __('api_msg.report_user_id_is_required'),
                    'post_id.required' => __('api_msg.post_id_is_required'),
                    'message.required' => __('api_msg.message_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $report_user_id = $request['report_user_id'];
            $post_id = $request['post_id'];
            $message = $request['message'];

            $report = Post_Report::where('report_user_id', $report_user_id)->where('post_id', $post_id)->where('status', 1)->first();
            if (!isset($report['id']) && $report == null) {

                $insert['report_user_id'] = $report_user_id;
                $insert['post_id'] = $post_id;
                $insert['message'] = $message;
                $insert['status'] = 1;
                Post_Report::insertGetId($insert);

                return $this->common->API_Response(200, __('api_msg.report_add_successfully'));
            } else {
                return $this->common->API_Response(400, __('api_msg.this_post_has_been_previously_reported'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_gift(Request $request)
    {
        try {

            $user_id = isset($request->user_id) ? $request->user_id : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Gift::orderBy('id', 'desc')->latest();

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get();

            $this->common->imageNameToUrl($data, 'image', $this->folder_gift);

            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['is_buy'] = $this->common->gift_buy($user_id, $data[$i]['id']);
            }

            return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function get_user_gift(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
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

            $user_id = $request->user_id;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $get_gift = Gift_Transaction::where('user_id', $user_id)->latest()->get();
            $gift_ids = [];
            foreach ($get_gift as $key => $value) {
                $gift_ids[] = $value['gift_id'];
            }

            $data = Gift::whereIn('id', $gift_ids)->orderBy('id', 'DESC')->latest();

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

                $this->common->imageNameToUrl($data, 'image', $this->folder_gift);
                for ($i = 0; $i < count($data); $i++) {
                    $data[$i]['is_buy'] = $this->common->gift_buy($user_id, $data[$i]['id']);
                }

                return $this->common->API_Response(200, __('api_msg.get_record_successfully'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function buy_gift(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'user_id' => 'required|numeric',
                    'gift_id' => 'required|numeric',
                    'coin' => 'required|numeric',
                ],
                [
                    'user_id.required' => __('api_msg.user_id_is_required'),
                    'gift_id.required' => __('api_msg.gift_id_is_required'),
                    'coin.required' => __('api_msg.coin_is_required'),
                ]
            );
            if ($validation->fails()) {
                $data['status'] = 400;
                $data['message'] = $validation->errors()->first();
                return $data;
            }

            $data['user_id'] = $request['user_id'];
            $data['gift_id'] = $request['gift_id'];
            $data['coin'] = $request['coin'];
            $data['status'] = 1;

            $user_wallet = User::where('id', $request['user_id'])->first();
            if (isset($user_wallet) && $user_wallet['wallet_balance'] >= $request['coin']) {

                $result = Gift_Transaction::insertGetId($data);

                if (isset($result) && $result > 0) {

                    User::where('id', $request['user_id'])->decrement('wallet_balance', $request['coin']);

                    return $this->common->API_Response(200, 'Congratulations!!! gift is bought successfully.');
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            } else {
                return $this->common->API_Response(400, 'Opps, Add Amount in Your Wallet.');
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
