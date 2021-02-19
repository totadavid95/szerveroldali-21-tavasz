<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\CategoryController;

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

/*
Route::get('/new-category', function () {
    return view('new-category');
})->name('new-category');
*/

Route::get('/new-category', [CategoryController::class, 'showNewCategoryForm'])->name('new-category');
Route::post('/store-category', [CategoryController::class, 'storeNewCategory'])->name('store-category');
