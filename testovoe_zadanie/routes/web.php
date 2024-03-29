<?php

use App\Http\Controllers\Admin\InfoController;
use App\Http\Controllers\Admin\RecordController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\admin\TariffController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
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


Auth::routes();


Route::middleware('auth')->group(function () {

    Route::get('/api/info', [InfoController::class, 'index'])->name('info.index');
    Route::get('/api/info/{info}', [InfoController::class, 'show'])->name('info.show');

    Route::get('/api/residents', [ResidentController::class, 'index'])->name('residents.index');
    Route::post('/api/residents', [ResidentController::class, 'store'])->name('residents.stores');
    Route::get('/api/residents/{resident}', [ResidentController::class, 'show'])->name('residents.show');
    Route::put('/api/residents/{resident}', [ResidentController::class, 'update'])->name('residents.update');
    Route::delete('/api/residents/{resident}', [ResidentController::class, 'destroy'])->name('resident.destroy');

    Route::get('/api/records', [RecordController::class, 'index'])->name('records.index');
    Route::post('/api/records', [RecordController::class, 'store'])->name('records.stores');
    Route::put('/api/records/{record}', [RecordController::class, 'update'])->name('record.update');

    Route::get('/api/tariffs', [TariffController::class, 'index'])->name('tariff.index');
    Route::post('/api/tariffs', [TariffController::class, 'store'])->name('tariffs.stores');
    Route::put('/api/tariffs/{tariff}', [TariffController::class, 'update'])->name('tariffs.update');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/api/user', [UserController::class, 'index'])->name('user.index');

    Route::get('{view}', ApplicationController::class)->where('view', '(.*)');

});

Route::middleware('guest')->group(function () {

});



