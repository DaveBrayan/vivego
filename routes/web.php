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
use App\Http\Controllers\Web\LegalController;
use App\Http\Controllers\Web\ClaimBookController;
use App\Http\Controllers\Web\ClaimAdminController;
use App\Http\Controllers\Web\DeviceController;
use App\Http\Controllers\Web\EmailLogController;
use App\Http\Controllers\Web\CheckoutLogController;
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
Route::post('/recuperar-password', [AuthController::class, 'recoverPassword'])->name('web.password.recover');
Route::post('/actualizar-password-temporal', [AuthController::class, 'updateTemporaryPassword'])->name('web.password.update_temp');
Route::get('/evento/{slug}', [EventDetailController::class, 'show'])->name('web.event.detail');

// Páginas Legales & Políticas Institucionales
Route::get('/terminos-y-condiciones', [LegalController::class, 'terms'])->name('web.terms');
Route::get('/politicas-de-privacidad', [LegalController::class, 'privacy'])->name('web.privacy');
Route::get('/politica-de-cookies', [LegalController::class, 'cookies'])->name('web.cookies');

// Libro de Reclamaciones Virtual (INDECOPI / Hoja de Reclamación)
Route::get('/libro-de-reclamaciones', [ClaimBookController::class, 'index'])->name('web.claim_book');
Route::post('/libro-de-reclamaciones', [ClaimBookController::class, 'store'])->name('web.claim_book.store');
Route::get('/libro-de-reclamaciones/constancia/{code}', [ClaimBookController::class, 'confirmation'])->name('web.claim_book.confirmation');

// Terminal Móvil de Control de Acceso & Scanner QR
Route::get('/scanner/{event}', [AttendeeController::class, 'mobileScanner'])->name('web.scanner.direct');
Route::post('/scanner/{event}/validar-qr', [AttendeeController::class, 'verifyQr'])->name('web.scanner.verify_qr');
Route::post('/scanner/{event}/claim-device', [AttendeeController::class, 'claimDeviceSession'])->name('web.scanner.claim_device');
Route::post('/scanner/{event}/release-device', [AttendeeController::class, 'releaseDeviceSession'])->name('web.scanner.release_device');
Route::post('/scanner/{event}/devices-status', [AttendeeController::class, 'getDevicesStatus'])->name('web.scanner.devices_status');
Route::get('/scanner/{event}/device-logs', [AttendeeController::class, 'getDeviceScanLogs'])->name('web.scanner.device_logs');
Route::post('/scanner/{event}/device-logs/clear', [AttendeeController::class, 'clearDeviceScanLogs'])->name('web.scanner.clear_device_logs');
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
Route::get('/mi-cuenta/boleto/{sale}/opciones-mejora', [CustomerPortalController::class, 'getUpgradeOptions'])->name('web.customer.ticket_upgrade_options');
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
Route::match(['get', 'post'], '/api/culqi/webhook', [CheckoutController::class, 'culqiWebhook'])->name('api.culqi.webhook');
Route::match(['get', 'post'], '/api/culqi/webhook/', [CheckoutController::class, 'culqiWebhook']);

// Rutas Protegidas de Administración y Panel de Control
Route::middleware([\App\Http\Middleware\EnsureAdminAuthenticated::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('web.dashboard');

    // Directorio & Gestión de Clientes
    Route::get('/admin/clientes', [CustomerController::class, 'index'])->name('web.customers');
    Route::get('/admin/clientes/{id}/detalle', [CustomerController::class, 'getCustomerDetails'])->name('web.customers.details');
    Route::put('/admin/clientes/{id}', [CustomerController::class, 'update'])->name('web.customers.update');
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
    Route::post('/admin/eventos/{event}/sincronizar-boletos', [EventController::class, 'syncTickets'])->name('web.events.sync_tickets');
    Route::post('/admin/eventos/{event}/registrar-boletos-pdf', [EventController::class, 'storeBatchTickets'])->name('web.events.store_batch_tickets');
    Route::delete('/admin/eventos/{event}/eliminar-boletos-pdf', [EventController::class, 'destroyBatchTickets'])->name('web.events.destroy_batch_tickets');
    Route::get('/admin/media', [EventController::class, 'getMedia'])->name('web.media.index');
    Route::post('/admin/media/upload', [EventController::class, 'uploadMedia'])->name('web.media.upload');
    Route::post('/admin/media/delete', [EventController::class, 'deleteMedia'])->name('web.media.delete');

    // Taquilla & Ventas POS (Punto de Venta Presencial / Físico)
    Route::get('/admin/taquilla', [BoxOfficeController::class, 'index'])->name('web.box_office');
    Route::get('/admin/taquilla/{event}', [BoxOfficeController::class, 'manage'])->name('web.box_office.manage');
    Route::get('/admin/taquilla/{event}/reporte-excel', [BoxOfficeController::class, 'exportSalesReport'])->name('web.box_office.export_sales_report');
    Route::post('/admin/taquilla/{event}/finalizar', [BoxOfficeController::class, 'finalizeEvent'])->name('web.box_office.finalize_event');
    Route::post('/admin/taquilla/{event}/venta', [BoxOfficeController::class, 'storeSale'])->name('web.box_office.store_sale');
    Route::delete('/admin/taquilla/venta/{sale}', [BoxOfficeController::class, 'destroySale'])->name('web.box_office.destroy_sale');
    Route::post('/admin/taquilla/venta/{sale}/enviar-correo', [BoxOfficeController::class, 'emailTicketPdf'])->name('web.box_office.email_ticket');
    Route::post('/admin/taquilla/venta/{sale}/actualizar-correo', [BoxOfficeController::class, 'updateSaleEmail'])->name('web.box_office.update_email');

    // Control de Acceso & Asistentes (Scanner de Boletos QR en Tiempo Real)
    Route::get('/admin/asistentes', [AttendeeController::class, 'index'])->name('web.attendees');
    Route::get('/admin/asistentes/{event}', [AttendeeController::class, 'scanner'])->name('web.attendees.scanner');
    Route::get('/admin/asistentes/{event}/movil', [AttendeeController::class, 'mobileScanner'])->name('web.attendees.mobile_scanner');
    Route::get('/admin/asistentes/{event}/checkins-feed', [AttendeeController::class, 'checkinsFeed'])->name('web.attendees.checkins_feed');
    Route::get('/admin/asistentes/{event}/feed', [AttendeeController::class, 'checkinsFeed'])->name('web.attendees.feed');
    Route::post('/admin/asistentes/{event}/validar-qr', [AttendeeController::class, 'verifyQr'])->name('web.attendees.verify_qr');
    Route::post('/admin/asistentes/{event}/claim-device', [AttendeeController::class, 'claimDeviceSession'])->name('web.attendees.claim_device');
    Route::post('/admin/asistentes/{event}/release-device', [AttendeeController::class, 'releaseDeviceSession'])->name('web.attendees.release_device');
    Route::post('/admin/asistentes/{event}/devices-status', [AttendeeController::class, 'getDevicesStatus'])->name('web.attendees.devices_status');
    Route::get('/admin/asistentes/{event}/device-logs', [AttendeeController::class, 'getDeviceScanLogs'])->name('web.attendees.device_logs');
    Route::post('/admin/asistentes/{event}/device-logs/clear', [AttendeeController::class, 'clearDeviceScanLogs'])->name('web.attendees.clear_device_logs');
    Route::post('/admin/asistentes/{event}/anular-escaneo/{ticket}', [AttendeeController::class, 'resetCheckin'])->name('web.attendees.reset_checkin');
    Route::delete('/admin/asistentes/{event}/anular-escaneo/{ticket}', [AttendeeController::class, 'resetCheckin'])->name('web.attendees.destroy_checkin');

    // Control de Dispositivos Móviles & Handhelds (App Flutter)
    Route::get('/admin/dispositivos', [DeviceController::class, 'index'])->name('web.devices');
    Route::post('/admin/dispositivos', [DeviceController::class, 'store'])->name('web.devices.store');
    Route::put('/admin/dispositivos/{device}', [DeviceController::class, 'update'])->name('web.devices.update');
    Route::delete('/admin/dispositivos/{device}', [DeviceController::class, 'destroy'])->name('web.devices.destroy');
    Route::post('/admin/dispositivos/{device}/regenerar-qr', [DeviceController::class, 'regenerateQr'])->name('web.devices.regenerate_qr');
    Route::post('/admin/dispositivos/{device}/toggle-status', [DeviceController::class, 'toggleStatus'])->name('web.devices.toggle_status');

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

    // Registro de Correos Electrónicos (Auditoría de Envíos y Diagnóstico de Fallas)
    Route::get('/admin/registro-correos', [EmailLogController::class, 'index'])->name('web.email_logs');
    Route::get('/admin/registro-correos/{id}', [EmailLogController::class, 'show'])->name('web.email_logs.show');
    Route::post('/admin/registro-correos/{id}/reenviar', [EmailLogController::class, 'resend'])->name('web.email_logs.resend');
    Route::delete('/admin/registro-correos/{id}', [EmailLogController::class, 'destroy'])->name('web.email_logs.destroy');

    // Logs de Pagos & Auditoría de Checkout
    Route::get('/admin/logs-pagos', [CheckoutLogController::class, 'index'])->name('web.checkout_logs');
    Route::get('/admin/logs-pagos/descargar', [CheckoutLogController::class, 'download'])->name('web.checkout_logs.download');
    Route::post('/admin/logs-pagos/limpiar', [CheckoutLogController::class, 'clear'])->name('web.checkout_logs.clear');
    Route::get('/admin/logs-pagos/feed', [CheckoutLogController::class, 'apiFeed'])->name('web.checkout_logs.feed');

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

    // Libro de Reclamaciones (Atención de Reclamos y Quejas)
    Route::get('/admin/reclamaciones', [ClaimAdminController::class, 'index'])->name('web.claims');
    Route::get('/admin/reclamaciones/{id}/detalle', [ClaimAdminController::class, 'show'])->name('web.claims.details');
    Route::post('/admin/reclamaciones/{id}/responder', [ClaimAdminController::class, 'respond'])->name('web.claims.respond');
    Route::post('/admin/reclamaciones/{id}/estado', [ClaimAdminController::class, 'updateStatus'])->name('web.claims.update_status');
    Route::delete('/admin/reclamaciones/{id}', [ClaimAdminController::class, 'destroy'])->name('web.claims.destroy');

    // Administración de Usuarios Admin
    Route::get('/admin/administradores', [AdminController::class, 'index'])->name('web.admins');
    Route::post('/admin/administradores', [AdminController::class, 'store'])->name('web.admins.store');
    Route::put('/admin/administradores/{administrator}', [AdminController::class, 'update'])->name('web.admins.update');
    Route::delete('/admin/administradores/{administrator}', [AdminController::class, 'destroy'])->name('web.admins.destroy');
    Route::post('/admin/administradores/{administrator}/reset-password', [AdminController::class, 'resetPassword'])->name('web.admins.reset-password');
    Route::post('/admin/cambiar-password', [AuthController::class, 'changePassword'])->name('web.change_password');
});

