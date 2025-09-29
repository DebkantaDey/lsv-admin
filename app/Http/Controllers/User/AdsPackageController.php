<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ads_Package;
use App\Models\Common;
use Illuminate\Http\Request;
use Exception;

class AdsPackageController extends Controller
{
    private $folder = "package";
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
                if ($input_search != null && isset($input_search)) {
                    $data = Ads_Package::where('name', 'LIKE', "%{$input_search}%")->latest()->get();
                } else {
                    $data = Ads_Package::latest()->get();
                }

                // Image Name to URL
                $this->common->imageNameToUrl($data, 'image', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->make(true);
            }
            return view('user.ads_package.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
