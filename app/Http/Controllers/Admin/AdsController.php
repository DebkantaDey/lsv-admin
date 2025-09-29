<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use App\Models\Ads_View_Click_Count;
use App\Models\Common;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class AdsController extends Controller
{
    private $folder = "ads";
    private $folder_user = "user";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['data'] = [];

            $user_ids = [];
            $ads_channel_list = Ads::select('user_id')->groupBY('user_id')->get();
            for ($i = 0; $i < count($ads_channel_list); $i++) {
                $user_ids[] = $ads_channel_list[$i]['user_id'];
            }

            $input_search = $request['input_search'];
            if ($input_search != null && isset($input_search)) {
                $params['data'] = User::whereIn('id', $user_ids)->where('channel_name', 'LIKE', "%{$input_search}%")->orwhere('full_name', 'LIKE', "%{$input_search}%")
                    ->orwhere('email', 'LIKE', "%{$input_search}%")->orwhere('mobile_number', 'LIKE', "%{$input_search}%")->orderBy('id', 'DESC')->paginate(15);
            } else {
                $params['data'] = User::whereIn('id', $user_ids)->orderBy('id', 'DESC')->paginate(15);
            }

            for ($i = 0; $i < count($params['data']); $i++) {
                $params['data'][$i]['total_ads'] = Ads::where('user_id', $params['data'][$i]['id'])->count();
                $params['data'][$i]['total_active_ads'] = Ads::where('user_id', $params['data'][$i]['id'])->where('status', 1)->count();
            }
            $this->common->imageNameToUrl($params['data'], 'image', $this->folder_user);

            return view('admin.ads.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function create()
    {
        try {

            $params['data'] = [];
            $params['user'] = User::orderBy('channel_name', 'asc')->latest()->get();
            $params['ads_setting'] = Setting_Data();

            return view('admin.ads.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'user_id' => 'required',
                'title' => 'required',
                'redirect_uri' => 'required',
                'budget' => 'required|numeric|min:1',
                'image' => 'required|image|mimes:jpeg,png,jpg',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            if ($request->type == 3) {
                $validator1 = Validator::make($request->all(), [
                    'video' => 'required',
                ]);
                if ($validator1->fails()) {
                    $errs1 = $validator1->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs1));
                }
            }

            // Budget Check
            $user_budget = $this->common->get_user_budget($request['user_id']);
            if ($user_budget < $request['budget']) {
                return response()->json(array('status' => 400, 'errors' => 'Recharge you Wallet.'));
            }

            $requestData = $request->all();
            $requestData['status'] = 1;
            $requestData['is_hide'] = 0;
            $file = $requestData['image'];
            $requestData['image'] = $this->common->saveImage($file, $this->folder);

            if ($requestData['type'] == 3) {
                $requestData['video'] = $requestData['video'];
            } else {
                $requestData['video'] = "";
            }

            $ads_data = Ads::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($ads_data->id)) {

                return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_added')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function adsList($user_id, Request $request)
    {
        try {

            $params['data'] = [];
            $params['user_id'] = $user_id;

            $input_search = $request['input_search'];
            $input_type = $request['input_type'];
            if ($input_search != null && isset($input_search)) {

                if ($input_type != 0) {
                    $params['data'] = Ads::where('user_id', $user_id)->where('title', 'LIKE', "%{$input_search}%")->where('type', $input_type)->orderBy('id', 'DESC')->paginate(15);
                } else {
                    $params['data'] = Ads::where('user_id', $user_id)->where('title', 'LIKE', "%{$input_search}%")->orderBy('id', 'DESC')->paginate(15);
                }
            } else {

                if ($input_type != 0) {
                    $params['data'] = Ads::where('user_id', $user_id)->where('type', $input_type)->orderBy('id', 'DESC')->paginate(15);
                } else {
                    $params['data'] = Ads::where('user_id', $user_id)->orderBy('id', 'DESC')->paginate(15);
                }
            }

            $this->common->imageNameToUrl($params['data'], 'image', $this->folder);
            for ($i = 0; $i < count($params['data']); $i++) {

                if ($params['data'][$i]['type'] == 3) {
                    $this->common->videoNameToUrl(array($params['data'][$i]), 'video', $this->folder);
                }
            }

            return view('admin.ads.list', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {

            $data = Ads::where('id', $id)->first();
            if (isset($data)) {

                $this->common->deleteImageToFolder($this->folder, $data['image']);
                $this->common->deleteImageToFolder($this->folder, $data['video']);
                $data->delete();

                Ads_View_Click_Count::where('ads_id', $id)->delete();
            }

            return redirect()->route('ads.index')->with('success', __('Label.data_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function changeStatus(Request $request)
    {
        try {

            $data = Ads::where('id', $request->id)->first();
            if ($data->is_hide == 0) {
                $data->is_hide = 1;
            } elseif ($data->is_hide == 1) {
                $data->is_hide = 0;
            } else {
                $data->is_hide = 1;
            }
            $data->save();
            return response()->json(array('status' => 200, 'success' => 'Status Changed', 'id' => $data->id, 'is_hide' => $data->is_hide));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function adsDetails($user_id, $ads_id)
    {
        try {

            $params['user_id'] = $user_id;
            $params['ads_id'] = $ads_id;
            $params['data'] = Ads::where('id', $ads_id)->first();
            $params['total_ads_cpv'] = Ads_View_Click_Count::where('ads_id', $ads_id)->where('type', 1)->count();
            $params['total_ads_cpc'] = Ads_View_Click_Count::where('ads_id', $ads_id)->where('type', 2)->count();
            $params['total_use_budget'] = Ads_View_Click_Count::where('ads_id', $ads_id)->sum('total_coin');
            $params['total_ads_cpv_coin'] = Ads_View_Click_Count::where('ads_id', $ads_id)->where('type', 1)->sum('total_coin');
            $params['total_ads_cpc_coin'] = Ads_View_Click_Count::where('ads_id', $ads_id)->where('type', 2)->sum('total_coin');

            return view('admin.ads.details', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function saveChunk()
    {
        @set_time_limit(5 * 60);

        $targetDir = storage_path('/app/public/ads');
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds

        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }

        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

        // Remove old temp files
        if ($cleanupTargetDir && is_dir($targetDir) && $dir = opendir($targetDir)) {
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                // Remove temp file if it is older than the max age and not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        } else {
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
        }

        // Open temp file
        if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off
            rename("{$filePath}.part", $filePath);

            // Generate a new filename based on the current date and time
            $extension = pathinfo($fileName, PATHINFO_EXTENSION); // Get the file extension from the original filename
            $newFileName = 'ads' . date('_d_m_Y_') . rand(1111, 9999) . '.' . $extension; // Use the extracted extension
            $newFilePath = $targetDir . DIRECTORY_SEPARATOR . $newFileName;

            // Rename the uploaded file to the new filename
            rename($filePath, $newFilePath);

            // Send the new file name back to the client
            die(json_encode(array('jsonrpc' => '2.0', 'result' => $newFileName, 'id' => 'id')));
        }

        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }
}
