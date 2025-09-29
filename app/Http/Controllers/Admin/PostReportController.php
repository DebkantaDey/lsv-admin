<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Post_Report;
use Illuminate\Http\Request;
use Exception;

class PostReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            $params['data'] = [];
            if ($request->ajax()) {

                $data = Post_Report::with('report_user', 'post')->latest()->get();
                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function ($row) {
                        if ($row->post && $row->post->status == 1) {
                            return "<button type='button' id='$row->id' onclick='changestatus(\" $row->post_id \", \"0\")'  style='background:#058f00; color:#fff; padding: 4px; font-weight:bold;  border: none; outline: none;'>Show</button>";
                        } else if ($row->post) {
                            return "<button type='button'  id='$row->id' onclick='changestatus(\" $row->post_id \", \"1\")'  style='background:#e3000b; color:#fff; padding: 4px; font-weight:bold; border: none; outline: none;'>Hide</button>";
                        }
                    })
                    ->rawColumns(['status'])
                    ->make(true);
            }
            return view('admin.post_report.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function changeStatus(Request $request)
    {
        try {

            $id = $request->id;
            $status = $request->status;
            $data = Post::where('id', $id)->first();
            if (isset($data)) {
                $data->update(['status' => $status]);
            }
            return response()->json(array('status' => 200, 'success' => __('label.status_changed')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
