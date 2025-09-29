<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\MusicController;
use App\Http\Controllers\Api\ReelsController;
use App\Http\Controllers\Api\PlaylistController;

Route::group(['middleware' => 'apipurchasecode'], function () {

    // --------------------- UserController ---------------------
    Route::post('login', [UserController::class, 'login']);
    Route::post('get_profile', [UserController::class, 'get_profile']);
    Route::post('update_profile', [UserController::class, 'update_profile']);
    Route::post('add_remove_subscribe', [UserController::class, 'add_remove_subscribe']);
    Route::post('get_subscribe_list', [UserController::class, 'get_subscribe_list']);
    Route::post('get_subscriber_list', [UserController::class, 'get_subscriber_list']);
    Route::post('add_remove_block_channel', [UserController::class, 'add_remove_block_channel']);
    Route::post('logout', [UserController::class, 'logout']);
    // socket
    Route::post('list_of_live_users', [UserController::class, 'list_of_live_users']);
    
    // --------------------- HomeController ---------------------
    Route::post('general_setting', [HomeController::class, 'general_setting']);
    Route::post('get_payment_option', [HomeController::class, 'get_payment_option']);
    Route::post('get_pages', [HomeController::class, 'get_pages']);
    Route::post('get_social_links', [HomeController::class, 'get_social_links']);
    Route::post('get_onboarding_screen', [HomeController::class, 'get_onboarding_screen']);
    Route::post('get_report_reason', [HomeController::class, 'get_report_reason']);
    Route::post('get_package', [HomeController::class, 'get_package']);
    Route::post('add_content_report', [HomeController::class, 'add_content_report']);
    Route::post('add_view', [HomeController::class, 'add_view']);
    Route::post('add_remove_like_dislike', [HomeController::class, 'add_remove_like_dislike']);
    Route::post('add_remove_watch_later', [HomeController::class, 'add_remove_watch_later']);
    Route::post('add_comment', [HomeController::class, 'add_comment']);
    Route::post('edit_comment', [HomeController::class, 'edit_comment']);
    Route::post('delete_comment', [HomeController::class, 'delete_comment']);
    Route::post('get_comment', [HomeController::class, 'get_comment']);
    Route::post('get_reply_comment', [HomeController::class, 'get_reply_comment']);
    Route::post('add_comment_report', [HomeController::class, 'add_comment_report']);
    Route::post('get_content_detail', [HomeController::class, 'get_content_detail']);
    Route::post('get_like_content', [HomeController::class, 'get_like_content']);
    Route::post('get_watch_later_content', [HomeController::class, 'get_watch_later_content']);
    Route::post('get_content_by_channel', [HomeController::class, 'get_content_by_channel']);
    Route::post('add_content_to_history', [HomeController::class, 'add_content_to_history']);
    Route::post('remove_content_to_history', [HomeController::class, 'remove_content_to_history']);
    Route::post('get_content_to_history', [HomeController::class, 'get_content_to_history']);
    Route::post('get_episode_by_podcasts', [HomeController::class, 'get_episode_by_podcasts']);
    Route::post('get_radio_content', [HomeController::class, 'get_radio_content']);
    Route::post('add_transaction', [HomeController::class, 'add_transaction']);
    Route::post('search_content', [HomeController::class, 'search_content']);
    Route::post('get_rent_section', [HomeController::class, 'get_rent_section']);
    Route::post('get_rent_section_detail', [HomeController::class, 'get_rent_section_detail']);
    Route::post('add_rent_transaction', [HomeController::class, 'add_rent_transaction']);
    Route::post('get_user_rent_content', [HomeController::class, 'get_user_rent_content']);
    Route::post('get_rent_content_by_channel', [HomeController::class, 'get_rent_content_by_channel']);
    Route::post('get_notification', [HomeController::class, 'get_notification']);
    Route::post('read_notification', [HomeController::class, 'read_notification']);
    Route::post('delete_content', [HomeController::class, 'delete_content']);
    Route::post('get_withdrawal_request_list', [HomeController::class, 'get_withdrawal_request_list']);
    Route::post('get_transaction_list', [HomeController::class, 'get_transaction_list']);
    Route::post('get_post', [HomeController::class, 'get_post']);
    Route::post('get_channel_post', [HomeController::class, 'get_channel_post']);
    Route::post('post_content_upload', [HomeController::class, 'post_content_upload']);
    Route::post('upload_post', [HomeController::class, 'upload_post']);
    Route::post('delete_post', [HomeController::class, 'delete_post']);
    Route::post('add_post_comment', [HomeController::class, 'add_post_comment']);
    Route::post('edit_post_comment', [HomeController::class, 'edit_post_comment']);
    Route::post('delete_post_comment', [HomeController::class, 'delete_post_comment']);
    Route::post('get_post_comment', [HomeController::class, 'get_post_comment']);
    Route::post('get_post_reply_comment', [HomeController::class, 'get_post_reply_comment']);
    Route::post('like_unlike_post', [HomeController::class, 'like_unlike_post']);
    Route::post('add_post_view', [HomeController::class, 'add_post_view']);
    Route::post('add_post_report', [HomeController::class, 'add_post_report']);
    Route::post('get_gift', [HomeController::class, 'get_gift']);
    Route::post('get_user_gift', [HomeController::class, 'get_user_gift']);
    Route::post('buy_gift', [HomeController::class, 'buy_gift']);

    // --------------------- VideoController ---------------------
    Route::post('get_video_category', [VideoController::class, 'get_video_category']);
    Route::post('get_video_list', [VideoController::class, 'get_video_list']);
    Route::post('get_releted_video', [VideoController::class, 'get_releted_video']);

    // --------------------- MusicController ---------------------
    Route::post('get_music_category', [MusicController::class, 'get_music_category']);
    Route::post('get_music_section', [MusicController::class, 'get_music_section']);
    Route::post('get_music_section_detail', [MusicController::class, 'get_music_section_detail']);
    Route::post('get_music_by_category', [MusicController::class, 'get_music_by_category']);
    Route::post('get_music_by_language', [MusicController::class, 'get_music_by_language']);
    Route::post('get_releted_music', [MusicController::class, 'get_releted_music']);

    // --------------------- ReelsController ---------------------
    Route::post('get_reels_list', [ReelsController::class, 'get_reels_list']);
    Route::post('upload_reels', [ReelsController::class, 'upload_reels']);

    // --------------------- PlaylistController ---------------------
    Route::post('create_playlist', [PlaylistController::class, 'create_playlist']);
    Route::post('edit_playlist', [PlaylistController::class, 'edit_playlist']);
    Route::post('delete_playlist', [PlaylistController::class, 'delete_playlist']);
    Route::post('add_remove_content_to_playlist', [PlaylistController::class, 'add_remove_content_to_playlist']);
    Route::post('get_playlist_content', [PlaylistController::class, 'get_playlist_content']);
    Route::post('add_multipal_content_to_playlist', [PlaylistController::class, 'add_multipal_content_to_playlist']);
    Route::post('get_content_to_playlist', [PlaylistController::class, 'get_content_to_playlist']);

    // --------------------- AdsController ---------------------
    Route::post('get_ads', [AdsController::class, 'get_ads']);
    Route::post('add_ads_view_click_count', [AdsController::class, 'add_ads_view_click_count']);
    Route::post('get_ads_package', [AdsController::class, 'get_ads_package']);
    Route::post('add_ads_transaction', [AdsController::class, 'add_ads_transaction']);
    Route::post('get_ads_transaction_list', [AdsController::class, 'get_ads_transaction_list']);
    Route::post('get_ads_coin_history', [AdsController::class, 'get_ads_coin_history']);
});
