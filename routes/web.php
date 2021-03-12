<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StoriesController;
use App\Http\Controllers\StoryChaptersController;

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

Route::group(['middleware' => 'auth'], function() {
    Route::get('/stories/create', [StoriesController::class, 'create']);
    Route::post('/stories', [StoriesController::class, 'store']);
    Route::get('/stories/{story}/edit', [StoriesController::class, 'edit']);
    Route::patch('/stories/{story}', [StoriesController::class, 'update']);
    Route::delete('/stories/{story}', [StoriesController::class, 'destroy']);

    Route::post('/stories/{story}/chapters', [StoryChaptersController::class, 'store']);
    Route::patch('/stories/{story}/chapters/{chapter}', [StoryChaptersController::class, 'update']);

    Route::get('/dashboard/{user}', [DashboardController::class, 'index']);
});

Route::get('/stories', [StoriesController::class, 'index']);
Route::get('/stories/{story}', [StoriesController::class, 'show']);

