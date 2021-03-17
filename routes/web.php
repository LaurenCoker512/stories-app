<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChaptersController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StoriesController;
use App\Http\Controllers\TagsController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/stories/{story}/chapters/{chapterNum}/comments', [CommentsController::class, 'store']);

Route::group(['middleware' => 'auth'], function() {
    Route::get('/stories/create', [StoriesController::class, 'create']);
    Route::post('/stories', [StoriesController::class, 'store']);
    Route::get('/stories/{story}/edit', [StoriesController::class, 'edit']);
    Route::patch('/stories/{story}', [StoriesController::class, 'update']);
    Route::delete('/stories/{story}', [StoriesController::class, 'destroy']);

    Route::patch('/stories/{story}/chapters/{chapterNum}/comments/{comment}', [CommentsController::class, 'update']);
    Route::delete('/stories/{story}/chapters/{chapterNum}/comments/{comment}', [CommentsController::class, 'destroy']);

    Route::get('/stories/{story}/chapters/create', [ChaptersController::class, 'create']);
    Route::post('/stories/{story}/chapters', [ChaptersController::class, 'store']);
    Route::get('/stories/{story}/chapters/{chapterNum}/edit', [ChaptersController::class, 'edit']);
    Route::patch('/stories/{story}/chapters/{chapterNum}', [ChaptersController::class, 'update']);
    Route::delete('/stories/{story}/chapters/{chapterNum}', [ChaptersController::class, 'destroy']);
});

Route::get('/dashboard/{user}', [DashboardController::class, 'index']);

Route::get('/stories', [StoriesController::class, 'index']);
Route::get('/stories/{story}/chapters/{chapterNum}', [ChaptersController::class, 'show']);

Route::get('/tags', [TagsController::class, 'index']);
Route::get('/tags/{tag}', [TagsController::class, 'show']);

Route::get('/search/', [StoriesController::class, 'search']);

