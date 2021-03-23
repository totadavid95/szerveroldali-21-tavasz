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

/*Route::get('/', function () {
    return view('home');
})->name('home');*/

Route::get('/', function () {
    return redirect()->route('posts.index');
});

Route::get('/home', function () {
    return redirect()->route('posts.index');
});

Route::resource('categories', CategoryController::class)->only([
    'create', 'store', 'edit', 'update',
]);

Route::get('posts/{id}/attachment', [PostController::class, 'attachment'])->name('posts.attachment');
Route::resource('posts', PostController::class);

//Route::get('/new-category', [CategoryController::class, 'newCategoryFormIndex'])->name('new-category');
//Route::post('/store-category', [CategoryController::class, 'store'])->name('store-category');

//Route::get('/new-post', [PostController::class, 'newPostFormIndex'])->name('new-post');
//Route::post('/store-post', [PostController::class, 'store'])->name('store-post');

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
