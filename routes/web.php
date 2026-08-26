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
use App\Http\Controllers\Web\CampaignController;
use App\Http\Controllers\Web\CouponController;
use App\Http\Controllers\Web\CustomerController;
use App\Http\Controllers\Web\CustomerPortalController;
use App\Http\Controllers\Web\PaymentGatewayController;
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

// Terminal Móvil de Control de Acceso & Scanner QR
Route::get('/scanner/{event}', [AttendeeController::class, 'mobileScanner'])->name('web.scanner.direct');
Route::post('/scanner/{event}/validar-qr', [AttendeeController::class, 'verifyQr'])->name('web.scanner.verify_qr');
Route::post('/scanner/{event}/anular-escaneo/{ticket}', [AttendeeController::class, 'resetCheckin'])->name('web.scanner.reset_checkin');
Route::delete('/scanner/{event}/anular-escaneo/{ticket}', [AttendeeController::class, 'resetCheckin'])->name('web.scanner.destroy_checkin');
Route::get('/scanner/{event}/checkins-feed', [AttendeeController::class, 'checkinsFeed'])->name('web.scanner.checkins_feed');
Route::get('/scanner/{event}/feed', [AttendeeController::class, 'checkinsFeed'])->name('web.scanner.feed');

// Portal del Cliente (Mis Boletos & Mis Recibos)
Route::get('/cliente/login', [CustomerPortalController::class, 'showLoginForm'])->name('customer.login');
Route::post('/cliente/login', [CustomerPortalController::class, 'login'])->name('web.customer.login');
Route::post('/cliente/logout', [CustomerPortalController::class, 'logout'])->name('web.customer.logout');
Route::post('/cliente/cerrar-sesion', [CustomerPortalController::class, 'logout'])->name('customer.logout');
Route::get('/mi-cuenta/boletos', [CustomerPortalController::class, 'myTickets'])->name('web.customer.tickets');
Route::get('/mi-cuenta/mis-boletos', [CustomerPortalController::class, 'myTickets'])->name('customer.my_tickets');
Route::get('/mi-cuenta/recibos', [CustomerPortalController::class, 'myReceipts'])->name('web.customer.receipts');
Route::get('/mi-cuenta/mis-recibos', [CustomerPortalController::class, 'myReceipts'])->name('customer.my_receipts');
Route::get('/mi-cuenta/boleto/{sale}/pdf', [CustomerPortalController::class, 'downloadTicketPdf'])->name('web.customer.ticket_pdf');
Route::get('/mi-cuenta/boleto/{sale}/descargar', [CustomerPortalController::class, 'downloadTicketPdf'])->name('customer.ticket_pdf');
Route::post('/mi-cuenta/boleto/{sale}/enviar-correo', [CustomerPortalController::class, 'emailTicketPdf'])->name('web.customer.ticket_email');

// Pasarela de Pagos & Carrito de Compras Checkout
Route::match(['get', 'post'], '/checkout', [CheckoutController::class, 'show'])->name('web.checkout');
Route::post('/checkout/validar-cupon', [CheckoutController::class, 'validateCoupon'])->name('web.checkout.validate_coupon');
Route::get('/checkout/confirmacion/{sale}', [CheckoutController::class, 'confirmation'])->name('web.checkout.confirmation');
Route::post('/checkout/izipay/iniciar', [CheckoutController::class, 'initiateIzipay'])->name('web.checkout.izipay_initiate');
Route::post('/checkout/izipay/completar', [CheckoutController::class, 'completeIzipayPayment'])->name('web.checkout.izipay_complete');
Route::post('/checkout/culqi/iniciar', [CheckoutController::class, 'initiateCulqi'])->name('web.checkout.culqi_initiate');
Route::post('/checkout/culqi/completar', [CheckoutController::class, 'completeCulqiPayment'])->name('web.checkout.culqi_complete');
Route::post('/checkout/culqi/consultar-orden', [CheckoutController::class, 'checkCulqiOrderStatus'])->name('web.checkout.culqi_order_status');
Route::post('/checkout/cortesia/completar', [CheckoutController::class, 'completeCourtesyOrder'])->name('web.checkout.courtesy_complete');
Route::post('/api/culqi/webhook', [CheckoutController::class, 'culqiWebhook'])->name('api.culqi.webhook');

