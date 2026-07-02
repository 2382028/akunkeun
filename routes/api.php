<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPerjadinController;
use App\Http\Controllers\AdminKegiatanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('/cek-mobilitas', [AdminPerjadinController::class, 'cekMobilitasAPI']);
Route::get('/cek-mobilitas/kegiatan', [AdminKegiatanController::class, 'cekMobilitasAPI']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
