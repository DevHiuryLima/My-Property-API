<?php

use App\Http\Controllers\Api\Auth\LoginJwtController;
use App\Http\Controllers\Api\RealStateController;
use App\Http\Controllers\Api\RealStatePhotoController;
use App\Http\Controllers\Api\RealStateSearchController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function (){
    Route::post('login', [LoginJwtController::class, 'login'])->name('login');
    Route::get('logout', [LoginJwtController::class, 'logout'])->name('logout');
    Route::get('refresh', [LoginJwtController::class, 'refresh'])->name('refresh');

    Route::get('search', [RealStateSearchController::class, 'index'])->name('search');
    Route::get('search/{real_state_id}', [RealStateSearchController::class, 'show'])->name('search_single');

    Route::group(['middleware' => ['jwt.auth']], function (){
        Route::name('real_states.')->group(function (){
            Route::resource('real-states', RealStateController::class); // api/v1/real-states/
        });

        Route::name('users.')->group(function (){
            Route::resource('users', UserController::class);
        });

        Route::name('categories.')->group(function (){
            Route::get('categories/{id}/real-states', [CategoryController::class, 'realState']);
            Route::resource('categories', CategoryController::class);
        });

        Route::name('photos.')->prefix('photos')->group(function(){
            Route::delete('/{id}', [RealStatePhotoController::class, 'remove'])->name('delete');

            Route::put('/set-thumb/{photoId}/{realStateId}', [RealStatePhotoController::class, 'setThumb'])->name('setThumb');
        });
    });
});
