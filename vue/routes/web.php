<?php

use App\Http\Controllers\admin\RecordController;
use App\Http\Controllers\admin\TariffController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Admin\UserController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/api/residents', [ResidentController::class, 'index'])->name('residents.index');
Route::post('/api/residents', [ResidentController::class, 'store'])->name('residents.store');
Route::get('/api/residents/{resident}', [ResidentController::class, 'show'])->name('residents.show');
Route::put('/api/residents/{resident}', [ResidentController::class, 'update'])->name('residents.update');
Route::delete('/api/residents/{resident}', [ResidentController::class, 'destroy'])->name('resident.destroy');

Route::get('/api/records', [RecordController::class, 'index'])->name('records.index');
Route::post('/api/records', [RecordController::class, 'store'])->name('records.store');
Route::put('/api/records/{record}', [RecordController::class, 'update'])->name('record.update');

Route::get('/api/tariffs', [TariffController::class, 'index'])->name('tariff.index');
Route::post('/api/tariffs', [TariffController::class, 'store'])->name('tariffs.store');
Route::put('/api/tariffs/{tariff}', [TariffController::class, 'update'])->name('tariffs.update');


Route::get('{view}', ApplicationController::class)->where('view', '(.*)');




