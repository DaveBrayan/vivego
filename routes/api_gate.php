<?php

use App\Http\Controllers\Api\Gate\AuthDeviceController;
use App\Http\Controllers\Api\Gate\DownloadHashesController;
use App\Http\Controllers\Api\Gate\ScanLogController;
use App\Http\Middleware\VerifyGateDeviceToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Gate API Routes - Control de Acceso y Handhelds
|--------------------------------------------------------------------------
|
| Endpoints para la App de escaneo en puerta (handhelds). Incluye autenticación de terminales,
| descarga de hashes para validación offline y sincronización de lecturas.
|
*/

Route::prefix('api/gate')->name('api.gate.')->group(function () {
    // Autenticación de dispositivo handheld
    // Route::post('/auth-device', [AuthDeviceController::class, 'authenticate'])->name('auth');

    // Endpoints protegidos por token de dispositivo de puerta
    // Route::middleware([VerifyGateDeviceToken::class])->group(function () {
    //     Route::get('/event/{event}/download-hashes', [DownloadHashesController::class, 'download'])->name('download_hashes');
    //     Route::post('/scan', [ScanLogController::class, 'scan'])->name('scan');
    //     Route::post('/sync-offline', [ScanLogController::class, 'syncBatch'])->name('sync_offline');
    // });
});
