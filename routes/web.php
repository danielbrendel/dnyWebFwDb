<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\FrameworkController;
use App\Http\Controllers\MemberController;

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

Route::get('/', [MainController::class, 'index']);
Route::post('/register', [MainController::class, 'register']);
Route::get('/confirm', [MainController::class, 'confirm']);
Route::get('/reset', [MainController::class, 'viewReset']);
Route::post('/recover', [MainController::class, 'recover']);
Route::get('/resend/{id}', [MainController::class, 'resend']);
Route::post('/reset', [MainController::class, 'reset']);
Route::post('/login', [MainController::class, 'login']);
Route::any('/logout', [MainController::class, 'logout']);
Route::post('/framework/query', [FrameworkController::class, 'query']);
Route::post('/framework/query/user', [FrameworkController::class, 'queryUser']);
Route::post('/framework/query/reviews', [FrameworkController::class, 'queryReviews']);
Route::get('/view/{framework}', [FrameworkController::class, 'view']);
Route::get('/user/{ident}', [MemberController::class, 'showProfile']);
Route::get('/member/name/valid', [MemberController::class, 'usernameValidity']);