<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['as' => 'user.', 'prefix' => 'user', 'controller' => UserController::class], function () {
    Route::get('/', 'index')->name('index');
    Route::post('store', 'store')->name('store');
    Route::post('edit', 'edit')->name('edit');
    Route::post('show', 'show')->name('show');
    Route::post('destroy', 'destroy')->name('destroy');
});
