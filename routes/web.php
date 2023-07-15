<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// visitor
Auth::routes(['password.confirm' => false]);
Route::get('/'            , [PostController::class, 'page'])->name('home');

// user && admin
Route::middleware(['auth'])->group(function (){
    // admin
    Route::prefix('admin')->middleware('admin')->group(function (){
        Route::get('/'                    , [AdminController::class, 'index'])->name('admin');
        Route::resource('posts'         , PostController::class)->except('show');
        Route::resource('posts.comments', CommentController::class)->only('index', 'edit', 'update');
        Route::resource('posts.reacts'  , ReactController::class)->only('index');
        Route::resource('users'         , UserController::class)->except('create', 'store', 'show');
        Route::singleton('/setting'     , SettingController::class)->except('edit');
        Route::post('/upload'             , [PostController::class, 'upload']);
    });

    // user
    Route::resource('posts'         , PostController::class)->only('show');
    Route::resource('posts.comments', CommentController::class)->only('destroy', 'store');
    Route::resource('posts.reacts'  , ReactController::class)->only('destroy');
    Route::singleton('/account'     , AccountController::class)->except('edit');
});

