<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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



Auth::routes();

Route::get('/home'        , [PostController::class, 'page']);
Route::get('/'            , [PostController::class, 'page'])->name('home');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

Route::middleware(['auth'])->group(function (){
    Route::prefix('admin')->middleware('admin')->group(function (){
        Route::resource('posts'         , PostController::class)->except('show');
        Route::resource('posts.comments', CommentController::class)->except('create', 'store', 'show');
        Route::resource('users'         , UserController::class)->except('create', 'store', 'show');
    });

    Route::middleware('auth')->group(function (){
//        Route::post('posts', PostController::class);
//        Route::post('comments', [CommentController::class, 'store']);
//        Route::resource('users', UserController::class)->except('create', 'store', 'show');
    });
});

