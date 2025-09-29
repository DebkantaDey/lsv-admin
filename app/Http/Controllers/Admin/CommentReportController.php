<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Comment_Report;
use Illuminate\Http\Request;
use Exception;

class CommentReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            $params['data'] = [];

            if ($request->ajax()) {

                $data = Comment_Report::with('user', 'report_user', 'comment')->orderBy('id', 'desc')->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        if ($row->comment != null && $row->comment->status == 1) {

                            $id = $row->comment->id;
                            $status = $row->comment->status;
                            return "<button type='button' id='$status' onclick='change_status($id, $status)' style='background:#058f00; font-weight:bold; border: none; color: white; padding: 5px 15px; outline: none;border-radius: 5px;cursor: pointer;'>Show Comment</button>";
                        } else if ($row->comment != null && $row->comment->status == 0) {

                            $id = $row->comment->id;
                            $status = $row->comment->status;
                            return "<button type='button' id='$status' onclick='change_status($id, $status)' style='background:#e3000b; font-weight:bold; border: none; color: white; padding: 5px 20px; outline: none;border-radius: 5px;cursor: pointer;'>Hide Comment</button>";
                        } else {
                            return "-";
                        }
                    })
                    ->addColumn('date', function ($row) {
                        $date = date("Y-m-d", strtotime($row->created_at));
                        return $date;
                    })
                    ->make(true);
            }
            return view('admin.comment_report.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function changeStatus(Request $request)
    {
        try {

            $data = Comment::where('id', $request->id)->first();
            if ($data->status == 0) {
                $data->status = 1;
            } elseif ($data->status == 1) {
                $data->status = 0;
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
