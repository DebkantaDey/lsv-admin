<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Comment;
use App\Models\Common;
use App\Models\Content;
use App\Models\History;
use App\Models\Radio_Content;
use App\Models\View;
use App\Models\Watch_later;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class RadioController extends Controller
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
            $params['artist'] = Artist::latest()->get();

            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_artist = $request['input_artist'];

                $query = Content::where('content_type', 6)->with('artist');
                if (!empty($input_search)) {
                    $query->where('title', 'LIKE', "%{$input_search}%");
                }
                if ($input_artist != 0) {
                    $query->where('artist_id', $input_artist);
                }
                $data = $query->latest()->get();

                $this->common->imageNameToUrl($data, 'portrait_img', $this->folder);
                $this->common->imageNameToUrl($data, 'landscape_img', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $delete = ' <form onsubmit="return confirm(\'Are you sure !!! You want to Delete this Radio ?\');" method="POST"  action="' . route('radio.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="edit-delete-btn" style="outline: none;" title="Delete"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around" title="Edit">';
                        $btn .= '<a class="edit-delete-btn edit_radio" title="Edit" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-artist_id="' . $row->artist_id . '" data-title="' . $row->title . '" data-description="' . $row->description . '" data-portrait_img="' . $row->portrait_img . '" data-landscape_img="' . $row->landscape_img . '">';
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
                        $btn = '<a href="' . route('radio.content.index', $row->id) . '" class="btn text-white p-1 font-weight-bold" style="background:#4e45b8;">Content List</a> ';
                        return $btn;
                    })
                    ->rawColumns(['action', 'content', 'status'])
                    ->make(true);
            }
            return view('admin.radio.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|min:2',
                'artist_id' => 'required',
                'portrait_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'landscape_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $requestData['content_type'] = 6;
            $requestData['channel_id'] = 0;
            $requestData['category_id'] = 0;
            $requestData['language_id'] = 0;
            $requestData['hashtag_id'] = 0;
            $requestData['description'] = isset($requestData['description']) ? $requestData['description'] : '';
            $files1 = $requestData['portrait_img'];
            $files2 = $requestData['landscape_img'];
            $requestData['portrait_img'] = $this->common->saveImage($files1, $this->folder);
            $requestData['landscape_img'] = $this->common->saveImage($files2, $this->folder);
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
            $requestData['playlist_type'] = 0;
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
                'title' => 'required|min:2',
                'artist_id' => 'required',
                'portrait_img' => 'image|mimes:jpeg,png,jpg|max:2048',
                'landscape_img' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $requestData['category_id'] = 0;
            $requestData['language_id'] = 0;
            $requestData['description'] = isset($requestData['description']) ? $requestData['description'] : '';
            if (isset($requestData['portrait_img'])) {
                $files = $requestData['portrait_img'];
                $requestData['portrait_img'] = $this->common->saveImage($files, $this->folder);
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_portrait_img']));
            }
            if (isset($requestData['landscape_img'])) {
                $files1 = $requestData['landscape_img'];
                $requestData['landscape_img'] = $this->common->saveImage($files1, $this->folder);
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_landscape_img']));
            }
            unset($requestData['old_portrait_img'], $requestData['old_landscape_img']);

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

            $data = Content::where('id', $id)->first();
            if (isset($data) && $data != null) {
                $this->common->deleteImageToFolder($this->folder, $data['portrait_img']);
                $this->common->deleteImageToFolder($this->folder, $data['landscape_img']);
                $data->delete();

                Radio_Content::where('radio_id', $id)->delete();

                // Content Releted Data Delete
                Comment::where('content_id', $id)->delete();
                History::where('content_id', $id)->delete();
                View::where('content_id', $id)->delete();
                Watch_later::where('content_id', $id)->delete();
            }

            return redirect()->route('radio.index')->with('success', __('Label.data_delete_successfully'));
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
    public function RadioIndex($id, Request $request)
    {
        try {

            $params['data'] = [];
            $params['radio_id'] = $id;

            $ids_array = Radio_Content::select('content_id')->where('radio_id', $id)->get()->toArray();
            $params['content'] = Content::select('id', 'title')->whereNotIn('id', $ids_array)->where('content_type', 2)->where('status', 1)->where('is_rent', 0)->latest()->get();

            $params['data'] = Radio_Content::where('radio_id', $id)
                ->with(['content' => function ($query) {
                    $query->select('id', 'title', 'portrait_img');
                }])
                ->orderBy('sortable', 'asc')->latest()->get();

            for ($i = 0; $i < count($params['data']); $i++) {
                if ($params['data'][$i]['content'] != null) {
                    $this->common->imageNameToUrl(array($params['data'][$i]['content']), 'portrait_img', $this->folder);
                }
            }

            $check = Content::select('id', 'title')->where('id', $id)->first();
            if ($check != null) {

                $params['radio_name'] = $check['title'];
                return view('admin.radio.ct_index', $params);
            } else {
                return redirect()->route('radio.index')->with('errors', __('Label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function RadioSave(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'content' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();
            for ($i = 0; $i < count($requestData['content']); $i++) {

                $insert = new Radio_Content();
                $insert['radio_id'] = $requestData['radio_id'];
                $insert['content_id'] = $requestData['content'][$i];
                $insert['sortable'] = 1;
                $insert->save();
            }
            return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function RadioDelete(Request $request)
    {
        try {

            Radio_Content::where('id', $request->id)->delete();
            return response()->json(array('status' => 200, 'success' => __('Label.data_delete_successfully'), 'id' => $request->id));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function RadioSortable(Request $request)
    {
        try {
            $ids = $request['ids'];

            if (isset($ids) && $ids != null && $ids != "") {

                for ($i = 0; $i < count($ids); $i++) {
                    Radio_Content::where('id', $ids[$i])->update(['sortable' => $i + 1]);
                }
            }
            return response()->json(array('status' => 200, 'success' => __('Label.data_edit_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
