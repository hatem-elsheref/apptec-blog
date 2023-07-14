<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
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


Auth::routes(['password.confirm' => false]);

Route::get('/admin'       , [AdminController::class, 'index'])->name('admin');
Route::get('/'            , [PostController::class, 'page'])->name('home');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::singleton('/account', AccountController::class)->except('edit');
Route::singleton('/setting', SettingController::class)->except('edit');

Route::middleware(['auth'])->group(function (){
    Route::prefix('admin')->middleware('admin')->group(function (){
        Route::resource('posts'         , PostController::class)->except('show');
        Route::resource('posts.comments', CommentController::class)->except('create', 'store', 'show');
        Route::resource('users'         , UserController::class)->except('create', 'store', 'show');
    });

});

