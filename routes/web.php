<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');
Route::get('/posts', [PostController::class, 'index'])->name('post.all');

Route::group(['middleware' => 'login'], function () {

    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::group(['as' => 'post.'], function () {
        Route::get('/myposts', [PostController::class, 'myposts'])->name('myposts');
        Route::get('/post', [PostController::class, 'create'])->name('create');
        Route::post('/post/store', [PostController::class, 'store'])->name('store');
        Route::get('/post/edit/{id}', [PostController::class, 'edit'])->name('edit');
        Route::put('/post/update/{id}', [PostController::class, 'update'])->name('update');
        Route::delete('/post/delete/{id}', [PostController::class, 'destroy'])->name('delete');
    });

    Route::group(['as' => 'comments.'], function () {
        Route::post('/store/{id}',[CommentController::class, 'store'])->name('store');
        Route::put('/update/{id}',[CommentController::class, 'update'])->name('update');
        Route::delete('/delete/{id}',[CommentController::class, 'destroy'])->name('delete');
    });
});


