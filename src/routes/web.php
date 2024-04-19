<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthCheckResultController;

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

Route::get('/', [HealthCheckResultController::class, 'index'])->name('top');
Route::post('/health_check_results/import', [HealthCheckResultController::class, 'import'])->name('health_check_results.import');
Route::get('/health_check_results/search', [HealthCheckResultController::class, 'search'])->name('health_check_results.search');
Route::get('/test-email', [HealthCheckResultController::class, 'testEmail']);
