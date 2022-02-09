<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\FrameworkController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;

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
Route::get('/imprint', [MainController::class, 'imprint']);
Route::get('/tos', [MainController::class, 'tos']);
Route::post('/register', [MainController::class, 'register']);
Route::get('/confirm', [MainController::class, 'confirm']);
Route::get('/reset', [MainController::class, 'viewReset']);
Route::post('/recover', [MainController::class, 'recover']);
Route::get('/resend/{id}', [MainController::class, 'resend']);
Route::post('/reset', [MainController::class, 'reset']);
Route::post('/login', [MainController::class, 'login']);
Route::any('/logout', [MainController::class, 'logout']);

Route::get('/submit', [FrameworkController::class, 'viewSubmit']);
Route::post('/submit', [FrameworkController::class, 'submit']);
Route::post('/framework/query', [FrameworkController::class, 'query']);
Route::post('/framework/query/user', [FrameworkController::class, 'queryUser']);
Route::post('/framework/query/reviews', [FrameworkController::class, 'queryReviews']);
Route::get('/view/{framework}', [FrameworkController::class, 'view']);
Route::get('/framework/{id}/edit', [FrameworkController::class, 'viewEdit']);
Route::post('/framework/{id}/edit', [FrameworkController::class, 'edit']);
Route::get('/framework/{id}/report', [FrameworkController::class, 'reportFramework']);
Route::get('/framework/{id}/delete', [FrameworkController::class, 'deleteFramework']);
Route::post('/framework/{id}/review/send', [FrameworkController::class, 'createReview']);
Route::get('/review/{id}/report', [FrameworkController::class, 'reportReview']);
Route::get('/review/{id}/delete', [FrameworkController::class, 'deleteReview']);

Route::get('/user/{ident}', [MemberController::class, 'showProfile']);
Route::get('/user/{ident}/report', [MemberController::class, 'reportUser']);
Route::get('/profile', [MemberController::class, 'profile']);
Route::post('/profile/save', [MemberController::class, 'saveProfile']);
Route::get('/member/name/valid', [MemberController::class, 'usernameValidity']);
Route::any('/user/query/reviews', [MemberController::class, 'queryReviews']);

Route::get('/notifications/list', [NotificationController::class, 'list']);
Route::get('/notifications/fetch', [NotificationController::class, 'fetch']);
Route::get('/notifications/seen', [NotificationController::class, 'seen']);

Route::get('/admin', [AdminController::class, 'index']);
Route::post('/admin/about/save', [AdminController::class, 'saveAboutContent']);
Route::post('/admin/logo/save', [AdminController::class, 'saveLogo']);
Route::post('/admin/cookieconsent/save', [AdminController::class, 'saveCookieConsent']);
Route::post('/admin/reginfo/save', [AdminController::class, 'saveRegInfo']);
Route::post('/admin/tos/save', [AdminController::class, 'saveTosContent']);
Route::post('/admin/imprint/save', [AdminController::class, 'saveImprintContent']);
Route::post('/admin/headcode/save', [AdminController::class, 'saveHeadCode']);
Route::get('/admin/user/details', [AdminController::class, 'userDetails']);
Route::post('/admin/user/save', [AdminController::class, 'userSave']);
Route::any('/admin/user/{id}/resetpw', [AdminController::class, 'userResetPassword']);
Route::any('/admin/user/{id}/delete', [AdminController::class, 'userDelete']);
Route::any('/admin/user/{id}/lock', [AdminController::class, 'lockUser']);
Route::any('/admin/user/{id}/safe', [AdminController::class, 'setUserSafe']);
Route::any('/admin/approval/{id}/approve', [AdminController::class, 'approveFramework']);
Route::any('/admin/approval/{id}/decline', [AdminController::class, 'declineFramework']);
Route::get('/admin/entity/lock', [AdminController::class, 'lockEntity']);
Route::get('/admin/entity/delete', [AdminController::class, 'deleteEntity']);
Route::get('/admin/entity/safe', [AdminController::class, 'setSafeEntity']);
