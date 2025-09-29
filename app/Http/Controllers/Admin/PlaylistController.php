<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Content;
use App\Models\Playlist_Content;
use App\Models\User;
use App\Models\View;
use App\Models\Watch_later;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class PlaylistController extends Controller
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
            $params['channel'] = User::orderby('channel_name', 'asc')->latest()->get();

            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_channel = $request['input_channel'];
                $input_type = $request['input_type'];

                if ($input_search != null && isset($input_search)) {
                    if ($input_channel != 0) {

                        if ($input_type == 1 || $input_type == 2) {
                            $data = Content::where('title', 'LIKE', "%{$input_search}%")->where('playlist_type', $input_type)->where('content_type', 5)->where('channel_id', $input_channel)->with('channel')->latest()->get();
                        } else {
                            $data = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 5)->where('channel_id', $input_channel)->with('channel')->latest()->get();
                        }
                    } else {

                        if ($input_type == 1 || $input_type == 2) {
                            $data = Content::where('title', 'LIKE', "%{$input_search}%")->where('playlist_type', $input_type)->where('content_type', 5)->with('channel')->latest()->get();
                        } else {
                            $data = Content::where('title', 'LIKE', "%{$input_search}%")->where('content_type', 5)->with('channel')->latest()->get();
                        }
                    }
                } else {
                    if ($input_channel != 0) {

                        if ($input_type == 1 || $input_type == 2) {
                            $data = Content::where('channel_id', $input_channel)->where('playlist_type', $input_type)->where('content_type', 5)->with('channel')->latest()->get();
                        } else {
                            $data = Content::where('channel_id', $input_channel)->where('content_type', 5)->with('channel')->latest()->get();
                        }
                    } else {

                        if ($input_type == 1 || $input_type == 2) {
                            $data = Content::latest()->where('playlist_type', $input_type)->where('content_type', 5)->with('channel')->get();
                        } else {
                            $data = Content::latest()->where('content_type', 5)->with('channel')->get();
                        }
                    }
                }

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $delete = ' <form onsubmit="return confirm(\'Are you sure !!! You want to Delete this Playlist ?\');" method="POST"  action="' . route('playlist.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="edit-delete-btn" style="outline: none;" title="Delete"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around" title="Edit">';
                        $btn .= '<a class="edit-delete-btn edit_playlist" title="Edit" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-channel_id="' . $row->channel_id . '" data-title="' . $row->title . '" data-description="' . $row->description . '" data-playlist_type="' . $row->playlist_type . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {
                        if ($row->status == 1) {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id, $row->status)' style='background:#058f00; font-weight:bold; border: none; color: white; padding: 5px 15px; outline: none;border-radius: 5px;cursor: pointer;'>Show</button>";
                        } else {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id, $row->status)' style='background:#e3000b; font-weight:bold; border: none; color: white; padding: 5px 20px; outline: none;border-radius: 5px;cursor: pointer;'>Hide</button>";
                        }
                    })
                    ->addColumn('content', function ($row) {
                        $btn = '<a href="' . route('playlist.content.index', $row->id) . '" class="btn text-white p-1 font-weight-bold" style="background:#4e45b8;">Content List</a> ';
                        return $btn;
                    })
                    ->rawColumns(['action', 'content', 'status'])
                    ->make(true);
            }
            return view('admin.playlist.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|min:2',
                'channel_id' => 'required',
                'playlist_type' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $requestData['category_id'] = 0;
            $requestData['language_id'] = 0;
            $requestData['artist_id'] = 0;
            $requestData['hashtag_id'] = 0;
            $requestData['description'] = isset($requestData['description']) ? $requestData['description'] : '';
            $requestData['portrait_img'] = '';
            $requestData['landscape_img'] = '';
            $requestData['content_type'] = 5;
            $requestData['content_upload_type'] = '';
            $requestData['content'] = '';
            $requestData['content_size'] = '';
            $requestData['is_rent'] = 0;
            $requestData['rent_price'] = 0;
            $requestData['is_comment'] = 0;
            $requestData['is_download'] = 0;
            $requestData['is_like'] = 0;
            $requestData['total_view'] = 0;
            $requestData['total_like'] = 0;
            $requestData['total_dislike'] = 0;
            $requestData['is_admin_added'] = 1;
            $requestData['status'] = 1;

            $content_data = Content::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($content_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_added')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'playlist_type' => 'required',
                'title' => 'required|min:2',
                'channel_id' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $requestData['description'] = isset($requestData['description']) ? $requestData['description'] : '';

            $content_data = Content::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($content_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_edit_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_updated')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function destroy($id)
    {
        try {
            Content::where('id', $id)->delete();
            Playlist_Content::where('playlist_id', $id)->delete();

            // Content Releted Data Delete
            View::where('content_id', $id)->delete();
            Watch_later::where('content_id', $id)->delete();

            return redirect()->route('playlist.index')->with('success', __('Label.data_delete_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {

            $data = Content::where('id', $id)->first();
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

    // Content
    public function PlaylistIndex($id, Request $request)
    {
        try {

            $params['data'] = [];
            $params['playlist_id'] = $id;

            $params['data'] = Playlist_Content::where('playlist_id', $id)
                ->with(['content' => function ($query) {
                    $query->select('id', 'title', 'portrait_img', 'channel_id');
                }])
                ->orderBy('sortable', 'asc')->latest()->get();

            for ($i = 0; $i < count($params['data']); $i++) {
                if ($params['data'][$i]['content'] != null) {
                    $this->common->imageNameToUrl(array($params['data'][$i]['content']), 'portrait_img', $this->folder);
                }
            }

            $check = Content::select('id', 'title')->where('id', $id)->first();
            if ($check != null) {

                $params['playlist_name'] = $check['title'];
                return view('admin.playlist.ct_index', $params);
            } else {
                return redirect()->route('playlist.index')->with('errors', __('Label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function GetContentData(Request $request)
    {
        try {
            $content_type = $request['content_type'];
            $playlist_id = $request['playlist_id'];

            $ids_array = Playlist_Content::select('content_id')->where('playlist_id', $playlist_id)->where('content_type', $content_type)->get()->toArray();
            $data = Content::select('id', 'title')->whereNotIn('id', $ids_array)->where('content_type', $content_type)->where('status', 1)->where('is_rent', 0)->latest()->get();

            if (isset($data) && $data != null) {
                return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'data' => $data));
            } else {
                return response()->json(array('status' => 400, 'errors' => "Data Not Found"));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function PlaylistSave(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content_type' => 'required',
                'content' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();
            $channel_id = Content::select('channel_id')->where('id', $requestData['playlist_id'])->first();

            for ($i = 0; $i < count($requestData['content']); $i++) {

                $insert = new Playlist_Content();
                $insert['channel_id'] = $channel_id['channel_id'];
                $insert['playlist_id'] = $requestData['playlist_id'];
                $insert['content_id'] = $requestData['content'][$i];
                $insert['content_type'] = $requestData['content_type'];
                $insert['sortable'] = 1;
                $insert->save();
            }
            return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function PlaylistDelete(Request $request)
    {
        try {

            Playlist_Content::where('id', $request->id)->delete();
            return response()->json(array('status' => 200, 'success' => __('Label.data_delete_successfully'), 'id' => $request->id));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function PlaylistSortable(Request $request)
    {
        try {

            $ids = $request['ids'];
            if (isset($ids) && $ids != null && $ids != "") {

                for ($i = 0; $i < count($ids); $i++) {
                    Playlist_Content::where('id', $ids[$i])->update(['sortable' => $i + 1]);
                }
            }
            return response()->json(array('status' => 200, 'success' => __('Label.data_edit_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
