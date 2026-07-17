<?php

use App\Http\Controllers\LandServiceController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [LandServiceController::class, 'home'])->name('home');
Route::get('/search-land-record', [LandServiceController::class, 'search'])->name('land.search');
Route::get('/mutation-tracking', [LandServiceController::class, 'trackMutation'])->name('mutation.track');
Route::match(['get', 'post'], '/khajna-apply', [LandServiceController::class, 'khajnaApply'])->name('khajna.apply');
Route::match(['get', 'post'], '/mutation-apply', [LandServiceController::class, 'mutationApply'])->name('mutation.apply');

Route::prefix('district-admin')->name('district-admin.')->group(function () {
	Route::middleware('district.guest')->group(function () {
		Route::get('/login', [LandServiceController::class, 'adminLoginForm'])->name('login');
		Route::post('/login', [LandServiceController::class, 'adminLogin'])->name('login.attempt');
	});

	Route::middleware('district.admin')->group(function () {
		Route::get('/', [LandServiceController::class, 'adminDashboard'])->name('dashboard');
		Route::post('/logout', [LandServiceController::class, 'adminLogout'])->name('logout');

		Route::post('/khajna/{khajnaApplication}/action', [LandServiceController::class, 'updateKhajnaStatus'])->name('khajna.action');
		Route::post('/mutation/{mutationApplication}/action', [LandServiceController::class, 'updateMutationStatus'])->name('mutation.action');

		Route::post('/notices', [LandServiceController::class, 'storeNotice'])->name('notices.store');
		Route::patch('/notices/{notice}', [LandServiceController::class, 'updateNotice'])->name('notices.update');
		Route::delete('/notices/{notice}', [LandServiceController::class, 'destroyNotice'])->name('notices.destroy');
	});
});
