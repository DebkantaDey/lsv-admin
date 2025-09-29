<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Common;
use App\Models\Hashtag;
use App\Models\User;
use App\Models\Content;
use App\Models\Content_Report;
use App\Models\History;
use App\Models\Like;
use App\Models\Notification;
use App\Models\View;
use App\Models\Watch_later;
use CURLFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ReelsController extends Controller
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

            $this->common->Delete_Reels();

            $params['data'] = [];
            $params['channel'] = User::orderby('channel_name', 'asc')->latest()->get();

            $input_search = $request['input_search'];
            $input_channel = $request['input_channel'];

            $query = Content::where('content_type', 3)->orderBy('id', 'DESC');
            if ($input_search) {
                $query->where('title', 'LIKE', "%{$input_search}%");
            }
            if ($input_channel != 0) {
                $query->where('channel_id', $input_channel);
            }
            $params['data'] = $query->paginate(15);

            $this->common->imageNameToUrl($params['data'], 'portrait_img', $this->folder);
            $this->common->videoNameToUrl($params['data'], 'content', $this->folder);

            return view('admin.reels.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function create()
    {
        try {
            $params['data'] = [];
            $params['channel'] = User::orderBy('channel_name', 'asc')->latest()->get();

            return view('admin.reels.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'channel_id' => 'required',
                'portrait_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'is_comment' => 'required',
                'is_like' => 'required',
                'video' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();
            $requestData['is_download'] = 0;

            $requestData['category_id'] = 0;
            $requestData['language_id'] = 0;
            $requestData['artist_id'] = 0;
            $hashtag_id = $this->common->checkHashTag($requestData['title']);
            $hashtagId = 0;
            if (count($hashtag_id) > 0) {
                $hashtagId = implode(',', $hashtag_id);
            }
            $requestData['hashtag_id'] = $hashtagId;
            $requestData['description'] = '';
            $files1 = $requestData['portrait_img'];
            $requestData['portrait_img'] = $this->common->saveImage($files1, $this->folder);
            $requestData['landscape_img'] = '';
            $requestData['content_type'] = 3;
            $requestData['content_upload_type'] = 'server_video';
            $setting_data = Setting_Data();
            if ($setting_data['sight_engine_status'] == 1) { // sight engine video Redaction

                $user_key = $setting_data['sight_engine_user_key'];
                $secret_key = $setting_data['sight_engine_secret_key'];
                $concepts = $setting_data['sight_engine_concepts'];

                $video = storage_path('app/public/content/' . $requestData['video']);

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
                                    $path = $this->folder . '/' . $filename;
                                    Storage::disk('public')->put($path, $video_get->body());

                                    // Delete the old video file
                                    $this->common->deleteImageToFolder($this->folder, $requestData['video']);
                                    $requestData['content'] = $filename;
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
                $requestData['content'] = $requestData['video'];
            }
            $requestData['content_size'] = $this->common->getFileSize($requestData['content'], $this->folder);
            $requestData['is_rent'] = 0;
            $requestData['rent_price'] = 0;
            $requestData['total_view'] = 0;
            $requestData['total_like'] = 0;
            $requestData['total_dislike'] = 0;
            $requestData['playlist_type'] = 0;
            $requestData['is_admin_added'] = 1;

            unset($requestData['video']);

            $video_data = Content::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($video_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_added')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function edit($id)
    {
        try {

            $params['data'] = Content::where('id', $id)->first();
            if ($params['data'] != null) {

                $params['channel'] = User::orderby('channel_name', 'asc')->latest()->get();

                $this->common->imageNameToUrl(array($params['data']), 'portrait_img', $this->folder);
                
                return view('admin.reels.edit', $params);
            } else {
                return redirect()->back()->with('error', __('Label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'channel_id' => 'required',
                'portrait_img' => 'image|mimes:jpeg,png,jpg|max:2048',
                'is_comment' => 'required',
                'is_like' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();
            $requestData['is_download'] = 0;

            $old_hashtag = explode(',', $requestData['old_hashtag_id']);
            Hashtag::whereIn('id', $old_hashtag)->decrement('total_used', 1);
            $hashtag_id = $this->common->checkHashTag($requestData['title']);
            $hashtagId = 0;
            if (count($hashtag_id) > 0) {
                $hashtagId = implode(',', $hashtag_id);
            }
            $requestData['hashtag_id'] = $hashtagId;

            if (isset($requestData['portrait_img'])) {
                $files = $requestData['portrait_img'];
                $requestData['portrait_img'] = $this->common->saveImage($files, $this->folder);
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_portrait_img']));
            }
            if ($requestData['video']) {
                $setting_data = Setting_Data();
                if ($setting_data['sight_engine_status'] == 1) { // sight engine video Redaction

                    $user_key = $setting_data['sight_engine_user_key'];
                    $secret_key = $setting_data['sight_engine_secret_key'];
                    $concepts = $setting_data['sight_engine_concepts'];

                    $video = storage_path('app/public/content/' . $requestData['video']);

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
                                        $path = $this->folder . '/' . $filename;
                                        Storage::disk('public')->put($path, $video_get->body());

                                        // Delete the old video file
                                        $this->common->deleteImageToFolder($this->folder, $requestData['video']);
                                        $requestData['content'] = $filename;
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
                    $requestData['content'] = $requestData['video'];
                }
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_content']));
                $requestData['content_size'] = $this->common->getFileSize($requestData['content'], $this->folder);
            }
            unset($requestData['video'], $requestData['old_hashtag_id'], $requestData['old_content'], $requestData['old_portrait_img']);

            $video_data = Content::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($video_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_edit_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_updated')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {

            $data = Content::where('id', $id)->first();
            if (isset($data)) {

                $old_hashtag = explode(',', $data['hashtag_id']);
                Hashtag::whereIn('id', $old_hashtag)->decrement('total_used', 1);

                $this->common->deleteImageToFolder($this->folder, $data['portrait_img']);
                $this->common->deleteImageToFolder($this->folder, $data['content']);
                $data->delete();

                // Content Releted Data Delete
                Comment::where('content_id', $id)->delete();
                Content_Report::where('content_id', $id)->delete();
                History::where('content_id', $id)->delete();
                Like::where('content_id', $id)->delete();
                Notification::where('content_id', $id)->delete();
                View::where('content_id', $id)->delete();
                Watch_later::where('content_id', $id)->delete();
            }

            return redirect()->route('reels.index')->with('success', __('Label.data_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    // Status Change
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
    // Save Chunk
    public function saveChunk()
    {
        @set_time_limit(5 * 60);

        $targetDir = storage_path('/app/public/content');
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
            $newFileName = 'vid' . date('_d_m_Y_') . rand(1111, 9999) . '.' . $extension; // Use the extracted extension
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
