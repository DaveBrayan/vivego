<?php

use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\EventDetailController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\SettingsController;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\ManagerController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\CapacityTypeController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\TemplateController;
use App\Http\Controllers\Web\BoxOfficeController;
use App\Http\Controllers\Web\AttendeeController;
use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Marketplace Vive Go
|--------------------------------------------------------------------------
|
| Rutas públicas de navegación, catálogo de eventos y checkout web.
|
*/

Route::get('/', [HomeController::class, 'index'])->name('web.home');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('web.login');
Route::post('/login', [AuthController::class, 'login'])->name('web.login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('web.logout');
Route::get('/evento/{slug}', [EventDetailController::class, 'show'])->name('web.event.detail');
Route::get('/scanner/{event}', [AttendeeController::class, 'mobileScanner'])->name('web.scanner.direct');

// Rutas Protegidas de Administración y Panel de Control
Route::middleware([\App\Http\Middleware\EnsureAdminAuthenticated::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('web.dashboard');

    // Catálogo & Gestión de Eventos, Categorías y Plantillas Canva
    Route::get('/admin/eventos', [EventController::class, 'index'])->name('web.events');
    Route::get('/admin/eventos/crear', [EventController::class, 'create'])->name('web.events.create');
    Route::post('/admin/eventos', [EventController::class, 'store'])->name('web.events.store');
    Route::get('/admin/eventos/{event}/editar', [EventController::class, 'edit'])->name('web.events.edit');
    Route::put('/admin/eventos/{event}', [EventController::class, 'update'])->name('web.events.update');
    Route::delete('/admin/eventos/{event}', [EventController::class, 'destroy'])->name('web.events.destroy');
    Route::post('/admin/eventos/{event}/duplicar', [EventController::class, 'duplicate'])->name('web.events.duplicate');
    Route::get('/admin/eventos/{event}/boletos-registrados', [EventController::class, 'getRegisteredTickets'])->name('web.events.registered_tickets');
    Route::post('/admin/eventos/{event}/registrar-boletos-pdf', [EventController::class, 'storeBatchTickets'])->name('web.events.store_batch_tickets');
    Route::get('/admin/media', [EventController::class, 'getMedia'])->name('web.media.index');
    Route::post('/admin/media/upload', [EventController::class, 'uploadMedia'])->name('web.media.upload');
    Route::post('/admin/media/delete', [EventController::class, 'deleteMedia'])->name('web.media.delete');

    // Taquilla & Ventas POS (Punto de Venta Presencial / Físico)
    Route::get('/admin/taquilla', [BoxOfficeController::class, 'index'])->name('web.box_office');
    Route::get('/admin/taquilla/{event}', [BoxOfficeController::class, 'manage'])->name('web.box_office.manage');
    Route::post('/admin/taquilla/{event}/venta', [BoxOfficeController::class, 'storeSale'])->name('web.box_office.store_sale');
    Route::delete('/admin/taquilla/venta/{sale}', [BoxOfficeController::class, 'destroySale'])->name('web.box_office.destroy_sale');

    // Control de Acceso & Asistentes (Scanner de Boletos QR en Tiempo Real)
    Route::get('/admin/asistentes', [AttendeeController::class, 'index'])->name('web.attendees');
    Route::get('/admin/asistentes/{event}', [AttendeeController::class, 'scanner'])->name('web.attendees.scanner');
    Route::get('/admin/asistentes/{event}/movil', [AttendeeController::class, 'mobileScanner'])->name('web.attendees.mobile_scanner');
    Route::get('/admin/asistentes/{event}/checkins-feed', [AttendeeController::class, 'checkinsFeed'])->name('web.attendees.checkins_feed');
    Route::get('/admin/asistentes/{event}/feed', [AttendeeController::class, 'checkinsFeed'])->name('web.attendees.feed');
    Route::post('/admin/asistentes/{event}/validar-qr', [AttendeeController::class, 'verifyQr'])->name('web.attendees.verify_qr');

    Route::get('/admin/categorias', [CategoryController::class, 'index'])->name('web.categories');
    Route::post('/admin/categorias', [CategoryController::class, 'store'])->name('web.categories.store');
    Route::put('/admin/categorias/{category}', [CategoryController::class, 'update'])->name('web.categories.update');
    Route::delete('/admin/categorias/{category}', [CategoryController::class, 'destroy'])->name('web.categories.destroy');

    Route::get('/admin/plantillas', [TemplateController::class, 'index'])->name('web.templates');
    Route::post('/admin/plantillas', [TemplateController::class, 'store'])->name('web.templates.store');
    Route::put('/admin/plantillas/{template}', [TemplateController::class, 'update'])->name('web.templates.update');
    Route::delete('/admin/plantillas/{template}', [TemplateController::class, 'destroy'])->name('web.templates.destroy');

    // Configuración de Aforo & Zonas
    Route::get('/admin/aforo', [CapacityTypeController::class, 'index'])->name('web.capacity_types');
    Route::post('/admin/aforo', [CapacityTypeController::class, 'store'])->name('web.capacity_types.store');
    Route::put('/admin/aforo/{capacityType}', [CapacityTypeController::class, 'update'])->name('web.capacity_types.update');
    Route::delete('/admin/aforo/{capacityType}', [CapacityTypeController::class, 'destroy'])->name('web.capacity_types.destroy');

    // Configuración General del Sistema
    Route::get('/admin/configuracion', [SettingsController::class, 'index'])->name('web.settings');
    Route::post('/admin/configuracion', [SettingsController::class, 'update'])->name('web.settings.update');

    // Información Empresarial: Compañías
    Route::get('/admin/compania', [CompanyController::class, 'index'])->name('web.companies');
    Route::post('/admin/compania', [CompanyController::class, 'store'])->name('web.companies.store');
    Route::put('/admin/compania/{company}', [CompanyController::class, 'update'])->name('web.companies.update');
    Route::delete('/admin/compania/{company}', [CompanyController::class, 'destroy'])->name('web.companies.destroy');

    // Información Empresarial: Responsables
    Route::get('/admin/responsable', [ManagerController::class, 'index'])->name('web.managers');
    Route::post('/admin/responsable', [ManagerController::class, 'store'])->name('web.managers.store');
    Route::put('/admin/responsable/{manager}', [ManagerController::class, 'update'])->name('web.managers.update');
    Route::delete('/admin/responsable/{manager}', [ManagerController::class, 'destroy'])->name('web.managers.destroy');

    // Administración de Usuarios Admin
    Route::get('/admin/administradores', [AdminController::class, 'index'])->name('web.admins');
    Route::post('/admin/administradores', [AdminController::class, 'store'])->name('web.admins.store');
    Route::put('/admin/administradores/{administrator}', [AdminController::class, 'update'])->name('web.admins.update');
    Route::delete('/admin/administradores/{administrator}', [AdminController::class, 'destroy'])->name('web.admins.destroy');
    Route::post('/admin/administradores/{administrator}/reset-password', [AdminController::class, 'resetPassword'])->name('web.admins.reset-password');
    Route::post('/admin/cambiar-password', [AuthController::class, 'changePassword'])->name('web.change_password');
});

// ==========================================================================
// RUTAS DE OPTIMIZACIÓN WEB DIRECTA PARA SERVIDORES SIN ACCESO SSH / CPANEL
// ==========================================================================
Route::get('/optimizar-sistema', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        \Illuminate\Support\Facades\Artisan::call('config:cache');
        \Illuminate\Support\Facades\Artisan::call('route:cache');
        \Illuminate\Support\Facades\Artisan::call('view:cache');

        return response('
            <div style="font-family: system-ui, -apple-system, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFFFFF;">
                <div style="background: #14141E; border: 1px solid rgba(255,85,0,0.3); padding: 2.5rem; border-radius: 20px; max-width: 520px; width: 100%; box-shadow: 0 20px 50px rgba(0,0,0,0.6); text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">⚡</div>
                    <h2 style="color: #FF5500; font-size: 1.6rem; font-weight: 900; margin: 0 0 0.5rem 0;">¡Sistema ViveGo Optimizado!</h2>
                    <p style="color: #94A3B8; font-size: 0.95rem; margin-bottom: 1.5rem;">Todos los cachés de producción han sido generados exitosamente.</p>
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 1rem; text-align: left; font-size: 0.9rem; color: #10B981; line-height: 1.8; margin-bottom: 1.5rem;">
                        <div>✔ Caché de Configuración (.env) activada</div>
                        <div>✔ Caché de Rutas compilada</div>
                        <div>✔ Caché de Plantillas Blade compilada</div>
                        <div>✔ Caché de Optimización lista</div>
                    </div>
                    <a href="' . route('web.home') . '" style="display: inline-block; background: linear-gradient(135deg, #FF5500, #E04B00); color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 0.85rem 1.8rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(255,85,0,0.4);">
                        Ir a la Página Principal
                    </a>
                </div>
            </div>
        ', 200)->header('Content-Type', 'text/html');
    } catch (\Exception $e) {
        return response('<h3 style="color:red;">Error al optimizar:</h3><pre>' . $e->getMessage() . '</pre>', 500);
    }
});

Route::get('/limpiar-cache', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return '<div style="font-family: sans-serif; padding: 2rem; background: #14141E; color: #10B981; text-align: center;"><h2 style="color:#FF5500;">✔ Caché limpiada con éxito</h2><p><a href="/" style="color:#FFF;">Volver al Inicio</a></p></div>';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

