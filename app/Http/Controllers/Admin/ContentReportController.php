<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\User;
use App\Models\Content;
use App\Models\Content_Report;
use Illuminate\Http\Request;
use Exception;

class ContentReportController extends Controller
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
            $params['channel'] = User::orderby('id', 'desc')->latest()->get();

            $input_channel = $request['input_channel'];
            $input_type = $request['input_type'];

            $query = Content_Report::with('content', 'user', 'report_user')->orderBy('id', 'DESC');
            if ($input_channel != 0) {
                $query->where('user_id', $input_channel);
            }
            if ($input_type != 0) {
                $query->where('content_type', $input_type);
            }
            $params['data'] = $query->paginate(20);

            for ($i = 0; $i < count($params['data']); $i++) {

                $params['data'][$i]['portrait_img'] = asset('assets/imgs/no_img.png');
                $params['data'][$i]['video_upload_type'] = "";
                if ($params['data'][$i]['content'] != null) {

                    $params['data'][$i]['portrait_img'] = $this->common->getImage($this->folder, $params['data'][$i]['content']['portrait_img']);
                    $params['data'][$i]['video_upload_type'] = $params['data'][$i]['content']['content_upload_type'];
                    if ($params['data'][$i]['content']['content_type'] == 1 || $params['data'][$i]['content']['content_type'] == 2 && $params['data'][$i]['content']['content_upload_type'] == 'server_video') {
                        $params['data'][$i]['video'] = $this->common->getVideo($this->folder, $params['data'][$i]['content']['content']);
                    }
                }
            }

            return view('admin.content_report.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
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
}
