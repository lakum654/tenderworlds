<?php

use App\Http\Controllers\admin\KeywordController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\QuestionController;
use App\Http\Controllers\admin\ServiceController;
use App\Http\Controllers\admin\SitemapController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

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

Route::view('/', 'welcome')->name('front');
Route::get('/post/{id}/{slug}', [HomeController::class, 'single'])->name('front.single');
// Route::get('/', function () {
//     return view('auth/login');
// })->middleware('guest');
// Route::get('/', function () {
//     return view('auth/login');
// })->middleware('guest');

Route::prefix('admin')->group(function () {
    Auth::routes();
});

Route::view('/admin', 'admin.index');
Route::group(['prefix' => 'admin/keywords', 'middleware' => ['auth'],], function () {
    Route::get('/', [KeywordController::class, 'index'])->name('keywords');
    Route::get('getKeywordsData', [KeywordController::class, 'getData'])->name('getKeywordsData');
    Route::get('create', [KeywordController::class, 'create'])->name('keywords.create');
    Route::post('store', [KeywordController::class, 'store'])->name('keywords.store');
    Route::get('/{id}/edit', [KeywordController::class, 'edit'])->name('keywords.edit');
    Route::put('/update/{id}', [KeywordController::class, 'update'])->name('keywords.update');
    Route::get('/delete/{id}', [KeywordController::class, 'delete'])->name('keywords.delete');
    Route::get('/changeStatus/{id}', [KeywordController::class, 'changeStatus'])->name('keywords.changeStatus');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth'],], function () {
    Route::get('services/data', [ServiceController::class, 'getData'])->name('services.data');
    Route::get('/delete/{id}', [ServiceController::class, 'destroy'])->name('services.delete');
    Route::get('/changeStatus/{id}', [ServiceController::class, 'changeStatus'])->name('services.changeStatus');
    Route::resource('services', ServiceController::class);
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth'],], function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.index');
    Route::put('profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/password', [ProfileController::class, 'showPasswordForm'])->name('profile.password.form');
    Route::put('profile/password/{id}', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});
