<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Admin\AdmobSettingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\HashtagController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\RentTransactionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\MusicController;
use App\Http\Controllers\Admin\ReelsController;
use App\Http\Controllers\Admin\PodcastsController;
use App\Http\Controllers\Admin\PlaylistController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\RadioController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ContentReportController;
use App\Http\Controllers\Admin\RentSectionController;
use App\Http\Controllers\Admin\CommentReportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\AdsController;
use App\Http\Controllers\Admin\AdsPackageController;
use App\Http\Controllers\Admin\AdsTransactionController;
use App\Http\Controllers\Admin\CustomAdsSettingController;
use App\Http\Controllers\Admin\FaceBookAdsSettingController;
use App\Http\Controllers\Admin\GiftController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PostReportController;
use App\Http\Controllers\Admin\RentSettingController;
use App\Http\Controllers\Admin\WalletUserController;
use App\Http\Controllers\Admin\WithdrawalController;

// Artisan
Route::get('artisan', function () {

    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "<h1>All Config Cache Clear Successfully.</h1>";
});

// Version
Route::get('version', function () {
    return "<h1>
        <li>PHP : " . phpversion() . "</li>
        <li>Laravel : " . app()->version() . "</li>
    </h1>";
});

Route::group(['middleware' => 'installation'], function () {

    // Login-Logout
    Route::get('login', [LoginController::class, 'login'])->name('admin.login');
    Route::post('login', [LoginController::class, 'save_login'])->name('admin.save.login');
    Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');
    // Chunk
    Route::any('video/saveChunk', [VideoController::class, 'saveChunk']);
    Route::any('post/saveChunk', [PostController::class, 'saveChunk']);
    Route::any('reel/saveChunk', [ReelsController::class, 'saveChunk']);
    Route::any('music/saveChunk', [MusicController::class, 'saveChunk']);
    Route::any('ads/saveChunk', [AdsController::class, 'saveChunk']);

    Route::group(['middleware' => 'authadmin'], function () {

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('dashboard/earning', [DashboardController::class, 'earningDashboard'])->name('earning.dashboard');
        // Profile
        Route::resource('profile', ProfileController::class)->only(['index', 'store']);
        Route::post('profile/changepassword', [ProfileController::class, 'ChangePassword'])->name('profile.changepassword');
        // Category
        Route::resource('category', CategoryController::class)->only(['index', 'store', 'update']);
        // Gift
        Route::resource('gift', GiftController::class)->only(['index', 'store', 'update']);
        // Language
        Route::resource('language', LanguageController::class)->only(['index', 'store', 'update']);
        // Artist
        Route::resource('artist', ArtistController::class)->only(['index', 'store', 'update']);
        // Hashtag
        Route::resource('hashtag', HashtagController::class)->only(['index', 'store', 'update']);
        // Pages
        Route::resource('page', PageController::class)->only(['index', 'edit', 'update']);
        // User
        Route::resource('user', UserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::get('user/wallet/{id}', [UserController::class, 'wallet'])->name('user.wallet');
        Route::post('user/penal/{id}', [UserController::class, 'userPenalStatus'])->name('user.penal.status');
        // Section
        Route::resource('section', SectionController::class)->only(['index', 'store', 'update', 'show']);
        Route::post('section/data', [SectionController::class, 'GetSectionData'])->name('section.content.data');
        Route::post('section/edit', [SectionController::class, 'SectionDataEdit'])->name('section.content.edit');
        Route::post('section/sortable', [SectionController::class, 'SectionSortable'])->name('section.content.sortable');
        Route::post('section/sortable/save', [SectionController::class, 'SectionSortableSave'])->name('section.content.sortable.save');
        // Video
        Route::resource('video', VideoController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::get('videostatus', [VideoController::class, 'changeStatus'])->name('video.status');
        Route::post('importvideo', [VideoController::class, 'importVideo'])->name('video.import');
        // Music
        Route::resource('music', MusicController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::get('musicstatus', [MusicController::class, 'changeStatus'])->name('music.status');
        // Reels
        Route::resource('reels', ReelsController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        Route::get('reelsstatus', [ReelsController::class, 'changeStatus'])->name('reels.status');
        // Post
        Route::resource('post', PostController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
        Route::get('post/content/{id}', [PostController::class, 'getpostcontent'])->name('postcontent.index');
        Route::post('post/content/save', [PostController::class, 'postcontentstore'])->name('postcontent.store');
        Route::post('post/content/delete', [PostController::class, 'postcontentdelete'])->name('postcontent.destroy');
        // Podcasts
        Route::resource('podcasts', PodcastsController::class)->only(['index', 'store', 'update', 'show']);
        Route::get('podcastsepisode/{id}', [PodcastsController::class, 'PodcastIndex'])->name('podcast.episode.index');
        Route::get('podcastsepisode/add/{id}', [PodcastsController::class, 'PodcastAdd'])->name('podcast.episode.add');
        Route::post('podcastsepisode/save', [PodcastsController::class, 'PodcastSave'])->name('podcast.episode.save');
        Route::get('podcastsepisode/edit/{podcasts_id}/{id}', [PodcastsController::class, 'PodcastEdit'])->name('podcast.episode.edit');
        Route::post('podcastsepisode/update/{podcasts_id}/{id}', [PodcastsController::class, 'PodcastUpdate'])->name('podcast.episode.update');
        Route::post('podcastsepisode/sortable', [PodcastsController::class, 'PodcastSortable'])->name('podcast.episode.sortable');
        // Radio
        Route::resource('radio', RadioController::class)->only(['index', 'store', 'update', 'show']);
        Route::get('radio/content/{id}', [RadioController::class, 'RadioIndex'])->name('radio.content.index');
        Route::post('radio/save', [RadioController::class, 'RadioSave'])->name('radio.content.save');
        Route::post('radio/delete', [RadioController::class, 'RadioDelete'])->name('radio.content.delete');
        Route::post('radio/sortable', [RadioController::class, 'RadioSortable'])->name('radio.content.sortable');
        // Playlist
        Route::resource('playlist', PlaylistController::class)->only(['index', 'store', 'update', 'show']);
        Route::get('playlist/content/{id}', [PlaylistController::class, 'PlaylistIndex'])->name('playlist.content.index');
        Route::post('playlist/getcontentdata', [PlaylistController::class, 'GetContentData'])->name('playlist.get.content');
        Route::post('playlist/save', [PlaylistController::class, 'PlaylistSave'])->name('playlist.content.save');
        Route::post('playlist/delete', [PlaylistController::class, 'PlaylistDelete'])->name('playlist.content.delete');
        Route::post('playlist/sortable', [PlaylistController::class, 'PlaylistSortable'])->name('playlist.content.sortable');
        // Rent Section
        Route::resource('rentsection', RentSectionController::class)->only(['index', 'store', 'update', 'show']);
        Route::post('rentsection/data', [RentSectionController::class, 'GetSectionData'])->name('rentsection.content.data');
        Route::post('rentsection/edit', [RentSectionController::class, 'SectionDataEdit'])->name('rentsection.content.edit');
        Route::post('rentsection/sortable', [RentSectionController::class, 'SectionSortable'])->name('rentsection.content.sortable');
        Route::post('rentsection/sortable/save', [RentSectionController::class, 'SectionSortableSave'])->name('rentsection.content.sortable.save');
        // Rent Transaction
        Route::resource('renttransaction', RentTransactionController::class)->only(['index', 'create', 'store']);
        Route::any('rentsearch_user', [RentTransactionController::class, 'searchUser'])->name('rentSearchUser');
        // Rent Setting
        Route::resource('rentsetting', RentSettingController::class)->only(['index', 'store']);
        // Comment
        Route::resource('comment', CommentController::class)->only(['index', 'show']);
        // Notification
        Route::resource('notification', NotificationController::class)->only(['index', 'create', 'store']);
        // Report
        Route::resource('report', ReportController::class)->only(['index', 'store', 'update']);
        // Content Report
        Route::resource('contentreport', ContentReportController::class)->only(['index']);
        Route::get('contentreportstatus', [ContentReportController::class, 'changeStatus'])->name('contentreport.status');
        // report 
        Route::resource('reportpost', PostReportController::class)->only(['index']);
        Route::post('reportpost/status', [PostReportController::class, 'changeStatus'])->name('reportpost.status');
        // Comment Report
        Route::resource('commenreport', CommentReportController::class)->only(['index']);
        Route::get('commentreportstatus', [CommentReportController::class, 'changeStatus'])->name('commentreport.status');
        // Package
        Route::resource('package', PackageController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        // Transaction
        Route::resource('transaction', TransactionController::class)->only(['index', 'create', 'store']);
        Route::any('search_user', [TransactionController::class, 'searchUser'])->name('searchUser');
        // Payment
        Route::resource('payment', PaymentController::class)->only(['index']);
        // User Wallet
        Route::resource('walletuser', WalletUserController::class)->only(['index', 'edit']);
        // Withdrawal
        Route::resource('withdrawal', WithdrawalController::class)->only(['index', 'show']);
        Route::post('withdrawal/minamount', [WithdrawalController::class, 'saveMinWithdrawalAmount'])->name('withdrawal.save.amount');
        // Custom Ads
        Route::resource('ads', AdsController::class)->only(['index', 'create', 'store']);
        Route::get('ads/list/{id}', [AdsController::class, 'adsList'])->name('ads.list');
        Route::get('ads/details/{user_id}/{ads_id}', [AdsController::class, 'adsDetails'])->name('ads.details');
        Route::get('adsstatus', [AdsController::class, 'changeStatus'])->name('ads.status');
        // Coin Package
        Route::resource('adpackage', AdsPackageController::class)->only(['index', 'create', 'store', 'edit', 'update']);
        // Coin Transaction
        Route::resource('adtransaction', AdsTransactionController::class)->only(['index', 'create', 'store']);
        Route::any('adsearch_user', [AdsTransactionController::class, 'searchUser'])->name('adSearchUser');
        // Custom Ads Setting
        Route::resource('customadssetting', CustomAdsSettingController::class)->only(['index']);
        Route::post('customadssetting/adscommission', [CustomAdsSettingController::class, 'saveAdsCommission'])->name('customAdsCommission');
        Route::post('customadssetting/bannerads', [CustomAdsSettingController::class, 'saveBannerAds'])->name('customBannerAds');
        Route::post('customadssetting/interstitalads', [CustomAdsSettingController::class, 'saveInterstitalAds'])->name('customInterstitalAds');
        Route::post('customadssetting/rewardads', [CustomAdsSettingController::class, 'saveRewardAds'])->name('customRewardAds');
        // Admob
        Route::resource('admob', AdmobSettingController::class)->only(['index']);
        Route::post('admob/android', [AdmobSettingController::class, 'admobAndroid'])->name('admob.android');
        Route::post('admob/ios', [AdmobSettingController::class, 'admobIos'])->name('admob.ios');
        // FaceBook Ads
        Route::resource('fbads', FaceBookAdsSettingController::class)->only(['index']);
        Route::post('fbads/android', [FaceBookAdsSettingController::class, 'facebookadAndroid'])->name('fbads.android');
        Route::post('fbads/ios', [FaceBookAdsSettingController::class, 'facebookadIos'])->name('fbads.ios');
        // App Setting
        Route::get('setting', [SettingController::class, 'index'])->name('setting');
        Route::post('setting/app', [SettingController::class, 'app'])->name('setting.app');
        Route::post('setting/currency', [SettingController::class, 'currency'])->name('setting.currency');
        Route::post('setting/vapidkey', [SettingController::class, 'vapIdKey'])->name('setting.vapidkey');
        Route::post('setting/deletereelsday', [SettingController::class, 'afterDayDeleteReels'])->name('setting.deletereels');
        Route::post('setting/sociallink', [SettingController::class, 'saveSocialLink'])->name('settingSocialLink');
        Route::post('setting/smtp', [SettingController::class, 'smtpSave'])->name('smtp.save');
        Route::post('setting/livestreaming', [SettingController::class, 'liveStreaming'])->name('settingLiveStreaming');
        Route::post('setting/deepar', [SettingController::class, 'DeepARsave'])->name('settingDeepAR');
        Route::post('setting/onboardingscreen', [SettingController::class, 'saveOnBoardingScreen'])->name('settingOnBoardingScreen');
        Route::post('setting/sightengine', [SettingController::class, 'SightEngine'])->name('settingSightengine');
        // System Setting
        Route::get('systemsetting', [SystemSettingController::class, 'index'])->name('system.setting.index');
        Route::post('systemsetting/cleardata', [SystemSettingController::class, 'ClearData'])->name('system.setting.cleardata');
        Route::post('systemsetting/dummydata', [SystemSettingController::class, 'DummyData'])->name('system.setting.dummydata');
        Route::post('systemsetting/cleandatabase', [SystemSettingController::class, 'CleanDatabase'])->name('system.setting.cleandatabase');

        Route::group(['middleware' => 'checkadmin'], function () {

            // Category
            Route::resource('category', CategoryController::class)->only(['destroy']);
            // Gift
            Route::resource('gift', GiftController::class)->only(['destroy']);
            // Language
            Route::resource('language', LanguageController::class)->only(['destroy']);
            // Artist
            Route::resource('artist', ArtistController::class)->only(['destroy']);
            // User
            Route::resource('user', UserController::class)->only(['destroy']);
            // Video
            Route::resource('video', VideoController::class)->only(['show']);
            // Music
            Route::resource('music', MusicController::class)->only(['show']);
            // Reels
            Route::resource('reels', ReelsController::class)->only(['show']);
            Route::get('reelsstatus', [ReelsController::class, 'changeStatus'])->name('reels.status');
            // Post
            Route::resource('post', PostController::class)->only(['destroy']);
            // Podcasts
            Route::resource('podcasts', PodcastsController::class)->only(['destroy']);
            Route::get('podcastsepisode/delete/{podcasts_id}/{id}', [PodcastsController::class, 'PodcastDelete'])->name('podcast.episode.delete');
            // Radio
            Route::resource('radio', RadioController::class)->only(['destroy']);
            // Playlist
            Route::resource('playlist', PlaylistController::class)->only(['destroy']);
            // Rent Transaction
            Route::resource('renttransaction', RentTransactionController::class)->only(['destroy']);
            // Notification
            Route::resource('notification', NotificationController::class)->only(['destroy']);
            Route::get('notifications/setting', [NotificationController::class, 'setting'])->name('notification.setting');
            Route::post('notifications/setting', [NotificationController::class, 'settingsave'])->name('notification.settingsave');
            // Report
            Route::resource('report', ReportController::class)->only(['destroy']);
            // Package
            Route::resource('package', PackageController::class)->only(['destroy']);
            // Transaction
            Route::resource('transaction', TransactionController::class)->only(['destroy']);
            // Payment
            Route::resource('payment', PaymentController::class)->only(['edit', 'update']);
            // Custom Ads
            Route::resource('ads', AdsController::class)->only(['show']);
            // Coin Package
            Route::resource('adpackage', AdsPackageController::class)->only(['destroy']);
            // Coin Transaction
            Route::resource('adtransaction', AdsTransactionController::class)->only(['destroy']);
            // System Setting
            Route::get('systemsetting/downloadsqlfile', [SystemSettingController::class, 'DownloadSqlFile'])->name('system.setting.downloadsqlfile');
        });
    });
});
