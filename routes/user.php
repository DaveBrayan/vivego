<?php

use App\Http\Controllers\User\NominationController;
use App\Http\Controllers\User\TicketDownloadController;
use App\Http\Controllers\User\WalletController;
use App\Http\Middleware\EnsureTicketIsNominated;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Routes - Billetera "Mis Entradas"
|--------------------------------------------------------------------------
|
| Rutas autenticadas del portal de usuario final para gestión de boletos,
| nominación obligatoria, transferencias y visualización de QR.
|
*/

Route::middleware(['auth:sanctum'])->prefix('user')->name('user.')->group(function () {
    // Billetera principal
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/ticket/{ticket}', [WalletController::class, 'show'])->name('wallet.show');

    // Nominación y Transferencia
    // Route::post('/ticket/{ticket}/nominate', [NominationController::class, 'nominate'])->name('ticket.nominate');
    // Route::post('/ticket/{ticket}/transfer', [NominationController::class, 'transfer'])->name('ticket.transfer');

    // Visores protegidos (requieren que el ticket esté nominado previamente)
    // Route::middleware([EnsureTicketIsNominated::class])->group(function () {
    //     Route::get('/ticket/{ticket}/qr-dynamic', [TicketDownloadController::class, 'dynamicQr'])->name('ticket.qr');
    //     Route::get('/ticket/{ticket}/download-pdf', [TicketDownloadController::class, 'downloadPdf'])->name('ticket.pdf');
    // });
});
