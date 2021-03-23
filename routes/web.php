<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChaptersController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StoriesController;
use App\Http\Controllers\SubscriptionsController;
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

Route::redirect('/', '/stories');

Auth::routes();

Route::post(
    '/stories/{story}/chapters/{chapterNum}/comments', 
    [CommentsController::class, 'store']
)->name('comments.store');

Route::group(['middleware' => 'auth'], function() {
    // Story routes
    Route::resource('stories', StoriesController::class)->only([
        'create', 'store', 'edit', 'update', 'destroy'
    ])->names([
        'create' => 'stories.create',
        'store' => 'stories.store',
        'edit' => 'stories.edit',
        'update' => 'stories.update',
        'destroy' => 'stories.destroy',
    ]);

    // Comment routes
    Route::patch(
        '/stories/{story}/chapters/{chapterNum}/comments/{comment}', 
        [CommentsController::class, 'update']
    )->name('comments.update');
    Route::delete(
        '/stories/{story}/chapters/{chapterNum}/comments/{comment}', 
        [CommentsController::class, 'destroy']
    )->name('comments.destroy');

    // Chapter routes
    Route::get(
        '/stories/{story}/chapters/create', 
        [ChaptersController::class, 'create']
    )->name('chapters.create');
    Route::post(
        '/stories/{story}/chapters', 
        [ChaptersController::class, 'store']
    )->name('chapters.store');
    Route::get(
        '/stories/{story}/chapters/{chapterNum}/edit', 
        [ChaptersController::class, 'edit']
    )->name('chapters.edit');
    Route::patch(
        '/stories/{story}/chapters/{chapterNum}', 
        [ChaptersController::class, 'update']
    )->name('chapters.update');
    Route::delete(
        '/stories/{story}/chapters/{chapterNum}', 
        [ChaptersController::class, 'destroy']
    )->name('chapters.destroy');

    // Subscription routes
    Route::get(
        '/subscriptions', 
        [SubscriptionsController::class, 'index']
    )->name('subscriptions.index');
    Route::post(
        '/subscriptions/story/{story}', 
        [SubscriptionsController::class, 'createStorySub']
    )->name('subscriptions.story.create');
    Route::post(
        '/subscriptions/user/{user}', 
        [SubscriptionsController::class, 'createUserSub']
    )->name('subscriptions.user.create');
    Route::delete(
        '/subscriptions/story/{story}', 
        [SubscriptionsController::class, 'deleteStorySub']
    )->name('subscriptions.story.delete');
    Route::delete(
        '/subscriptions/user/{user}', 
        [SubscriptionsController::class, 'deleteUserSub']
    )->name('subscriptions.user.delete');
});

// User dashboard
Route::get(
    '/dashboard/{user}', 
    [DashboardController::class, 'index']
)->name('dashboard');

// Story routes
Route::get('/stories', [StoriesController::class, 'index'])->name('stories.index');
Route::get('/stories/browse', [StoriesController::class, 'getType'])->name('stories.browse');
Route::get('/search', [StoriesController::class, 'search'])->name('stories.search');

// Chapter routes
Route::get(
    '/stories/{story}/chapters/{chapterNum}', 
    [ChaptersController::class, 'show']
)->name('chapters.show');

// Tag routes
Route::get('/tags/search', [TagsController::class, 'search'])->name('tags.search');

Route::resource('tags', TagsController::class)->only([
    'index', 'show', 'store'
])->names([
    'index' => 'tags.index',
    'show' => 'tags.show',
    'store' => 'tags.store'
]);
