<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoriesController;

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

Route::get('/stories', [StoriesController::class, 'index']);
Route::get('/stories/{story}', [StoriesController::class, 'show']);

Route::group(['middleware' => 'auth'], function() {
    Route::post('/stories', [StoriesController::class, 'store']);
    Route::get('/stories/{story}/edit', [StoriesController::class, 'edit']);
});

