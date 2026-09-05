<?php

use App\Http\Controllers\Api\Gate\DeviceApiController;
use App\Http\Middleware\VerifyGateDeviceToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Gate API Routes - Control de Acceso y App Móvil Flutter (ViveGo Handhelds)
|--------------------------------------------------------------------------
*/

Route::prefix('api/gate')->name('api.gate.')->group(function () {
    // 1. Vinculación inicial de dispositivo móvil mediante QR escaneado
    Route::post('/pair', [DeviceApiController::class, 'pair'])->name('pair');
    Route::get('/pair-check/{device}', function (\App\Models\Device $device) {
        $isPaired = ($device->status === 'active');
        return response()->json([
            'success' => true,
            'paired' => $isPaired,
            'is_active' => $isPaired,
            'status' => $device->status,
            'device_name' => $device->name,
            'device_model' => $device->device_model,
            'paired_at' => $device->paired_at ? $device->paired_at->format('d/m/Y H:i:s') : null,
        ]);
    })->name('pair_check');

    // 2. Endpoints protegidos por token de dispositivo activo
    Route::middleware([VerifyGateDeviceToken::class])->group(function () {
        Route::get('/status', [DeviceApiController::class, 'status'])->name('status');
        Route::get('/events', [DeviceApiController::class, 'events'])->name('events');
        Route::post('/scan', [DeviceApiController::class, 'scan'])->name('scan');
        Route::post('/unpair', [DeviceApiController::class, 'unpair'])->name('unpair');
    });
});
