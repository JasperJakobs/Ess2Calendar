<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\SoftbrickController;
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

Route::get('/', [Controller::class, 'home'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/settings', [Controller::class, 'settings'])->middleware(['auth', 'verified'])->name('settings');

Route::post('/settings/softbrick', [SoftbrickController::class, 'updateSoftbrick'])->middleware(['auth', 'verified'])->name("post:softbrick");

Route::get('/cal/{uuid}', [Controller::class, 'calendar']);

require __DIR__.'/auth.php';
