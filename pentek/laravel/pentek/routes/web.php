<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

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

/*
Route::get('/', function () {
    return view('home');
})->name('home');
*/

Route::get('/', function () {
    return redirect()->route('posts.index');
})->name('home');

/*
Route::get('/new-category', function () {
    return view('new-category');
})->name('new-category');
*/

/*
Route::get('/new-category', [CategoryController::class, 'showNewCategoryForm'])->name('new-category');
Route::post('/store-category', [CategoryController::class, 'storeNewCategory'])->name('store-category');
*/

Route::resource('categories', CategoryController::class);

/*
Route::get('/new-post', [PostController::class, 'showNewPostForm'])->name('new-post');
Route::post('/store-post', [PostController::class, 'storeNewPost'])->name('store-post');
*/

Route::get('/posts/{id}/attachment', [PostController::class, 'attachment'])->name('posts.attachment');

Route::resource('posts', PostController::class);

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