// Rutas Protegidas de Administración y Panel de Control
Route::middleware([\App\Http\Middleware\EnsureAdminAuthenticated::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('web.dashboard');

    // Directorio & Gestión de Clientes
    Route::get('/admin/clientes', [CustomerController::class, 'index'])->name('web.customers');
    Route::get('/admin/clientes/{id}/detalle', [CustomerController::class, 'getCustomerDetails'])->name('web.customers.details');
    Route::post('/admin/clientes/{id}/reset-password', [CustomerController::class, 'resetPassword'])->name('web.customers.reset_password');
    Route::delete('/admin/clientes/{id}', [CustomerController::class, 'destroy'])->name('web.customers.destroy');

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
    Route::post('/admin/asistentes/{event}/anular-escaneo/{ticket}', [AttendeeController::class, 'resetCheckin'])->name('web.attendees.reset_checkin');
    Route::delete('/admin/asistentes/{event}/anular-escaneo/{ticket}', [AttendeeController::class, 'resetCheckin'])->name('web.attendees.destroy_checkin');

    Route::get('/admin/categorias', [CategoryController::class, 'index'])->name('web.categories');
    Route::post('/admin/categorias', [CategoryController::class, 'store'])->name('web.categories.store');
    Route::put('/admin/categorias/{category}', [CategoryController::class, 'update'])->name('web.categories.update');
    Route::delete('/admin/categorias/{category}', [CategoryController::class, 'destroy'])->name('web.categories.destroy');

    // Módulos de Marketing: Campañas Promocionales & Cupones de Descuento
    Route::get('/admin/campanas', [CampaignController::class, 'index'])->name('web.campaigns');
    Route::post('/admin/campanas', [CampaignController::class, 'store'])->name('web.campaigns.store');
    Route::put('/admin/campanas/{campaign}', [CampaignController::class, 'update'])->name('web.campaigns.update');
    Route::post('/admin/campanas/{campaign}/toggle', [CampaignController::class, 'toggleStatus'])->name('web.campaigns.toggle');
    Route::delete('/admin/campanas/{campaign}', [CampaignController::class, 'destroy'])->name('web.campaigns.destroy');

    Route::get('/admin/cupones', [CouponController::class, 'index'])->name('web.coupons');
    Route::post('/admin/cupones', [CouponController::class, 'store'])->name('web.coupons.store');
    Route::put('/admin/cupones/{coupon}', [CouponController::class, 'update'])->name('web.coupons.update');
    Route::post('/admin/cupones/{coupon}/toggle', [CouponController::class, 'toggleStatus'])->name('web.coupons.toggle');
    Route::delete('/admin/cupones/{coupon}', [CouponController::class, 'destroy'])->name('web.coupons.destroy');

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

    // Métodos de Pago & Pasarelas (Izipay, Culqi, etc.)
    Route::get('/admin/metodos-pago', [PaymentGatewayController::class, 'index'])->name('web.payment_methods');
    Route::post('/admin/metodos-pago/izipay', [PaymentGatewayController::class, 'updateIzipay'])->name('web.payment_methods.update_izipay');
    Route::post('/admin/metodos-pago/izipay/test', [PaymentGatewayController::class, 'testIzipayConnection'])->name('web.payment_methods.test_izipay');
    Route::post('/admin/metodos-pago/culqi', [PaymentGatewayController::class, 'updateCulqi'])->name('web.payment_methods.update_culqi');
    Route::post('/admin/metodos-pago/culqi/test', [PaymentGatewayController::class, 'testCulqiConnection'])->name('web.payment_methods.test_culqi');

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
// RUTAS DE OPTIMIZACIÓN Y MIGRACIONES WEB PARA SERVIDORES SIN SSH / CPANEL
// ==========================================================================
Route::get('/optimizar-sistema', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('config:cache');
        \Illuminate\Support\Facades\Artisan::call('route:cache');
        \Illuminate\Support\Facades\Artisan::call('view:cache');

        return response('
            <div style="font-family: system-ui, -apple-system, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFFFFF;">
                <div style="background: #14141E; border: 1px solid rgba(255,85,0,0.3); padding: 2.5rem; border-radius: 20px; max-width: 600px; width: 100%; box-shadow: 0 20px 50px rgba(0,0,0,0.6); text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">⚡</div>
                    <h2 style="color: #FF5500; font-size: 1.6rem; font-weight: 900; margin: 0 0 0.5rem 0;">¡Sistema ViveGo Optimizado & Migrado!</h2>
                    <p style="color: #94A3B8; font-size: 0.95rem; margin-bottom: 1.5rem;">Las migraciones de base de datos y todos los cachés han sido procesados con éxito.</p>
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 1.25rem; text-align: left; font-size: 0.9rem; color: #10B981; line-height: 1.8; margin-bottom: 1.5rem;">
                        <div>✔ Migraciones de Base de Datos ejecutadas (migrate --force)</div>
                        <div>✔ Caché de Configuración (.env) activada</div>
                        <div>✔ Caché de Rutas compilada</div>
                        <div>✔ Caché de Plantillas Blade compilada</div>
                        <div>✔ Caché de Optimización lista</div>
                    </div>
                    ' . (!empty(trim($migrateOutput)) ? '<div style="background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 0.75rem 1rem; text-align: left; font-family: monospace; font-size: 0.8rem; color: #CBD5E1; max-height: 140px; overflow-y: auto; margin-bottom: 1.5rem; white-space: pre-wrap;">' . htmlspecialchars($migrateOutput) . '</div>' : '') . '
                    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                        <a href="' . route('web.home') . '" style="display: inline-block; background: linear-gradient(135deg, #FF5500, #E04B00); color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 0.85rem 1.8rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(255,85,0,0.4);">
                            Ir a la Página Principal
                        </a>
                        <a href="' . route('web.payment_methods') . '" style="display: inline-block; background: #1E1E2E; border: 1px solid rgba(255,255,255,0.15); color: #FFFFFF; font-weight: 700; text-decoration: none; padding: 0.85rem 1.4rem; border-radius: 12px;">
                            Configurar Pasarelas
                        </a>
                    </div>
                </div>
            </div>
        ', 200)->header('Content-Type', 'text/html');
    } catch (\Exception $e) {
        return response('<div style="font-family: sans-serif; padding: 2rem; background: #14141E; color: #EF4444;"><h3 style="color:#EF4444;">Error al optimizar y migrar:</h3><pre style="background: #000; padding: 1rem; border-radius: 8px; color: #FCA5A5;">' . htmlspecialchars($e->getMessage()) . '</pre></div>', 500);
    }
});

Route::get('/ejecutar-migraciones', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();

        return response('
            <div style="font-family: system-ui, -apple-system, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFFFFF;">
                <div style="background: #14141E; border: 1px solid rgba(16,185,129,0.3); padding: 2.5rem; border-radius: 20px; max-width: 600px; width: 100%; box-shadow: 0 20px 50px rgba(0,0,0,0.6); text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🗄️</div>
                    <h2 style="color: #10B981; font-size: 1.6rem; font-weight: 900; margin: 0 0 0.5rem 0;">¡Migraciones Ejecutadas con Éxito!</h2>
                    <p style="color: #94A3B8; font-size: 0.95rem; margin-bottom: 1.5rem;">Todas las tablas de la base de datos están al día.</p>
                    <div style="background: rgba(0,0,0,0.4); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 1rem; text-align: left; font-family: monospace; font-size: 0.85rem; color: #10B981; max-height: 200px; overflow-y: auto; margin-bottom: 1.5rem; white-space: pre-wrap;">' . htmlspecialchars($output ?: 'Nothing to migrate. Database is already up to date.') . '</div>
                    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                        <a href="' . route('web.home') . '" style="display: inline-block; background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 0.85rem 1.8rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(16,185,129,0.4);">
                            Ir al Inicio
                        </a>
                        <a href="/optimizar-sistema" style="display: inline-block; background: #FF5500; color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 0.85rem 1.4rem; border-radius: 12px;">
                            ⚡ Optimizar Todo el Sistema
                        </a>
                    </div>
                </div>
            </div>
        ', 200)->header('Content-Type', 'text/html');
    } catch (\Exception $e) {
        return response('<div style="font-family: sans-serif; padding: 2rem; background: #14141E; color: #EF4444;"><h3 style="color:#EF4444;">Error en migraciones:</h3><pre style="background: #000; padding: 1rem; border-radius: 8px; color: #FCA5A5;">' . htmlspecialchars($e->getMessage()) . '</pre></div>', 500);
    }
});

Route::get('/migrar-sistema', function () {
    return redirect('/ejecutar-migraciones');
});

Route::get('/limpiar-cache', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        return '<div style="font-family: sans-serif; padding: 2rem; background: #14141E; color: #10B981; text-align: center;"><h2 style="color:#FF5500;">✔ Caché limpiada con éxito</h2><p><a href="/" style="color:#FFF;">Volver al Inicio</a></p></div>';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

