<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\JasaController;
use App\Http\Controllers\KategoriJasaController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/sigin', [AccountController::class, 'login']);
Route::post('/register', [AccountController::class, 'register']);
Route::post('/verify-otp', [AccountController::class, 'verifyOtp']);





Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/daftar/freelancer',[FreelancerController::class, 'store']);
    Route::post('/order/freelancer',[FreelancerController::class, 'order']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::resource('kategori', KategoriJasaController::class);
    Route::get('/jasa',[JasaController::class, 'index']);
    Route::post('/jasa',[JasaController::class, 'store']);
    Route::get('/jasa/{id}',[JasaController::class, 'show']);
    Route::put('/jasa/{id}',[JasaController::class, 'update']);
    Route::delete('/jasa/{id}',[JasaController::class, 'destroy']);

});