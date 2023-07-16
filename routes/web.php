<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

Route::get('/test/{is_new?}', function ($isNew=null){
    $file = base_path('..' . DIRECTORY_SEPARATOR . 'v-1080.mp4');

    $chunkSize = 1024 * 1024 *  config('services.vimeo.size', 5); //5MB

    $fileSize = filesize($file);

    $offsets = range(0, $fileSize, $chunkSize);

    if (!is_null($isNew)){cache()->forget('_url');}

    $url = cache()->remember('_url', now()->addDay(), function () use ($fileSize){
        return Http::withToken(config('services.vimeo.token'))
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Accept', 'application/vnd.vimeo.*+json;version=3.4')
            ->post(sprintf("%s/%s", config('services.vimeo.url'), 'me/videos'), [
                'upload'      => ['approach' => 'tus', 'size' => $fileSize],
                'name'        => 'Test Hatem ' . now()->format('d-m-Y H:i'),
                'description' => 'AppTech Task'
            ])->json('upload.upload_link');
    });



    $response = Http::withHeaders([
        'Tus-Resumable'  => '1.0.0',
        'Accept'         => 'application/offset+octet-stream',
    ])->head($url)->headers();

    dd($response);
    Log::error("Uploading By Tus File With Size = " . $fileSize);

//    $fileHandler = fopen($file, 'rb');
    foreach ($offsets as $index => $offset){
//        fseek($fileHandler, $offset);
//        $chunk = fread($fileHandler, $chunkSize);

//        $path = public_path($index.'.mp4');
//        $fileHandlerWriter = fopen($path, 'w');
//        fwrite($fileHandlerWriter, $chunk);
//        fclose($fileHandlerWriter);

        \App\Jobs\Test::dispatch($url, $file, $offset, $chunkSize)
//        ->delay(now()->addSeconds(5))
        ;

    }
});
