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

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AdsController;
use App\Http\Controllers\User\AdsPackageController;
use App\Http\Controllers\User\AdsTransactionController;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\VideoController;
use App\Http\Controllers\User\ReelsController;
use App\Http\Controllers\User\PodcastsController;
use App\Http\Controllers\User\PlaylistController;
use App\Http\Controllers\User\PostController;
use App\Http\Controllers\User\WithdrawalController;

Route::group(['middleware' => 'installation'], function () {

    // Login-Logout
    Route::get('login', [LoginController::class, 'login'])->name('user.login');
    Route::post('login', [LoginController::class, 'save_login'])->name('user.save.login');
    Route::get('logout', [LoginController::class, 'logout'])->name('user.logout');

    Route::group(['middleware' => 'authuser'], function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
        Route::resource('uprofile', ProfileController::class)->only(['index', 'update']);
        Route::resource('uchangepassword', ChangePasswordController::class)->only(['index', 'update']);
        Route::resource('uvideo', VideoController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
        Route::get('uvideostatus', [VideoController::class, 'changeStatus'])->name('uvideo.status');
        Route::resource('ureels', ReelsController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
        Route::get('ureelsstatus', [ReelsController::class, 'changeStatus'])->name('ureels.status');

        Route::resource('upost', PostController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        Route::get('post/content/{id}', [PostController::class, 'getpostcontent'])->name('upostcontent.index');
        Route::post('post/content/save', [PostController::class, 'postcontentstore'])->name('upostcontent.store');
        Route::post('post/content/delete', [PostController::class, 'postcontentdelete'])->name('upostcontent.destroy');

        Route::resource('upodcasts', PodcastsController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('upodcastsepisode/{id}', [PodcastsController::class, 'PodcastIndex'])->name('upodcast.episode.index');
        Route::get('upodcastsepisode/add/{id}', [PodcastsController::class, 'PodcastAdd'])->name('upodcast.episode.add');
        Route::post('upodcastsepisode/save', [PodcastsController::class, 'PodcastSave'])->name('upodcast.episode.save');
        Route::get('upodcastsepisode/edit/{podcasts_id}/{id}', [PodcastsController::class, 'PodcastEdit'])->name('upodcast.episode.edit');
        Route::post('upodcastsepisode/update/{podcasts_id}/{id}', [PodcastsController::class, 'PodcastUpdate'])->name('upodcast.episode.update');
        Route::get('upodcastsepisode/delete/{podcasts_id}/{id}', [PodcastsController::class, 'PodcastDelete'])->name('upodcast.episode.delete');
        Route::post('upodcastsepisode/sortable', [PodcastsController::class, 'PodcastSortable'])->name('upodcast.episode.sortable');
        Route::resource('uplaylist', PlaylistController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('uplaylist/{id}', [PlaylistController::class, 'PlaylistIndex'])->name('uplaylist.content.index');
        Route::post('uplaylist/getcontentdata', [PlaylistController::class, 'GetContentData'])->name('uplaylist.get.content');
        Route::post('uplaylist/save', [PlaylistController::class, 'PlaylistSave'])->name('uplaylist.content.save');
        Route::post('uplaylist/delete', [PlaylistController::class, 'PlaylistDelete'])->name('uplaylist.content.delete');
        Route::post('uplaylist/sortable', [PlaylistController::class, 'PlaylistSortable'])->name('uplaylist.content.sortable');
        Route::resource('uads', AdsController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('uads/details/{id}', [AdsController::class, 'adsDetails'])->name('uads.details');
        Route::resource('uadpackage', AdsPackageController::class)->only(['index']);
        Route::resource('uadtransaction', AdsTransactionController::class)->only(['index']);
        Route::resource('uwithdrawal', WithdrawalController::class)->only(['index']);
    });
});
