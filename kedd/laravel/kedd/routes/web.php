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

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/new-category', [CategoryController::class, 'newCategoryFormIndex'])->name('new-category');
Route::post('/store-category', [CategoryController::class, 'store'])->name('store-category');

Route::get('/new-post', [PostController::class, 'newPostFormIndex'])->name('new-post');
Route::post('/store-post', [PostController::class, 'store'])->name('store-post');
