<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Socket\SocketController;
use Illuminate\Support\Facades\Route;

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

// Page
Route::group(['middleware' => 'installation'], function () {
    Route::get('pages/{page_name}', [DashboardController::class, 'Page'])->name('admin.pages');
});

// Socket Route
Route::post('addlivehistory', [SocketController::class, 'addLiveHistory']);
Route::post('endlive', [SocketController::class, 'endLive']);
Route::post('addview', [SocketController::class, 'addView']);
Route::post('lessview', [SocketController::class, 'lessView']);
Route::post('livechat', [SocketController::class, 'liveChat']);
Route::post('sendgift', [SocketController::class, 'sendGift']);