<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Common;
use App\Models\Hashtag;
use App\Models\Post;
use App\Models\Post_Comment;
use App\Models\Post_Content;
use App\Models\Post_Like;
use App\Models\Post_Report;
use App\Models\Post_View;
use App\Models\User;
use CURLFile;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    private $folder = "post";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['data'] = [];

            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_channel = $request['input_channel'];

                if ($input_search != null && isset($input_search)) {

                    if ($input_channel != 0) {
                        $data = Post::where('channel_id', $input_channel)->where('title', 'LIKE', "%{$input_search}%")->with('channel')->orderBy('id', 'DESC')->get();
                    } else {
                        $data = Post::where('title', 'LIKE', "%{$input_search}%")->with('channel')->orderBy('id', 'DESC')->get();
                    }
                } else {

                    if ($input_channel != 0) {
                        $data = Post::where('channel_id', $input_channel)->with('channel')->orderBy('id', 'DESC')->get();
                    } else {
                        $data = Post::orderBy('id', 'DESC')->with('channel')->get();
                    }
                }

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function ($row) {
                        if ($row->status == 1) {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id, $row->status)' style='background:#058f00; font-weight:bold;  border:none; color: white; outline: none;'>Show</button>";
                        } else {
                            return "<button id='$row->id' onclick='change_status($row->id, $row->status)' style='background:#e3000b; font-weight:bold;  border:none; color: white; outline: none;'>Hide</button>";
                        }
                    })
                    ->addColumn('action', function ($row) {
                        $delete = '<form onsubmit="return confirm(\'Are you sure !!! You want to Delete this Post ?\');" method="POST"  action="' . route('post.destroy', [$row->id]) . '">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="edit-delete-btn" style="outline: none;" title="Delete"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around" title="Edit">';
                        $btn .= '<a href="' . route('post.edit', [$row->id]) . '" class="edit-delete-btn">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '<a href="' . route('postcontent.index', [$row->id]) . '" class="btn text-white p-1 font-weight-bold" title="Post Content" style="background: #4e45b8;"><i class="fa-regular fa-image fa-xl"></i></a>';
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('date', function ($row) {
                        $date = date("Y-m-d", strtotime($row->created_at));
                        return $date;
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            }

            $params['channel'] = User::orderby('channel_name', 'asc')->latest()->get();

            return view('admin.post.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function create()
    {
        try {
            $params['data'] = [];
            $params['channel'] = User::orderby('channel_name', 'asc')->latest()->get();

            return view('admin.post.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'channel_id' => 'required',
                'title' => 'required|min:2',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $hashtag_id = $this->common->checkHashTag($requestData['title']);
            $hashtagId = 0;
            if (count($hashtag_id) > 0) {
                $hashtagId = implode(',', $hashtag_id);
            }
            $requestData['hashtag_id'] = $hashtagId;

            $requestData['descripation'] = isset($request->descripation) ? $request->descripation : '';
            $requestData['category_id'] = 0;
            $requestData['view'] = 0;

            $video_data = Post::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($video_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_added')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show(string $id)
    {
        try {
            $data = Post::where('id', $id)->first();
            if ($data->status == 0) {
                $data->status = 1;
            } elseif ($data->status == 1) {
                $data->status = 0;
            } else {
                $data->status = 0;
            }
            $data->save();
            return response()->json(array('status' => 200, 'success' => 'Status Changed', 'id' => $data->id, 'Status_Code' => $data->status));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function edit(string $id)
    {
        try {
            $params['data'] = Post::where('id', $id)->first();
            if ($params['data'] != null) {

                $params['channel'] = User::orderby('channel_name', 'asc')->latest()->get();

                return view('admin.post.edit', $params);
            } else {
                return redirect()->back()->with('error', __('Label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'channel_id' => 'required',
                'title' => 'required|min:2',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $old_hashtag = explode(',', $requestData['old_hashtag_id']);
            Hashtag::whereIn('id', $old_hashtag)->decrement('total_used', 1);

            $hashtag_id = $this->common->checkHashTag($requestData['title']);
            $hashtagId = 0;
            if (count($hashtag_id) > 0) {
                $hashtagId = implode(',', $hashtag_id);
            }
            $requestData['hashtag_id'] = $hashtagId;

            $requestData['descripation'] = isset($request->descripation) ? $request->descripation : '';

            unset($requestData['old_hashtag_id']);

            $video_data = Post::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($video_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_edit_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_updated')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function destroy(string $id)
    {
        try {

            $data = Post::where('id', $id)->first();

            if (isset($data)) {

                $old_hashtag = explode(',', $data['hashtag_id']);
                Hashtag::whereIn('id', $old_hashtag)->decrement('total_used', 1);

                $post_content = Post_Content::where('post_id', $data['id'])->get();
                foreach ($post_content as $post) {
                    $this->common->deleteImageToFolder($this->folder, $post['content_url']);
                    $this->common->deleteImageToFolder($this->folder, $post['thumbnail_image']);
                    $post->delete();
                }
                $this->common->Delete_All_Data($data['id']);
                $data->delete();
            }
            return redirect(route('post.index'))->with('success', __('Label.data_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    // Save Chunk
    public function saveChunk()
    {
        @set_time_limit(5 * 60);

        $targetDir = storage_path('/app/public/post');
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
    public function getpostcontent(Request $request)
    {
        try {

            $post_id = $request['id'];

            $params['data'] = [];
            $data = Post_Content::where('post_id', $post_id)->paginate(15);

            if ($data != null) {

                for ($i = 0; $i < count($data); $i++) {

                    if ($data[$i]['content_type'] == 1) {
                        $data[$i]['image'] = $this->common->getImage($this->folder, $data[$i]['content_url']);
                    } else {
                        $data[$i]['video'] = $this->common->getVideo($this->folder, $data[$i]['content_url']);
                    }
                }
            }

            $params['data'] = $data;
            $params['post'] = Post::where('id', $post_id)->first();

            return view('admin.post.add_content', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function postcontentstore(Request $request)
    {
        try {
            if ($request->content_type == 1) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'video' => 'required',
                ]);
            }
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $requestData['thumbnail_image'] = "";
            if ($requestData['content_type'] == 2) {

                $setting_data = Setting_Data();
                if ($setting_data['sight_engine_status'] == 1) { // sight engine video Redaction

                    $user_key = $setting_data['sight_engine_user_key'];
                    $secret_key = $setting_data['sight_engine_secret_key'];
                    $concepts = $setting_data['sight_engine_concepts'];

                    $video = storage_path('app/public/post/' . $requestData['video']);

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
                                        $requestData['content_url'] = $filename;
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
                    $requestData['content_url'] = $requestData['video'];
                }

                $requestData['thumbnail_image'] = $this->common->getimagefromvideo($requestData['content_url']);
            }

            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['content_url'] = $this->common->saveImage($files, $this->folder);
            }

            unset($requestData['video'], $requestData['image']);

            $video_data = Post_Content::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($video_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_added')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function postcontentdelete(Request $request)
    {
        try {

            $id = $request->id;
            $data = Post_Content::where('id', $id)->first();

            if (isset($data)) {

                $this->common->deleteImageToFolder($this->folder, $data['content_url']);
                $this->common->deleteImageToFolder($this->folder, $data['thumbnail_image']);
                $data->delete();
            }
            return response()->json(array('status' => 200, 'success' => __('Label.data_delete_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
