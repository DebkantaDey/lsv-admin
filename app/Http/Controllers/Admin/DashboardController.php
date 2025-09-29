<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ads_Package;
use App\Models\Ads_Transaction;
use App\Models\Artist;
use App\Models\Common;
use App\Models\User;
use App\Models\Page;
use App\Models\Category;
use App\Models\Content;
use App\Models\Hashtag;
use App\Models\Language;
use App\Models\Package;
use App\Models\Rent_Transaction;
use App\Models\Subscriber;
use App\Models\Transaction;
use App\Models\Withdrawal_Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class DashboardController extends Controller
{
    private $folder_content = "content";
    private $folder_artist = "artist";
    private $folder_user = "user";
    private $folder_category = "category";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {

            // Package Expiry
            $this->common->package_expiry();

            // First Card
            $params['UserCount'] = User::count();
            $params['CategoryCount'] = Category::count();
            $params['LanguageCount'] = Language::count();
            $params['ArtistCount'] = Artist::count();
            $params['PlaylistCount'] = Content::where('content_type', 5)->count();
            // Second Card
            $params['VideoCount'] = Content::where('content_type', 1)->count();
            $params['MusicCount'] = Content::where('content_type', 2)->count();
            $params['ReelsCount'] = Content::where('content_type', 3)->count();
            $params['PodcastsCount'] = Content::where('content_type', 4)->count();
            $params['RadioCount'] = Content::where('content_type', 6)->count();

            // User Statistice
            $user_data = [];
            $user_month = [];
            $d = date('t', mktime(0, 0, 0, date('m'), 1, date('Y')));

            for ($i = 1; $i < 13; $i++) {
                $Sum = User::whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->count();
                $user_data['sum'][] = (int) $Sum;
            }
            for ($i = 1; $i <= $d; $i++) {

                $Sum = User::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereDay('created_at', $i)->count();
                $user_month['sum'][] = (int) $Sum;
            }
            $params['user_year'] = json_encode($user_data);
            $params['user_month'] = json_encode($user_month);

            // Most Subscriber
            $params['top_subscriber'] = Subscriber::select('to_user_id', 'to_user_id as user_id', DB::raw('count(*) as total_subscriber'))->where('type', 1)
                ->groupBy('to_user_id')->orderBy('total_subscriber', 'desc')->with('to_user')->take(5)->get();
            for ($i = 0; $i < count($params['top_subscriber']); $i++) {
                if ($params['top_subscriber'][$i]['to_user'] != null && isset($params['top_subscriber'][$i]['to_user'])) {
                    $this->common->imageNameToUrl(array($params['top_subscriber'][$i]['to_user']), 'image', $this->folder_user);
                }
            }

            // Most Like Content
            $params['top_video_like'] = Content::where('content_type', 1)->orderBy('total_like', 'desc')->where('status', 1)->take(5)->get();
            $params['top_music_like'] = Content::where('content_type', 2)->orderBy('total_like', 'desc')->where('status', 1)->take(5)->get();
            $params['top_reels_like'] = Content::where('content_type', 3)->orderBy('total_like', 'desc')->where('status', 1)->take(5)->get();
            $params['top_podcasts_like'] = Content::where('content_type', 4)->orderBy('total_like', 'desc')->where('status', 1)->take(5)->get();
            $this->common->imageNameToUrl($params['top_video_like'], 'portrait_img', $this->folder_content);
            $this->common->imageNameToUrl($params['top_music_like'], 'portrait_img', $this->folder_content);
            $this->common->imageNameToUrl($params['top_reels_like'], 'portrait_img', $this->folder_content);
            $this->common->imageNameToUrl($params['top_podcasts_like'], 'portrait_img', $this->folder_content);

            // Best Category
            $params['best_category'] = Category::orderBy('id', 'desc')->take(8)->get();
            $this->common->imageNameToUrl($params['best_category'], 'image', $this->folder_category);

            // Most View Content
            $params['top_video_view'] = Content::where('content_type', 1)->orderBy('total_view', 'desc')->where('status', 1)->take(5)->get();
            $params['top_music_view'] = Content::where('content_type', 2)->orderBy('total_view', 'desc')->where('status', 1)->take(5)->get();
            $params['top_reels_view'] = Content::where('content_type', 3)->orderBy('total_view', 'desc')->where('status', 1)->take(5)->get();
            $params['top_podcasts_view'] = Content::where('content_type', 4)->orderBy('total_view', 'desc')->where('status', 1)->take(5)->get();
            $this->common->imageNameToUrl($params['top_video_view'], 'portrait_img', $this->folder_content);
            $this->common->imageNameToUrl($params['top_music_view'], 'portrait_img', $this->folder_content);
            $this->common->imageNameToUrl($params['top_reels_view'], 'portrait_img', $this->folder_content);
            $this->common->imageNameToUrl($params['top_podcasts_view'], 'portrait_img', $this->folder_content);

            // Best Language
            // $params['best_language'] = Language::orderBy('id', 'desc')->take(8)->get();
            // $this->common->imageNameToUrl($params['best_language'], 'image', $this->folder_language);

            // Most Used Hashtag
            $params['most_used_hashtag'] = Hashtag::orderBy('total_used', 'desc')->take(7)->get();

            // Most Famous Artist
            $params['top_artist'] = [];
            $subscrib_artist = Subscriber::select(DB::raw("count(*) as total_count"), 'to_user_id')->where('type', 2)->where('status', 1)
                ->groupBy('to_user_id')->orderBy('total_count', 'desc')->with('artist')->get();
            $q = 0;
            for ($i = 0; $i < count($subscrib_artist); $i++) {
                if ($subscrib_artist[$i]['artist'] != null) {

                    $this->common->imageNameToUrl(array($subscrib_artist[$i]['artist']), 'image', $this->folder_artist);

                    $params['top_artist'][] = $subscrib_artist[$i];
                    $q = $q + 1;
                    if ($q == 10) {
                        break;
                    }
                }
            }

            return view('admin.dashboard.dashboard', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function earningDashboard()
    {
        try {

            // First Card
            $params['CurrentMounthCount'] = Transaction::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('price');
            $params['TransactionCount'] = Transaction::sum('price');
            $params['PackageCount'] = Package::count();
            $params['CurrentMounthRentCount'] = Rent_Transaction::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('admin_commission');
            $params['RentTransactionCount'] = Rent_Transaction::sum('admin_commission');
            // Second Card
            $params['CurrentMounthAdsCount'] = Ads_Transaction::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('price');
            $params['AdsTransactionCount'] = Ads_Transaction::sum('price');
            $params['AdsPackageCount'] = Ads_Package::count();
            $params['PendingWithdrawalCount'] = Withdrawal_Request::where('status', 0)->sum('amount');
            $params['CompletedWithdrawalCount'] = Withdrawal_Request::where('status', 1)->sum('amount');

            // Package Statistice
            $subscription = Package::get();
            $pack_data = [];
            foreach ($subscription as $row) {

                $sum = array();
                for ($i = 1; $i < 13; $i++) {
                    $Sum = Transaction::where('package_id', $row->id)->whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->sum('price');
                    $sum[] = (int) $Sum;
                }
                $pack_data['label'][] = $row->name;
                $pack_data['sum'][] = $sum;
            }
            $params['package'] = json_encode($pack_data);

            // Rent Earning Statistice
            $rent_data = [];
            for ($i = 1; $i < 13; $i++) {
                $Sum = Rent_Transaction::whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->sum('price');
                $rent_data['sum'][] = (int) $Sum;
            }
            $params['rent_earning'] = json_encode($rent_data);

            // AdsPackage Statistice
            $ads_subscription = Ads_Package::get();
            $ads_pack_data = [];
            foreach ($ads_subscription as $row) {

                $sum = array();
                for ($i = 1; $i < 13; $i++) {
                    $Sum = Ads_Transaction::where('package_id', $row->id)->whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->sum('price');
                    $sum[] = (int) $Sum;
                }
                $ads_pack_data['label'][] = $row->name;
                $ads_pack_data['sum'][] = $sum;
            }
            $params['ads_package'] = json_encode($ads_pack_data);

            // Withdrawal Statistice
            $withdrawal_data['sum'][0] = Withdrawal_Request::whereYear('created_at', date('Y'))->where('status', 0)->sum('amount');
            $withdrawal_data['sum'][1] = Withdrawal_Request::whereYear('created_at', date('Y'))->where('status', 1)->sum('amount');
            $params['withdrawal_earning'] = json_encode($withdrawal_data);

            return view('admin.dashboard.earning_dashboard', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function Page()
    {
        try {
            $currentURL = URL::current();

            $link_array = explode('/', $currentURL);
            $page = end($link_array);

            $data = Page::where('page_name', $page)->first();
            if (isset($data)) {
                return view('page', ['result' => $data]);
            } else {
                return view('errors.404');
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
