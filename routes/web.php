<?php

use App\Http\Controllers\ArticleController;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UploadVideoController;
use App\Http\Controllers\UserController;

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
})->middleware('2fa');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', '2fa'])->name('dashboard');

Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 2단계 인증 미들웨어 라우트
Route::middleware(['2fa'])->group(function () {
  Route::get('/home', [HomeController::class, 'index'])->name('home');
  Route::post('/2fa', function () {
    // 2차 인증을 완료한 사용자만 접근할 수 있음.
    return redirect(route('home'));
  })->name('2fa');
});

Route::get('/complete-registration', [RegisteredUserController::class, 'completeRegistration'])->name('complete-registration');

// Route::get('boards/excel', 'excel')->name('boards.excel');
require __DIR__.'/auth.php';

Route::resource('articles', ArticleController::class);

// 다운로드 라우트
Route::middleware('auth')->group(function () {
  Route::get('/download/{filename}', [DownloadController::class, 'download'])->name('download');
});

// 첨부파일 삭제 라우트
Route::middleware('auth')->group(function () {
  Route::delete('/delete-file/{fileId}', [FileController::class, 'destroy'])->name('file.delete');
});

Route::resource('comments', CommentController::class);

Route::get('profile/{user:username}', [ProfileController::class, 'show'])
->name('profile') // user를 찾는 데 username 컬럼을 사용해라
->where('user', '^[A-Za-z0-9-]+$');

Route::post('follow/{user}', [FollowController::class, 'store'])->name('follow');
Route::delete('follow/{user}', [FollowController::class, 'destroy'])->name('unfollow');

// 동영상 업로드 라우트
Route::middleware('auth')->group(function () {
  Route::get('upload/video', [UploadVideoController::class, 'create'])->name('upload.video');
  Route::post('upload.videos', [UploadVideoController::class, 'store'])->name('upload.videoFile');
  Route::get('videos/{video}/show-video',[UploadVideoController::class,'show'])->name('videos/show-video');
});

// 이메일 인증 라우트
Route::post('/users/email', [UserController::class, 'email'])->name('users.email');
Route::post('/users/verify',[UserController::class, 'verify'])->name('users.verify');