// ==========================================================================
Route::match(['get', 'post'], '/optimizar-sistema', function (\Illuminate\Http\Request $request) {
    // 1. Manejador de peticiones AJAX / POST
    if ($request->isMethod('post') || $request->has('action')) {
        $action = $request->input('action');

        // ACCIÓN A: Procesar Orden Culqi y emitir boletos/correo
        if ($action === 'process_culqi') {
            $orderId = trim((string)$request->input('order_id', ''));
            if (empty($orderId)) {
                return response()->json(['success' => false, 'message' => 'El Order ID de Culqi es obligatorio (ej: ord_live_...).'], 400);
            }

            try {
                $culqiService = app(\App\Services\CulqiService::class);
                $controller = app(\App\Http\Controllers\Web\CheckoutController::class);

                $overrides = array_filter([
                    'customer_email' => $request->filled('customer_email') ? trim($request->input('customer_email')) : null,
                    'customer_name' => $request->filled('customer_name') ? trim($request->input('customer_name')) : null,
                    'customer_doc' => $request->filled('customer_doc') ? trim($request->input('customer_doc')) : null,
                ]);

                $sale = $controller->processCulqiPaidOrder($orderId, $culqiService, $overrides);

                if ($sale) {
                    $ticketsData = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data, true);
                    $destEmail = $ticketsData['customer_email'] ?? $sale->buyer_email ?? 'Sin correo registrado';
                    return response()->json([
                        'success' => true,
                        'message' => "¡Orden {$orderId} procesada exitosamente!",
                        'sale_id' => $sale->id,
                        'receipt_number' => $sale->receipt_number,
                        'buyer_name' => $sale->buyer_name,
                        'buyer_dni' => $sale->buyer_dni,
                        'buyer_email' => $destEmail,
                        'zone_name' => $sale->zone_name,
                        'quantity' => $sale->quantity,
                        'total_amount' => 'S/ ' . number_format($sale->total_amount, 2),
                        'confirmation_url' => route('web.checkout.confirmation', $sale->id),
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "No se pudo procesar la orden {$orderId}. Verifica que esté pagada/depositada en Culqi o revisa storage/logs/laravel.log.",
                    ], 422);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al procesar orden: ' . $e->getMessage(),
                ], 500);
            }
        }

        // ACCIÓN B: Ejecutar Comando Artisan desde la Consola Web
        if ($action === 'run_artisan') {
            $cmd = trim((string)$request->input('command', ''));
            // Remover prefijo 'php artisan' si fue escrito
            $cmd = preg_replace('/^(?:php\s+)?artisan\s+/i', '', $cmd);

            if (empty($cmd)) {
                return response()->json(['success' => false, 'output' => 'Debes ingresar un comando para ejecutar.'], 400);
            }

            try {
                \Illuminate\Support\Facades\Artisan::call($cmd);
                $out = \Illuminate\Support\Facades\Artisan::output();
                return response()->json([
                    'success' => true,
                    'command' => $cmd,
                    'output' => !empty(trim($out)) ? $out : "✔ Comando '{$cmd}' ejecutado con éxito (sin salida de texto).",
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => false,
                    'command' => $cmd,
                    'output' => "❌ Error ejecutando '{$cmd}': " . $e->getMessage(),
                ], 500);
            }
        }
    }

    // 2. Ejecutar optimización y migraciones estándar
    $migrateOutput = '';
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('config:cache');
        \Illuminate\Support\Facades\Artisan::call('route:cache');
        \Illuminate\Support\Facades\Artisan::call('view:cache');
    } catch (\Throwable $e) {
        $migrateOutput = 'Nota durante optimización: ' . $e->getMessage();
    }

    return response('
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Optimizar Sistema & Consola Web - ViveGo</title>
            <style>
                * { box-sizing: border-box; }
                body { margin: 0; font-family: system-ui, -apple-system, sans-serif; background: #0A0A10; color: #FFFFFF; min-height: 100vh; padding: 2rem 1rem; }
                .container { max-width: 860px; margin: 0 auto; }
                .card { background: #14141E; border: 1px solid rgba(255,255,255,0.08); border-radius: 18px; padding: 1.75rem; margin-bottom: 1.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
                .card-title { display: flex; align-items: center; gap: 0.6rem; font-size: 1.15rem; font-weight: 800; margin-bottom: 0.4rem; }
                .card-desc { color: #94A3B8; font-size: 0.85rem; margin-bottom: 1.25rem; line-height: 1.45; }
                .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.75rem; font-weight: 700; }
                .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem; cursor: pointer; border: none; font-weight: 700; border-radius: 10px; padding: 0.75rem 1.25rem; font-size: 0.88rem; transition: all 0.15s ease; text-decoration: none; }
                .btn:hover { transform: translateY(-1px); }
                .btn-primary { background: linear-gradient(135deg, #FF5500, #E04B00); color: #FFF; box-shadow: 0 4px 15px rgba(255,85,0,0.35); }
                .btn-success { background: linear-gradient(135deg, #10B981, #059669); color: #FFF; box-shadow: 0 4px 15px rgba(16,185,129,0.35); }
                .btn-secondary { background: #1E1E2E; border: 1px solid rgba(255,255,255,0.15); color: #FFF; }
                .btn-chip { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #CBD5E1; padding: 0.35rem 0.75rem; font-size: 0.78rem; border-radius: 8px; cursor: pointer; font-family: monospace; }
                .btn-chip:hover { background: rgba(255,85,0,0.15); border-color: #FF5500; color: #FF5500; }
                .input-group { margin-bottom: 0.9rem; }
                .input-label { display: block; font-size: 0.78rem; font-weight: 700; color: #CBD5E1; margin-bottom: 0.35rem; text-transform: uppercase; }
                .input-text { width: 100%; background: #0A0A10; border: 1px solid rgba(255,255,255,0.2); border-radius: 8px; padding: 0.65rem 0.85rem; color: #FFF; font-size: 0.88rem; font-family: monospace; outline: none; }
                .input-text:focus { border-color: #FF5500; box-shadow: 0 0 0 2px rgba(255,85,0,0.2); }
                .terminal-window { background: #050508; border: 1px solid rgba(255,255,255,0.12); border-radius: 12px; overflow: hidden; margin-top: 1rem; }
                .terminal-header { background: #101018; padding: 0.6rem 0.9rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.06); }
                .terminal-dots { display: flex; gap: 6px; }
                .terminal-dot { width: 10px; height: 10px; border-radius: 50%; }
                .dot-red { background: #EF4444; } .dot-yellow { background: #F59E0B; } .dot-green { background: #10B981; }
                .terminal-title { font-size: 0.75rem; font-family: monospace; color: #94A3B8; }
                .terminal-body { padding: 1rem; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 0.82rem; color: #10B981; min-height: 140px; max-height: 320px; overflow-y: auto; white-space: pre-wrap; line-height: 1.5; }
                .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem; }
                .result-box { margin-top: 1rem; padding: 1rem; border-radius: 10px; font-size: 0.85rem; display: none; }
                .result-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: #A7F3D0; }
                .result-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #FCA5A5; }
            </style>
        </head>
        <body>
            <div class="container">
                <!-- CABECERA: SISTEMA OPTIMIZADO -->
                <div class="card" style="border-color: rgba(255,85,0,0.35); text-align: center; padding: 2rem;">
                    <div style="font-size: 2.8rem; margin-bottom: 0.3rem;">⚡</div>
                    <h1 style="color: #FF5500; font-size: 1.6rem; font-weight: 900; margin: 0 0 0.4rem 0;">¡Sistema ViveGo Optimizado & Migrado!</h1>
                    <p style="color: #94A3B8; font-size: 0.9rem; margin-bottom: 1.25rem;">Las migraciones de base de datos y cachés fueron procesadas con éxito.</p>
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 1rem; text-align: left; font-size: 0.85rem; color: #10B981; line-height: 1.7; margin-bottom: 1rem;">
                        <div>✔ Migraciones de Base de Datos ejecutadas (migrate --force)</div>
                        <div>✔ Caché de Configuración, Rutas y Plantillas compiladas</div>
                        <div>✔ Soporte oficial Culqi para estados <strong>paid</strong> y <strong>deposited</strong> activo</div>
                    </div>
                    ' . (!empty(trim($migrateOutput)) ? '<div style="background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 0.65rem 0.9rem; text-align: left; font-family: monospace; font-size: 0.78rem; color: #CBD5E1; max-height: 100px; overflow-y: auto; white-space: pre-wrap;">' . htmlspecialchars($migrateOutput) . '</div>' : '') . '
                </div>

                <!-- SECCIÓN 1: PROCESADOR DE ÓRDENES CULQI -->
                <div class="card" style="border-color: rgba(16,185,129,0.35);">
                    <div class="card-title" style="color: #10B981;">
                        <span>💳</span> Procesador de Órdenes Culqi (Sin Terminal)
                    </div>
                    <div class="card-desc">
                        Procesa cualquier orden pagada o depositada en Culqi de forma automática en segundo plano: <strong>crea la venta, emite los boletos oficiales con código QR y envía el correo con el PDF al comprador</strong>.
                    </div>

                    <!-- BOTONES RÁPIDOS PARA LAS ÓRDENES DETECTADAS -->
                    <div style="margin-bottom: 1.25rem;">
                        <span style="display: block; font-size: 0.76rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 0.4rem;">
                            Órdenes Pendientes Detectadas (Haz clic para cargar):
                        </span>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <button type="button" class="btn-chip" onclick="loadOrder(\'ord_live_idwgvP5dHrllqEYf\', \'ediliarojas259@gmail.com\', \'Edilia Rojas Cahuaya\', \'73673527\')">
                                ⚡ ord_live_idwgvP5dHrllqEYf (Huancayo - S/ 30.00)
                            </button>
                            <button type="button" class="btn-chip" onclick="loadOrder(\'ord_live_k2VblmgR1rQh7xHC\', \'\', \'\', \'48347747\')">
                                ⚡ ord_live_k2VblmgR1rQh7xHC (Satipo - S/ 60.00)
                            </button>
                            <button type="button" class="btn-chip" onclick="loadOrder(\'ord_live_UNjSBSgn5DsCB9yV\', \'\', \'\', \'46699998\')">
                                ⚡ ord_live_UNjSBSgn5DsCB9yV (Satipo - S/ 60.00)
                            </button>
                        </div>
                    </div>

                    <form id="culqiOrderForm" onsubmit="submitCulqiOrder(event)">
                        <div class="input-group">
                            <label class="input-label">Order ID de Culqi <span style="color:#EF4444;">*</span></label>
                            <input type="text" id="order_id" name="order_id" class="input-text" placeholder="ord_live_..." required>
                        </div>

                        <div class="grid-3" style="margin-bottom: 1rem;">
                            <div>
                                <label class="input-label">Email Comprador (Opcional)</label>
                                <input type="email" id="customer_email" name="customer_email" class="input-text" placeholder="Auto por DNI si está vacío">
                            </div>
                            <div>
                                <label class="input-label">Nombre Comprador (Opcional)</label>
                                <input type="text" id="customer_name" name="customer_name" class="input-text" placeholder="Auto por DNI si está vacío">
                            </div>
                            <div>
                                <label class="input-label">DNI Comprador (Opcional)</label>
                                <input type="text" id="customer_doc" name="customer_doc" class="input-text" placeholder="Auto si viene en metadata">
                            </div>
                        </div>

                        <button type="submit" id="btnSubmitCulqi" class="btn btn-success" style="width: 100%;">
                            ⚡ Procesar Orden Culqi y Emitir Boletos
                        </button>
                    </form>

                    <div id="culqiResultBox" class="result-box"></div>
                </div>

                <!-- SECCIÓN 2: CONSOLA TERMINAL WEB (ARTISAN RUNNER) -->
                <div class="card" style="border-color: rgba(59,130,246,0.35);">
                    <div class="card-title" style="color: #60A5FA;">
                        <span>💻</span> Consola Terminal Web (Artisan Runner)
                    </div>
                    <div class="card-desc">
                        Ejecuta comandos de Laravel Artisan directamente en el servidor sin requerir acceso SSH ni terminal en cPanel.
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <span style="display: block; font-size: 0.76rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 0.4rem;">
                            Comandos Frecuentes:
                        </span>
                        <div style="display: flex; gap: 0.45rem; flex-wrap: wrap;">
                            <button type="button" class="btn-chip" onclick="setCommand(\'optimize:clear\')">optimize:clear</button>
                            <button type="button" class="btn-chip" onclick="setCommand(\'migrate --force\')">migrate --force</button>
                            <button type="button" class="btn-chip" onclick="setCommand(\'route:clear\')">route:clear</button>
                            <button type="button" class="btn-chip" onclick="setCommand(\'cache:clear\')">cache:clear</button>
                            <button type="button" class="btn-chip" onclick="setCommand(\'view:clear\')">view:clear</button>
                            <button type="button" class="btn-chip" onclick="setCommand(\'config:clear\')">config:clear</button>
                            <button type="button" class="btn-chip" onclick="setCommand(\'inspire\')">inspire</button>
                        </div>
                    </div>

                    <form id="artisanForm" onsubmit="submitArtisanCommand(event)" style="display: flex; gap: 0.5rem;">
                        <div style="position: relative; flex: 1;">
                            <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); font-family: monospace; color: #64748B; font-size: 0.85rem;">$ php artisan</span>
                            <input type="text" id="artisan_command" class="input-text" style="padding-left: 7.5rem;" placeholder="ej: optimize:clear" required>
                        </div>
                        <button type="submit" id="btnSubmitArtisan" class="btn btn-primary" style="white-space: nowrap;">
                            ▶ Ejecutar
                        </button>
                    </form>

                    <div class="terminal-window">
                        <div class="terminal-header">
                            <div class="terminal-dots">
                                <div class="terminal-dot dot-red"></div>
                                <div class="terminal-dot dot-yellow"></div>
                                <div class="terminal-dot dot-green"></div>
                            </div>
                            <div class="terminal-title">vivego-server ~ bash</div>
                            <button type="button" onclick="clearTerminal()" style="background: none; border: none; color: #64748B; font-size: 0.72rem; cursor: pointer;">Limpiar</button>
                        </div>
                        <div id="terminalBody" class="terminal-body">[Consola lista] Escribe un comando o selecciona un botón arriba para ejecutarlo.</div>
                    </div>
                </div>

                <!-- BOTONES DE NAVEGACIÓN -->
                <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                    <a href="' . route('web.home') . '" class="btn btn-primary">
                        Ir a la Página Principal
                    </a>
                    <a href="' . route('web.payment_methods') . '" class="btn btn-secondary">
                        Configurar Pasarelas
                    </a>
                </div>
            </div>

            <script>
                function loadOrder(id, email, name, doc) {
                    document.getElementById("order_id").value = id;
                    document.getElementById("customer_email").value = email || "";
                    document.getElementById("customer_name").value = name || "";
                    document.getElementById("customer_doc").value = doc || "";
                    document.getElementById("order_id").scrollIntoView({ behavior: "smooth", block: "center" });
                }

                function setCommand(cmd) {
                    document.getElementById("artisan_command").value = cmd;
                    document.getElementById("artisan_command").focus();
                }

                function clearTerminal() {
                    document.getElementById("terminalBody").innerText = "[Consola limpia]";
                }

                async function submitCulqiOrder(e) {
                    e.preventDefault();
                    const btn = document.getElementById("btnSubmitCulqi");
                    const resBox = document.getElementById("culqiResultBox");
                    const orderId = document.getElementById("order_id").value.trim();
                    const email = document.getElementById("customer_email").value.trim();
                    const name = document.getElementById("customer_name").value.trim();
                    const doc = document.getElementById("customer_doc").value.trim();

                    btn.disabled = true;
                    btn.innerText = "⏳ Procesando orden con Culqi...";
                    resBox.style.display = "none";

                    try {
                        const response = await fetch("/optimizar-sistema", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                action: "process_culqi",
                                order_id: orderId,
                                customer_email: email,
                                customer_name: name,
                                customer_doc: doc
                            })
                        });

                        const data = await response.json();
                        resBox.style.display = "block";

                        if (data.success) {
                            resBox.className = "result-box result-success";
                            resBox.innerHTML = `
                                <div style="font-weight: 800; font-size: 1rem; margin-bottom: 0.4rem;">🎉 \${data.message}</div>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 0.5rem; margin: 0.6rem 0;">
                                    <div><strong>Recibo:</strong> \${data.receipt_number}</div>
                                    <div><strong>Venta ID:</strong> #\${data.sale_id}</div>
                                    <div><strong>Comprador:</strong> \${data.buyer_name}</div>
                                    <div><strong>DNI:</strong> \${data.buyer_dni}</div>
                                    <div><strong>Correo:</strong> \${data.buyer_email}</div>
                                    <div><strong>Entradas:</strong> \${data.quantity} (\${data.zone_name})</div>
                                    <div><strong>Total:</strong> \${data.total_amount}</div>
                                </div>
                                <div style="margin-top: 0.6rem;">
                                    <a href="\${data.confirmation_url}" target="_blank" style="color: #10B981; font-weight: 800; text-decoration: underline;">
                                        🔗 Ver Pantalla de Confirmación y Boletos Emitidos →
                                    </a>
                                </div>
                            `;
                        } else {
                            resBox.className = "result-box result-error";
                            resBox.innerHTML = `<strong>❌ Error:</strong> \${data.message}`;
                        }
                    } catch (err) {
                        resBox.style.display = "block";
                        resBox.className = "result-box result-error";
                        resBox.innerHTML = `<strong>❌ Error de conexión:</strong> \${err.message}`;
                    } finally {
                        btn.disabled = false;
                        btn.innerText = "⚡ Procesar Orden Culqi y Emitir Boletos";
                    }
                }

                async function submitArtisanCommand(e) {
                    e.preventDefault();
                    const btn = document.getElementById("btnSubmitArtisan");
                    const cmdInput = document.getElementById("artisan_command");
                    const term = document.getElementById("terminalBody");
                    const cmd = cmdInput.value.trim();

                    btn.disabled = true;
                    btn.innerText = "⏳ Ejecutando...";
                    term.innerText += `\\n\\n$ php artisan \${cmd}\\n[Ejecutando...]`;
                    term.scrollTop = term.scrollHeight;

                    try {
                        const response = await fetch("/optimizar-sistema", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                action: "run_artisan",
                                command: cmd
                            })
                        });

                        const data = await response.json();
                        term.innerText += `\\n\${data.output}`;
                    } catch (err) {
                        term.innerText += `\\n❌ Error: \${err.message}`;
                    } finally {
                        btn.disabled = false;
                        btn.innerText = "▶ Ejecutar";
                        term.scrollTop = term.scrollHeight;
                    }
                }
            </script>
        </body>
        </html>
    ', 200)->header('Content-Type', 'text/html');
});

Route::match(['get', 'post'], '/regenerar-qr-ventas-pos', function (\Illuminate\Http\Request $request) {
    try {
        $eventId = $request->input('event_id');
        $startCorrelative = $request->filled('start_correlative') ? max(1, (int)$request->input('start_correlative')) : 0;
        $scope = $request->input('scope', 'pos_only') === 'all_sales' ? 'all_sales' : 'pos_only';

        if (!$eventId) {
            return response('
                <div style="font-family: system-ui, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFF;">
                    <div style="background: #14141E; border: 1px solid rgba(239,68,68,0.4); padding: 2rem; border-radius: 16px; max-width: 500px; text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⚠️</div>
                        <h3 style="color: #EF4444; margin-top: 0;">Evento no seleccionado</h3>
                        <p style="color: #94A3B8; font-size: 0.9rem;">Debes seleccionar un evento para regenerar sus códigos QR y correlativos.</p>
                        <a href="/optimizar-sistema" style="display: inline-block; background: #2563EB; color: #FFF; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700;">← Volver a Optimizar Sistema</a>
                    </div>
                </div>
            ', 400)->header('Content-Type', 'text/html');
        }

        $event = \App\Models\Event::find($eventId);
        if (!$event) {
            return response('
                <div style="font-family: system-ui, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFF;">
                    <div style="background: #14141E; border: 1px solid rgba(239,68,68,0.4); padding: 2rem; border-radius: 16px; max-width: 500px; text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">❌</div>
                        <h3 style="color: #EF4444; margin-top: 0;">Evento no encontrado</h3>
                        <p style="color: #94A3B8; font-size: 0.9rem;">El evento con ID #' . htmlspecialchars($eventId) . ' no fue encontrado en la base de datos.</p>
                        <a href="/optimizar-sistema" style="display: inline-block; background: #2563EB; color: #FFF; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700;">← Volver a Optimizar Sistema</a>
                    </div>
                </div>
            ', 404)->header('Content-Type', 'text/html');
        }

        $res = \App\Services\TicketGenerationService::regeneratePosSalesQrs($event, $startCorrelative, $scope);

        if (!$res['success']) {
            return response('
                <div style="font-family: system-ui, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFF;">
                    <div style="background: #14141E; border: 1px solid rgba(245,158,11,0.4); padding: 2rem; border-radius: 16px; max-width: 500px; text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">ℹ️</div>
                        <h3 style="color: #F59E0B; margin-top: 0;">Sin ventas para procesar</h3>
                        <p style="color: #94A3B8; font-size: 0.9rem;">' . htmlspecialchars($res['message']) . '</p>
                        <a href="/optimizar-sistema" style="display: inline-block; background: #2563EB; color: #FFF; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700;">← Volver a Optimizar Sistema</a>
                    </div>
                </div>
            ', 200)->header('Content-Type', 'text/html');
        }

        $rowsHtml = '';
        foreach (array_slice($res['details'], 0, 100) as $d) {
            $rowsHtml .= '<tr style="border-bottom: 1px solid rgba(255,255,255,0.06); font-size: 0.78rem;">
                <td style="padding: 0.5rem 0.65rem; color: #94A3B8;">#' . $d['sale_id'] . '<br><span style="font-size:0.7rem; color:#64748B;">' . htmlspecialchars($d['receipt_number'] ?? '') . '</span></td>
                <td style="padding: 0.5rem 0.65rem; color: #CBD5E1; font-weight: 600;">' . htmlspecialchars($d['buyer_name']) . '<br><span style="font-size:0.7rem; color:#94A3B8;">' . htmlspecialchars($d['zone_name']) . '</span></td>
                <td style="padding: 0.5rem 0.65rem; font-family: monospace; color: #EF4444; text-decoration: line-through;">' . htmlspecialchars($d['old_ticket_code']) . '<br><span style="font-size:0.7rem; color:#64748B;">' . htmlspecialchars($d['old_hash']) . '</span></td>
                <td style="padding: 0.5rem 0.65rem; font-family: monospace; color: #10B981; font-weight: 800;">' . htmlspecialchars($d['new_ticket_code']) . '<br><span style="font-size:0.7rem; color:#34D399;">' . htmlspecialchars($d['new_hash']) . '</span></td>
                <td style="padding: 0.5rem 0.65rem; font-family: monospace; font-size: 0.7rem; color: #60A5FA; word-break: break-all; max-width: 220px;">' . htmlspecialchars($d['new_qr']) . '</td>
                <td style="padding: 0.5rem 0.65rem; text-align: center;"><span style="background: rgba(16,185,129,0.15); color: #10B981; border: 1px solid rgba(16,185,129,0.4); padding: 0.15rem 0.45rem; border-radius: 6px; font-weight: 800; font-size: 0.68rem;">✔ OK</span></td>
            </tr>';
        }

        return response('
            <div style="font-family: system-ui, -apple-system, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFFFFF;">
                <div style="background: #14141E; border: 1px solid rgba(16,185,129,0.35); padding: 2.2rem; border-radius: 20px; max-width: 860px; width: 100%; box-shadow: 0 20px 50px rgba(0,0,0,0.6); text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🎉</div>
                    <h2 style="color: #10B981; font-size: 1.55rem; font-weight: 900; margin: 0 0 0.4rem 0;">¡Códigos QR y Correlativos POS Regenerados!</h2>
                    <p style="color: #94A3B8; font-size: 0.9rem; margin-bottom: 1.3rem;">
                        Evento: <strong style="color: #FFFFFF;">' . htmlspecialchars($event->title) . ' (ID #' . $event->id . ')</strong>
                    </p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem; text-align: center;">
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem;">
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Ventas Actualizadas</span>
                            <strong style="font-size: 1.35rem; color: #60A5FA;">' . $res['sales_count'] . '</strong>
                        </div>
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem;">
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Boletos Regenerados</span>
                            <strong style="font-size: 1.35rem; color: #10B981;">' . $res['tickets_count'] . '</strong>
                        </div>
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem;">
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Rango Correlativos</span>
                            <strong style="font-size: 1.1rem; color: #F59E0B; font-family: monospace;">' . $res['correlative_range'] . '</strong>
                        </div>
                    </div>

                    <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem; max-height: 280px; overflow-y: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="background: rgba(255,255,255,0.04); font-size: 0.72rem; color: #94A3B8; text-transform: uppercase;">
                                    <th style="padding: 0.5rem 0.65rem;">Venta / Recibo</th>
                                    <th style="padding: 0.5rem 0.65rem;">Comprador</th>
                                    <th style="padding: 0.5rem 0.65rem;">Anterior</th>
                                    <th style="padding: 0.5rem 0.65rem;">NUEVO Correlativo</th>
                                    <th style="padding: 0.5rem 0.65rem;">Nuevo Payload QR</th>
                                    <th style="padding: 0.5rem 0.65rem; text-align: center;">Estado</th>
                                </tr>
                            </thead>
                            <tbody>' . $rowsHtml . '</tbody>
                        </table>
                    </div>

                    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                        <a href="/optimizar-sistema" style="display: inline-block; background: #1E1E2E; border: 1px solid rgba(255,255,255,0.15); color: #FFFFFF; font-weight: 700; text-decoration: none; padding: 0.85rem 1.4rem; border-radius: 12px;">
                            ← Volver a Optimizar Sistema
                        </a>
                        <a href="' . route('web.box_office.manage', $event->id) . '" style="display: inline-block; background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 0.85rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(16,185,129,0.4);">
                            🎟️ Ir a Taquilla del Evento
                        </a>
                        <a href="' . route('web.home') . '" style="display: inline-block; background: linear-gradient(135deg, #FF5500, #E04B00); color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 0.85rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(255,85,0,0.4);">
                            Ir al Inicio
                        </a>
                    </div>
                </div>
            </div>
        ', 200)->header('Content-Type', 'text/html');
    } catch (\Exception $e) {
        return response('<div style="font-family: sans-serif; padding: 2rem; background: #14141E; color: #EF4444;"><h3 style="color:#EF4444;">Error al regenerar códigos QR y correlativos:</h3><pre style="background: #000; padding: 1rem; border-radius: 8px; color: #FCA5A5;">' . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString()) . '</pre></div>', 500);
    }
});

Route::match(['get', 'post'], '/restablecer-boletos-fisicos', function (\Illuminate\Http\Request $request) {
    try {
        $eventId = $request->input('event_id');
        $actionMode = $request->input('action_mode', 'both');
        if (!in_array($actionMode, ['both', 'qr_only', 'correlative_only'])) {
            $actionMode = 'both';
        }
        $startCorrelative = $request->filled('start_correlative') ? max(1, (int)$request->input('start_correlative')) : 1;
        $resetSales = ($request->input('reset_sales', 'yes') === 'yes');
        $ticketScope = $request->input('ticket_scope', 'all_physical');

        if (!$eventId) {
            return response('
                <div style="font-family: system-ui, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFF;">
                    <div style="background: #14141E; border: 1px solid rgba(239,68,68,0.4); padding: 2rem; border-radius: 16px; max-width: 500px; text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⚠️</div>
                        <h3 style="color: #EF4444; margin-top: 0;">Evento no seleccionado</h3>
                        <p style="color: #94A3B8; font-size: 0.9rem;">Debes seleccionar un evento para restablecer sus boletos físicos.</p>
                        <a href="/optimizar-sistema" style="display: inline-block; background: #2563EB; color: #FFF; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700;">← Volver a Optimizar Sistema</a>
                    </div>
                </div>
            ', 400)->header('Content-Type', 'text/html');
        }

        $event = \App\Models\Event::find($eventId);
        if (!$event) {
            return response('
                <div style="font-family: system-ui, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFF;">
                    <div style="background: #14141E; border: 1px solid rgba(239,68,68,0.4); padding: 2rem; border-radius: 16px; max-width: 500px; text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">❌</div>
                        <h3 style="color: #EF4444; margin-top: 0;">Evento no encontrado</h3>
                        <p style="color: #94A3B8; font-size: 0.9rem;">El evento con ID #' . htmlspecialchars($eventId) . ' no fue encontrado en la base de datos.</p>
                        <a href="/optimizar-sistema" style="display: inline-block; background: #2563EB; color: #FFF; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700;">← Volver a Optimizar Sistema</a>
                    </div>
                </div>
            ', 404)->header('Content-Type', 'text/html');
        }

        $res = \App\Services\TicketGenerationService::resetPhysicalTicketsAndSales(
            $event,
            $actionMode,
            $startCorrelative,
            $resetSales,
            $ticketScope
        );

        if (!$res['success']) {
            return response('
                <div style="font-family: system-ui, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFF;">
                    <div style="background: #14141E; border: 1px solid rgba(245,158,11,0.4); padding: 2rem; border-radius: 16px; max-width: 500px; text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">ℹ️</div>
                        <h3 style="color: #F59E0B; margin-top: 0;">Sin boletos físicos para procesar</h3>
                        <p style="color: #94A3B8; font-size: 0.9rem;">' . htmlspecialchars($res['message']) . '</p>
                        <a href="/optimizar-sistema" style="display: inline-block; background: #2563EB; color: #FFF; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700;">← Volver a Optimizar Sistema</a>
                    </div>
                </div>
            ', 200)->header('Content-Type', 'text/html');
        }

        $modeLabels = [
            'both' => 'Regenerar QR y Correlativo',
            'qr_only' => 'Solo Regenerar QR y Hash',
            'correlative_only' => 'Solo Renumerar Correlativo',
        ];
        $modeText = $modeLabels[$actionMode] ?? 'Personalizado';

        $rowsHtml = '';
        foreach (array_slice($res['details'], 0, 100) as $d) {
            $rowsHtml .= '<tr style="border-bottom: 1px solid rgba(255,255,255,0.06); font-size: 0.78rem;">
                <td style="padding: 0.5rem 0.65rem; color: #94A3B8;">#' . $d['ticket_id'] . '<br><span style="font-size:0.7rem; color:#60A5FA;">' . htmlspecialchars($d['zone_name']) . '</span></td>
                <td style="padding: 0.5rem 0.65rem; font-family: monospace; color: #EF4444;">' . htmlspecialchars($d['old_ticket_code']) . '</td>
                <td style="padding: 0.5rem 0.65rem; font-family: monospace; color: #10B981; font-weight: 800;">' . htmlspecialchars($d['new_ticket_code']) . '</td>
                <td style="padding: 0.5rem 0.65rem; font-family: monospace; font-size: 0.72rem; color: #CBD5E1;">
                    <span style="color:#64748B; text-decoration:line-through;">' . htmlspecialchars(substr($d['old_hash'] ?? '', 0, 10)) . '</span><br>
                    <span style="color:#34D399; font-weight:700;">' . htmlspecialchars($d['new_hash']) . '</span>
                </td>
                <td style="padding: 0.5rem 0.65rem; font-family: monospace; font-size: 0.7rem; color: #60A5FA; word-break: break-all; max-width: 200px;">' . htmlspecialchars($d['new_qr']) . '</td>
                <td style="padding: 0.5rem 0.65rem; text-align: center;"><span style="background: rgba(16,185,129,0.15); color: #10B981; border: 1px solid rgba(16,185,129,0.4); padding: 0.15rem 0.45rem; border-radius: 6px; font-weight: 800; font-size: 0.68rem;">✔ OK</span></td>
            </tr>';
        }

        return response('
            <div style="font-family: system-ui, -apple-system, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFFFFF;">
                <div style="background: #14141E; border: 1px solid rgba(245,158,11,0.35); padding: 2.2rem; border-radius: 20px; max-width: 860px; width: 100%; box-shadow: 0 20px 50px rgba(0,0,0,0.6); text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🎉</div>
                    <h2 style="color: #F59E0B; font-size: 1.55rem; font-weight: 900; margin: 0 0 0.4rem 0;">¡Boletos Físicos Restablecidos con Éxito!</h2>
                    <p style="color: #94A3B8; font-size: 0.9rem; margin-bottom: 1.3rem;">
                        Evento: <strong style="color: #FFFFFF;">' . htmlspecialchars($event->title) . ' (ID #' . $event->id . ')</strong>
                    </p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem; text-align: center;">
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem;">
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Boletos Físicos</span>
                            <strong style="font-size: 1.35rem; color: #10B981;">' . $res['tickets_count'] . '</strong>
                        </div>
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem;">
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Ventas Liberadas</span>
                            <strong style="font-size: 1.35rem; color: #F59E0B;">' . $res['sales_reset_count'] . '</strong>
                        </div>
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem;">
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Modo Aplicado</span>
                            <strong style="font-size: 0.85rem; color: #60A5FA;">' . htmlspecialchars($modeText) . '</strong>
                        </div>
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem;">
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Rango Correlativos</span>
                            <strong style="font-size: 1rem; color: #34D399; font-family: monospace;">' . $res['correlative_range'] . '</strong>
                        </div>
                    </div>

                    <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem; max-height: 280px; overflow-y: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="background: rgba(255,255,255,0.04); font-size: 0.72rem; color: #94A3B8; text-transform: uppercase;">
                                    <th style="padding: 0.5rem 0.65rem;">ID / Zona</th>
                                    <th style="padding: 0.5rem 0.65rem;">Anterior</th>
                                    <th style="padding: 0.5rem 0.65rem;">Nuevo Correlativo</th>
                                    <th style="padding: 0.5rem 0.65rem;">Hash (Viejo → Nuevo)</th>
                                    <th style="padding: 0.5rem 0.65rem;">Nuevo Payload QR</th>
                                    <th style="padding: 0.5rem 0.65rem; text-align: center;">Estado</th>
                                </tr>
                            </thead>
                            <tbody>' . $rowsHtml . '</tbody>
                        </table>
                    </div>

                    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                        <a href="/optimizar-sistema" style="display: inline-block; background: #1E1E2E; border: 1px solid rgba(255,255,255,0.15); color: #FFFFFF; font-weight: 700; text-decoration: none; padding: 0.85rem 1.4rem; border-radius: 12px;">
                            ← Volver a Optimizar Sistema
                        </a>
                        <a href="' . route('web.box_office.manage', $event->id) . '" style="display: inline-block; background: linear-gradient(135deg, #F59E0B, #D97706); color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 0.85rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(245,158,11,0.4);">
                            🎟️ Ir a Taquilla del Evento
                        </a>
                        <a href="' . route('web.home') . '" style="display: inline-block; background: linear-gradient(135deg, #FF5500, #E04B00); color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 0.85rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(255,85,0,0.4);">
                            Ir al Inicio
                        </a>
                    </div>
                </div>
            </div>
        ', 200)->header('Content-Type', 'text/html');
    } catch (\Exception $e) {
        return response('<div style="font-family: sans-serif; padding: 2rem; background: #14141E; color: #EF4444;"><h3 style="color:#EF4444;">Error al restablecer boletos físicos:</h3><pre style="background: #000; padding: 1rem; border-radius: 8px; color: #FCA5A5;">' . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString()) . '</pre></div>', 500);
    }
});

Route::match(['get', 'post'], '/renumerar-todas-categorias', function (\Illuminate\Http\Request $request) {
    try {
        $eventId = $request->input('event_id');
        if (!$eventId) {
            return response('
                <div style="font-family: system-ui, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFF;">
                    <div style="background: #14141E; border: 1px solid rgba(239,68,68,0.4); padding: 2rem; border-radius: 16px; max-width: 500px; text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⚠️</div>
                        <h3 style="color: #EF4444; margin-top: 0;">Evento no seleccionado</h3>
                        <p style="color: #94A3B8; font-size: 0.9rem;">Debes seleccionar un evento para renumerar sus categorías.</p>
                        <a href="/optimizar-sistema" style="display: inline-block; background: #2563EB; color: #FFF; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700;">← Volver a Optimizar Sistema</a>
                    </div>
                </div>
            ', 400)->header('Content-Type', 'text/html');
        }

        $event = \App\Models\Event::find($eventId);
        if (!$event) {
            return response('
                <div style="font-family: system-ui, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFF;">
                    <div style="background: #14141E; border: 1px solid rgba(239,68,68,0.4); padding: 2rem; border-radius: 16px; max-width: 500px; text-align: center;">
                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">❌</div>
                        <h3 style="color: #EF4444; margin-top: 0;">Evento no encontrado</h3>
                        <p style="color: #94A3B8; font-size: 0.9rem;">El evento con ID #' . htmlspecialchars($eventId) . ' no fue encontrado en la base de datos.</p>
                        <a href="/optimizar-sistema" style="display: inline-block; background: #2563EB; color: #FFF; text-decoration: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 700;">← Volver a Optimizar Sistema</a>
                    </div>
                </div>
            ', 404)->header('Content-Type', 'text/html');
        }

        $res = \App\Services\TicketGenerationService::renumberEventTicketCategories($event);

        $categoriesHtml = '';
        $colors = [
            'fisica' => '#F59E0B',
            'cortesia_fisica' => '#10B981',
            'digital' => '#3B82F6',
            'cortesia_digital' => '#8B5CF6',
        ];
        foreach ($res['categories'] as $key => $cat) {
            $color = $colors[$key] ?? '#60A5FA';
            $categoriesHtml .= '<div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 1rem; text-align: left;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                    <span style="font-size: 0.85rem; font-weight: 700; color: ' . $color . ';">' . htmlspecialchars($cat['title']) . '</span>
                    <span style="background: ' . $color . '22; color: ' . $color . '; border: 1px solid ' . $color . '55; padding: 0.15rem 0.5rem; border-radius: 6px; font-weight: 800; font-size: 0.72rem;">' . $cat['count'] . ' boletos</span>
                </div>
                <div style="font-family: monospace; font-size: 0.95rem; font-weight: 800; color: #FFFFFF;">
                    ' . htmlspecialchars($cat['range']) . '
                </div>
            </div>';
        }

        return response('
            <div style="font-family: system-ui, -apple-system, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFFFFF;">
                <div style="background: #14141E; border: 1px solid rgba(139,92,246,0.35); padding: 2.2rem; border-radius: 20px; max-width: 720px; width: 100%; box-shadow: 0 20px 50px rgba(0,0,0,0.6); text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🎉</div>
                    <h2 style="color: #A78BFA; font-size: 1.55rem; font-weight: 900; margin: 0 0 0.4rem 0;">¡Todas las Categorías Renumeradas desde N° 1!</h2>
                    <p style="color: #94A3B8; font-size: 0.9rem; margin-bottom: 1.3rem;">
                        Evento: <strong style="color: #FFFFFF;">' . htmlspecialchars($event->title) . ' (ID #' . $event->id . ')</strong>
                    </p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem; text-align: center;">
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem;">
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Total Boletos Renumerados</span>
                            <strong style="font-size: 1.35rem; color: #10B981;">' . $res['total_tickets'] . '</strong>
                        </div>
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem;">
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Ventas Sincronizadas</span>
                            <strong style="font-size: 1.35rem; color: #60A5FA;">' . $res['sales_updated'] . '</strong>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem; margin-bottom: 1.5rem;">
                        ' . $categoriesHtml . '
                    </div>

                    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                        <a href="/optimizar-sistema" style="display: inline-block; background: #1E1E2E; border: 1px solid rgba(255,255,255,0.15); color: #FFFFFF; font-weight: 700; text-decoration: none; padding: 0.85rem 1.4rem; border-radius: 12px;">
                            ← Volver a Optimizar Sistema
                        </a>
                        <a href="' . route('web.box_office.manage', $event->id) . '" style="display: inline-block; background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 0.85rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(16,185,129,0.4);">
                            🎟️ Ir a Taquilla del Evento
                        </a>
                        <a href="' . route('web.home') . '" style="display: inline-block; background: linear-gradient(135deg, #FF5500, #E04B00); color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 0.85rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(255,85,0,0.4);">
                            Ir al Inicio
                        </a>
                    </div>
                </div>
            </div>
        ', 200)->header('Content-Type', 'text/html');
    } catch (\Exception $e) {
        return response('<div style="font-family: sans-serif; padding: 2rem; background: #14141E; color: #EF4444;"><h3 style="color:#EF4444;">Error al renumerar categorías:</h3><pre style="background: #000; padding: 1rem; border-radius: 8px; color: #FCA5A5;">' . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString()) . '</pre></div>', 500);
    }
});

Route::get('/sincronizar-ventas-antiguas', function () {
    try {
        $res = \App\Services\TicketGenerationService::syncSalesToEventTickets();

        $rowsHtml = '';
        foreach (array_slice($res['details'], 0, 50) as $d) {
            $badgeColor = $d['action'] === 'Creado' ? '#10B981' : '#3B82F6';
            $rowsHtml .= '<tr style="border-bottom: 1px solid rgba(255,255,255,0.06); font-size: 0.8rem;">
                <td style="padding: 0.5rem 0.65rem; color: #94A3B8;">#' . $d['sale_id'] . '</td>
                <td style="padding: 0.5rem 0.65rem; color: #F59E0B; font-weight: 800; font-family: monospace;">' . htmlspecialchars($d['ticket_code']) . '</td>
                <td style="padding: 0.5rem 0.65rem; color: #60A5FA; font-family: monospace;">' . htmlspecialchars($d['validation_hash']) . '</td>
                <td style="padding: 0.5rem 0.65rem; color: #FFFFFF;">' . htmlspecialchars($d['buyer_name']) . '</td>
                <td style="padding: 0.5rem 0.65rem; color: #CBD5E1;">ET #' . $d['event_ticket_id'] . '</td>
                <td style="padding: 0.5rem 0.65rem;"><span style="background: ' . $badgeColor . '22; color: ' . $badgeColor . '; border: 1px solid ' . $badgeColor . '55; padding: 0.15rem 0.45rem; border-radius: 6px; font-weight: 800; font-size: 0.7rem;">' . $d['action'] . '</span></td>
            </tr>';
        }

        return response('
            <div style="font-family: system-ui, -apple-system, sans-serif; min-height: 100vh; background: #0A0A10; display: flex; align-items: center; justify-content: center; padding: 1.5rem; color: #FFFFFF;">
                <div style="background: #14141E; border: 1px solid rgba(37,99,235,0.35); padding: 2.5rem; border-radius: 20px; max-width: 760px; width: 100%; box-shadow: 0 20px 50px rgba(0,0,0,0.6); text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">🎉</div>
                    <h2 style="color: #60A5FA; font-size: 1.6rem; font-weight: 900; margin: 0 0 0.5rem 0;">¡Ventas Antiguas Sincronizadas con Éxito!</h2>
                    <p style="color: #94A3B8; font-size: 0.92rem; margin-bottom: 1.5rem;">Se han vinculado los códigos QR, hashes y correlativos de las ventas registradas con la tabla oficial de boletos de plancha (<code>event_tickets</code>).</p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem; text-align: center;">
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem;">
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Ventas Procesadas</span>
                            <strong style="font-size: 1.3rem; color: #60A5FA;">' . $res['synced_sales'] . '</strong>
                        </div>
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem;">
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Boletos Actualizados</span>
                            <strong style="font-size: 1.3rem; color: #10B981;">' . $res['updated_tickets'] . '</strong>
                        </div>
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 0.85rem;">
                            <span style="font-size: 0.75rem; color: #94A3B8; display: block;">Boletos Creados</span>
                            <strong style="font-size: 1.3rem; color: #F59E0B;">' . $res['created_tickets'] . '</strong>
                        </div>
                    </div>

                    ' . (!empty($rowsHtml) ? '
                    <div style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem; max-height: 260px; overflow-y: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="background: rgba(255,255,255,0.04); font-size: 0.72rem; color: #94A3B8; text-transform: uppercase;">
                                    <th style="padding: 0.5rem 0.65rem;">Venta</th>
                                    <th style="padding: 0.5rem 0.65rem;">Boleto</th>
                                    <th style="padding: 0.5rem 0.65rem;">Hash</th>
                                    <th style="padding: 0.5rem 0.65rem;">Comprador</th>
                                    <th style="padding: 0.5rem 0.65rem;">Ref ET</th>
                                    <th style="padding: 0.5rem 0.65rem;">Estado</th>
                                </tr>
                            </thead>
                            <tbody>' . $rowsHtml . '</tbody>
                        </table>
                    </div>' : '') . '

                    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
                        <a href="/optimizar-sistema" style="display: inline-block; background: #1E1E2E; border: 1px solid rgba(255,255,255,0.15); color: #FFFFFF; font-weight: 700; text-decoration: none; padding: 0.85rem 1.4rem; border-radius: 12px;">
                            ← Volver a Optimizar Sistema
                        </a>
                        <a href="' . route('web.home') . '" style="display: inline-block; background: linear-gradient(135deg, #FF5500, #E04B00); color: #FFFFFF; font-weight: 800; text-decoration: none; padding: 0.85rem 1.8rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(255,85,0,0.4);">
                            Ir a la Página Principal
                        </a>
                    </div>
                </div>
            </div>
        ', 200)->header('Content-Type', 'text/html');
    } catch (\Exception $e) {
        return response('<div style="font-family: sans-serif; padding: 2rem; background: #14141E; color: #EF4444;"><h3 style="color:#EF4444;">Error al sincronizar ventas antiguas:</h3><pre style="background: #000; padding: 1rem; border-radius: 8px; color: #FCA5A5;">' . htmlspecialchars($e->getMessage()) . '</pre></div>', 500);
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

