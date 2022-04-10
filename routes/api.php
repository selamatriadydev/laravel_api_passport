<?php

use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\MenusController;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'users', 'middleware' => 'CORS'], function ($router) {
    Route::post('/register', [UserController::class, 'register'])->name('register.user');
    Route::post('/login', [UserController::class, 'login'])->name('login.user');
    Route::get('/view-profile', [UserController::class, 'viewProfile'])->name('profile.user');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout.user');
});

Route::group(['prefix' => 'groups', 'middleware' => ['CORS','auth:api']], function ($router) {
    Route::get('/list', [GroupController::class, 'index'])->name('list.group');
    Route::post('/create', [GroupController::class, 'store'])->name('create.group');
    Route::get('/{id}', [GroupController::class, 'show'])->name('show.group');
    Route::put('/{id}', [GroupController::class, 'update'])->name('update.group');
    Route::delete('/{id}', [GroupController::class, 'destroy'])->name('delete.group');
});

Route::group(['prefix' => 'menus', 'middleware' => 'CORS'], function ($router) {
    Route::get('/all', [MenusController::class, 'list_all'])->name('all.group');
    Route::get('/list', [MenusController::class, 'index'])->name('list.group');
    Route::post('/add', [MenusController::class, 'store'])->name('add.group');
    Route::get('/{id}', [MenusController::class, 'show'])->name('show.group');
    Route::put('/{id}', [MenusController::class, 'update'])->name('update.group');
    Route::put('/{id}/publish', [MenusController::class, 'publish'])->name('publish.group');
});