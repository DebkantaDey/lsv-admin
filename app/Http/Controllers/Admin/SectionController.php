<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Common;
use App\Models\Language;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

// 1- Music, 2- Podcasts, 3- Radio, 4- Playlist, 5- Category, 6- Language	

class SectionController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $params['data'] = [];
            $params['language'] = Language::latest()->get();
            $params['artist'] = Artist::latest()->get();
            $params['category'] = Category::where('type', 2)->latest()->get();

            return view('admin.section.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'is_home_screen' => 'required',
                'content_type' => 'required',
                'screen_layout' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            if ($request['content_type'] == 1 || $request['content_type'] == 2 || $request['content_type'] == 3 || $request['content_type'] == 4) {
                $validator1 = Validator::make($request->all(), [
                    'no_of_content' => 'required|numeric|min:1'
                ]);
                if ($validator1->fails()) {
                    $errs1 = $validator1->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs1));
                }
            }

            $requestData = $request->all();
            $requestData['short_title'] = isset($request->short_title) ? $request->short_title : '';

            $requestData['category_id'] = 0;
            $requestData['language_id'] = 0;
            $requestData['artist_id'] = 0;
            $requestData['no_of_content'] = 0;
            $requestData['order_by_upload'] = 0;
            $requestData['order_by_view'] = 0;
            $requestData['order_by_like'] = 0;
            $requestData['view_all'] = 0;
            $requestData['is_admin_added'] = 1;

            if ($requestData['content_type'] == 1) {

                $requestData['category_id'] = isset($request->category_id) ? $request->category_id : 0;
                $requestData['language_id'] = isset($request->language_id) ? $request->language_id : 0;
                $requestData['artist_id'] = isset($request->artist_id) ? $request->artist_id : 0;
                $requestData['order_by_upload'] = isset($request->order_by_upload) ? $request->order_by_upload : 0;
                $requestData['order_by_view'] = isset($request->order_by_view) ? $request->order_by_view : 0;
                $requestData['order_by_like'] = isset($request->order_by_like) ? $request->order_by_like : 0;
                $requestData['no_of_content'] = isset($request->no_of_content) ? $request->no_of_content : 0;
                $requestData['view_all'] = isset($request->view_all) ? $request->view_all : 0;
            } elseif ($requestData['content_type'] == 2) {

                $requestData['category_id'] = isset($request->category_id) ? $request->category_id : 0;
                $requestData['language_id'] = isset($request->language_id) ? $request->language_id : 0;
                $requestData['order_by_upload'] = isset($request->order_by_upload) ? $request->order_by_upload : 0;
                $requestData['order_by_view'] = isset($request->order_by_view) ? $request->order_by_view : 0;
                $requestData['order_by_like'] = isset($request->order_by_like) ? $request->order_by_like : 0;
                $requestData['no_of_content'] = isset($request->no_of_content) ? $request->no_of_content : 0;
                $requestData['view_all'] = isset($request->view_all) ? $request->view_all : 0;
            } elseif ($requestData['content_type'] == 3) {

                $requestData['category_id'] = isset($request->category_id) ? $request->category_id : 0;
                $requestData['language_id'] = isset($request->language_id) ? $request->language_id : 0;
                $requestData['artist_id'] = isset($request->artist_id) ? $request->artist_id : 0;
                $requestData['order_by_upload'] = isset($request->order_by_upload) ? $request->order_by_upload : 0;
                $requestData['no_of_content'] = isset($request->no_of_content) ? $request->no_of_content : 0;
                $requestData['view_all'] = isset($request->view_all) ? $request->view_all : 0;
            } elseif ($requestData['content_type'] == 4) {

                $requestData['order_by_upload'] = isset($request->order_by_upload) ? $request->order_by_upload : 0;
                $requestData['no_of_content'] = isset($request->no_of_content) ? $request->no_of_content : 0;
                $requestData['view_all'] = isset($request->view_all) ? $request->view_all : 0;
            }

            $section_data = Section::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($section_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_added')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function GetSectionData(Request $request)
    {
        try {
            if ($request->is_home_screen == 1) {

                $data = Section::where('is_home_screen', 1)->orderBy('sortable', 'asc')->latest()->get();
                return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $data));
            } else if ($request->is_home_screen == 2) {

                if ($request->content_type == 1 || $request->content_type == 4) {
                    $data = Section::where('is_home_screen', 2)->whereIn('content_type', [1, 4])->orderBy('sortable', 'asc')->latest()->get();
                } else {
                    $data = Section::where('is_home_screen', 2)->where('content_type', $request->content_type)->orderBy('sortable', 'asc')->latest()->get();
                }

                return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $data));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function SectionDataEdit(Request $request)
    {
        try {

            $data = Section::where('id', $request['id'])->first();
            return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $data));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'is_home_screen' => 'required',
                'content_type' => 'required',
                'screen_layout' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            if ($request['content_type'] == 1 || $request['content_type'] == 2 || $request['content_type'] == 3 || $request['content_type'] == 4) {
                $validator1 = Validator::make($request->all(), [
                    'no_of_content' => 'required|numeric|min:1'
                ]);
                if ($validator1->fails()) {
                    $errs1 = $validator1->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs1));
                }
            }

            $requestData = $request->all();
            $requestData['short_title'] = isset($request->short_title) ? $request->short_title : '';

            $requestData['category_id'] = 0;
            $requestData['language_id'] = 0;
            $requestData['artist_id'] = 0;
            $requestData['no_of_content'] = 0;
            $requestData['order_by_upload'] = 0;
            $requestData['order_by_view'] = 0;
            $requestData['order_by_like'] = 0;
            $requestData['view_all'] = 0;
            $requestData['is_admin_added'] = 1;

            if ($requestData['content_type'] == 1) {

                $requestData['category_id'] = isset($request->category_id) ? $request->category_id : 0;
                $requestData['language_id'] = isset($request->language_id) ? $request->language_id : 0;
                $requestData['artist_id'] = isset($request->artist_id) ? $request->artist_id : 0;
                $requestData['no_of_content'] = isset($request->no_of_content) ? $request->no_of_content : 0;
                $requestData['order_by_upload'] = isset($request->order_by_upload) ? $request->order_by_upload : 0;
                $requestData['order_by_view'] = isset($request->order_by_view) ? $request->order_by_view : 0;
                $requestData['order_by_like'] = isset($request->order_by_like) ? $request->order_by_like : 0;
                $requestData['view_all'] = isset($request->view_all) ? $request->view_all : 0;
            } elseif ($requestData['content_type'] == 2) {

                $requestData['category_id'] = isset($request->category_id) ? $request->category_id : 0;
                $requestData['language_id'] = isset($request->language_id) ? $request->language_id : 0;
                $requestData['no_of_content'] = isset($request->no_of_content) ? $request->no_of_content : 0;
                $requestData['order_by_upload'] = isset($request->order_by_upload) ? $request->order_by_upload : 0;
                $requestData['order_by_view'] = isset($request->order_by_view) ? $request->order_by_view : 0;
                $requestData['order_by_like'] = isset($request->order_by_like) ? $request->order_by_like : 0;
                $requestData['view_all'] = isset($request->view_all) ? $request->view_all : 0;
            } elseif ($requestData['content_type'] == 3) {

                $requestData['category_id'] = isset($request->category_id) ? $request->category_id : 0;
                $requestData['language_id'] = isset($request->language_id) ? $request->language_id : 0;
                $requestData['artist_id'] = isset($request->artist_id) ? $request->artist_id : 0;
                $requestData['no_of_content'] = isset($request->no_of_content) ? $request->no_of_content : 0;
                $requestData['order_by_upload'] = isset($request->order_by_upload) ? $request->order_by_upload : 0;
                $requestData['view_all'] = isset($request->view_all) ? $request->view_all : 0;
            } elseif ($requestData['content_type'] == 4) {

                $requestData['no_of_content'] = isset($request->no_of_content) ? $request->no_of_content : 0;
                $requestData['order_by_upload'] = isset($request->order_by_upload) ? $request->order_by_upload : 0;
                $requestData['view_all'] = isset($request->view_all) ? $request->view_all : 0;
            }

            $section_data = Section::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($section_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('Label.data_add_successfully')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('Label.data_not_added')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {

            $data = Section::where('id', $id)->delete();
            return response()->json(array('status' => 200, 'success' => __('Label.data_delete_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    // Sortable
    public function SectionSortable(Request $request)
    {
        try {
            if ($request->is_home_screen == 1) {

                $data = Section::select('id', 'title')->where('is_home_screen', 1)->orderBy('sortable', 'asc')->latest()->get();
                return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $data));
            } else if ($request->is_home_screen == 2) {

                if ($request->content_type == 1 || $request->content_type == 4) {
                    $data = Section::select('id', 'title')->where('is_home_screen', 2)->whereIn('content_type', [1, 4])->orderBy('sortable', 'asc')->latest()->get();
                } else {
                    $data = Section::select('id', 'title')->where('is_home_screen', 2)->where('content_type', $request->content_type)->orderBy('sortable', 'asc')->latest()->get();
                }

                return response()->json(array('status' => 200, 'success' => 'Data Get Successfully', 'result' => $data));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function SectionSortableSave(Request $request)
    {
        try {

            $ids = $request['ids'];

            if (isset($ids) && $ids != null && $ids != "") {

                $id_array = explode(',', $ids);
                for ($i = 0; $i < count($id_array); $i++) {
                    Section::where('id', $id_array[$i])->update(['sortable' => $i + 1]);
                }
            }

            return response()->json(array('status' => 200, 'success' => __('Label.data_edit_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
