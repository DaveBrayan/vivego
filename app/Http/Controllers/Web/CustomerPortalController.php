<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EventTicket;
use App\Models\TicketSale;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class CustomerPortalController extends Controller
{
    /**
     * Redirige al inicio de sesión unificado de Vive Go
     */
    public function showLoginForm(): RedirectResponse
    {
        return redirect()->route('web.login');
    }

    /**
     * Iniciar sesión unificado (Soporta Clientes y Administradores vía API/JSON)
     */
    public function login(Request $request): JsonResponse
    {
        $loginInput = trim($request->input('email') ?? $request->input('login') ?? '');
        $password = trim((string) $request->input('password'));
        $passwordUpper = strtoupper($password);

        if (empty($loginInput) || empty($password)) {
            return response()->json([
                'success' => false,
                'message' => 'Debes ingresar tu correo/DNI y contraseña.',
            ], 422);
        }

        $loginLower = strtolower($loginInput);

        // 1. Validar como Administrador
        $admin = \App\Models\Administrator::where(function ($q) use ($loginInput, $loginLower) {
            $q->where('email', $loginLower)->orWhere('username', $loginInput);
        })->first();

        $adminPassMatches = $admin && (Hash::check($password, $admin->password) || (str_starts_with($passwordUpper, 'VG') && Hash::check($passwordUpper, $admin->password)));

        if ($adminPassMatches) {
            if ($admin->status !== 'Activo') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tu cuenta de administrador se encuentra inactiva o suspendida.',
                ], 422);
            }

            if ($request->hasSession()) {
                $request->session()->regenerate();
            }
            session()->forget([
                'customer_logged_in',
                'customer_id',
                'customer_name',
                'customer_email',
                'customer_dni',
                'customer_phone',
            ]);

            session([
                'admin_logged_in' => true,
                'admin_id' => $admin->id,
                'admin_name' => $admin->full_name,
                'admin_email' => $admin->email,
                'admin_role' => $admin->role,
                'admin_avatar' => $admin->avatar,
                'must_change_password' => str_starts_with($passwordUpper, 'VG'),
            ]);

            return response()->json([
                'success' => true,
                'role' => 'admin',
                'must_change_password' => str_starts_with($passwordUpper, 'VG'),
                'message' => "¡Bienvenido de nuevo, {$admin->full_name}!",
                'redirect_url' => route('web.dashboard'),
            ]);
        }

        // 2. Validar como Cliente / Usuario
        $user = User::where(function ($q) use ($loginInput, $loginLower) {
            $q->where('email', $loginLower)->orWhere('dni', $loginInput);
        })->first();

        $userPassMatches = $user && (Hash::check($password, $user->password) || (str_starts_with($passwordUpper, 'VG') && Hash::check($passwordUpper, $user->password)));

        if ($userPassMatches) {
            $statusLower = strtolower(trim((string) $user->status));
            $isInactive = in_array($statusLower, ['inactivo', 'inactive', 'bloqueado', 'blocked', 'suspendido', 'suspended', '0']);
            if ($isInactive) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tu cuenta de cliente se encuentra inactiva o bloqueada.',
                ], 422);
            }

            if ($request->hasSession()) {
                $request->session()->regenerate();
            }
            session()->forget([
                'admin_logged_in',
                'admin_id',
                'admin_name',
                'admin_email',
                'admin_role',
                'admin_avatar',
                'must_change_password',
            ]);

            $isTempPassword = str_starts_with($password, 'VG');

            session([
                'customer_logged_in' => true,
                'customer_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_dni' => $user->dni,
                'customer_phone' => $user->phone,
                'must_change_password' => $isTempPassword,
            ]);

            return response()->json([
                'success' => true,
                'role' => 'customer',
                'must_change_password' => $isTempPassword,
                'message' => $isTempPassword ? 'Has ingresado con una contraseña temporal. Por favor establece una nueva contraseña.' : ('¡Bienvenido, ' . $user->name . '!'),
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'dni' => $user->dni,
                    'phone' => $user->phone,
                ],
                'redirect_url' => route('web.customer.tickets'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Correo, DNI o contraseña incorrectos.',
        ], 422);
    }

    /**
     * Cerrar sesión de Cliente
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget([
            'customer_logged_in',
            'customer_id',
            'customer_name',
            'customer_email',
            'customer_dni',
            'customer_phone',
        ]);

        return redirect()->route('web.home')
            ->with('success', 'Has cerrado sesión del Portal de Clientes correctamente.');
    }

    /**
     * Portal del Cliente: Mis Boletos Emitidos con Código QR
     */
    public function myTickets(): View|RedirectResponse
    {
        $customerId = session('customer_id');
        $customerEmail = session('customer_email');
        $customerDni = session('customer_dni');

        if ($customerId && (empty($customerEmail) || empty($customerDni))) {
            $custUser = User::find($customerId);
            if ($custUser) {
                $customerEmail = $customerEmail ?: $custUser->email;
                $customerDni = $customerDni ?: $custUser->dni;
            }
        }

        if (!session('customer_logged_in') && empty($customerEmail) && empty($customerDni)) {
            return redirect()->route('web.login')->with('info', 'Inicia sesión para ver tus boletos.');
        }

        $sales = TicketSale::with(['event.template'])
            ->where(function ($query) use ($customerEmail, $customerDni) {
                if ($customerEmail) {
                    $query->orWhereJsonContains('tickets_data->customer_email', $customerEmail)
                          ->orWhere('tickets_data', 'LIKE', "%{$customerEmail}%")
                          ->orWhere('buyer_name', $customerEmail);
                }
                if ($customerDni) {
                    $query->orWhere('buyer_dni', $customerDni);
                }
            })
            ->latest()
            ->get();

        return view('web.customer.my_tickets', compact('sales'));
    }

    /**
     * Portal del Cliente: Mis Recibos y Comprobantes de Pago
     */
    public function myReceipts(): View|RedirectResponse
    {
        $customerId = session('customer_id');
        $customerEmail = session('customer_email');
        $customerDni = session('customer_dni');

        if ($customerId && (empty($customerEmail) || empty($customerDni))) {
            $custUser = User::find($customerId);
            if ($custUser) {
                $customerEmail = $customerEmail ?: $custUser->email;
                $customerDni = $customerDni ?: $custUser->dni;
            }
        }

        if (!session('customer_logged_in') && empty($customerEmail) && empty($customerDni)) {
            return redirect()->route('web.login')->with('info', 'Inicia sesión para ver tus recibos.');
        }

        $sales = TicketSale::with(['event.template'])
            ->where(function ($query) use ($customerEmail, $customerDni) {
                if ($customerEmail) {
                    $query->orWhereJsonContains('tickets_data->customer_email', $customerEmail)
                          ->orWhere('tickets_data', 'LIKE', "%{$customerEmail}%")
                          ->orWhere('buyer_name', $customerEmail);
                }
                if ($customerDni) {
                    $query->orWhere('buyer_dni', $customerDni);
                }
            })
            ->latest()
            ->get();

        return view('web.customer.my_receipts', compact('sales'));
    }

    /**
     * Obtiene las opciones de zonas y cálculo de diferencias para mejorar una entrada
     */
    public function getUpgradeOptions(TicketSale $sale): JsonResponse
    {
        $customerEmail = session('customer_email');
        $customerDni = session('customer_dni');
        $customerId = session('customer_id');
        $isAdmin = session('admin_logged_in');

        $isAuthorized = false;
        if ($isAdmin) {
            $isAuthorized = true;
        } elseif ($customerId) {
            $custUser = User::find($customerId);
            if ($custUser) {
                $customerEmail = $customerEmail ?: $custUser->email;
                $customerDni = $customerDni ?: $custUser->dni;
            }
        }

        $ticketsDataStr = is_array($sale->tickets_data) ? json_encode($sale->tickets_data) : (string) $sale->tickets_data;

        if ($isAdmin) {
            $isAuthorized = true;
        } elseif ($customerDni && !empty($sale->buyer_dni) && $sale->buyer_dni === $customerDni) {
            $isAuthorized = true;
        } elseif ($customerEmail && (str_contains($ticketsDataStr, $customerEmail) || str_contains((string) ($sale->buyer_email ?? ''), $customerEmail))) {
            $isAuthorized = true;
        }

        if (!$isAuthorized) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes autorización para consultar las opciones de esta entrada.',
            ], 403);
        }

        if ($sale->isUpgraded()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta entrada ya fue mejorada a una zona superior previamente.',
            ], 422);
        }

        $pm = strtolower($sale->payment_method ?? '');
        $tData = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data, true);
        $isCourtesy = ($pm === 'cortesía' || $pm === 'cortesia' || !empty($tData['is_courtesy']) || (float)$sale->total_amount == 0 || str_contains(strtolower($sale->zone_name ?? ''), 'cortesía') || str_contains(strtolower($sale->zone_name ?? ''), 'cortesia'));

        if ($isCourtesy) {
            return response()->json([
                'success' => false,
                'message' => 'Las entradas de cortesía no aplican para mejoras de zona.',
            ], 422);
        }

        $event = $sale->event;
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el evento correspondiente a esta entrada.',
            ], 404);
        }

        // Validar si el evento ya pasó
        $eventDateStr = !empty($event->event_date) ? (is_string($event->event_date) ? substr($event->event_date, 0, 10) : $event->event_date->format('Y-m-d')) : null;
        if ($eventDateStr) {
            $eventTimeStr = $event->event_time ?: '23:59:59';
            $eventDateTime = \Carbon\Carbon::parse($eventDateStr . ' ' . $eventTimeStr);
            if ($eventDateTime->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El evento ya ha finalizado. No es posible realizar mejoras de entrada.',
                ], 422);
            }
        }

        $zonesRaw = is_array($event->zones) ? $event->zones : (is_string($event->zones) ? json_decode($event->zones, true) : []);
        $quantity = max(1, (int) $sale->quantity);
        $currentUnitPrice = (float) $sale->unit_price;
        if ($currentUnitPrice <= 0 && (float) $sale->total_amount > 0) {
            $currentUnitPrice = round((float) $sale->total_amount / $quantity, 2);
        }

        $upgradeOptions = [];
        $hasAvailableUpgrades = false;

        foreach ($zonesRaw as $idx => $zone) {
            $zoneName = $zone['name'] ?? $zone['capacity_type'] ?? ('Zona ' . ($idx + 1));
            $zonePrice = isset($zone['price']) ? (float) $zone['price'] : 0.00;
            $zoneCapacity = isset($zone['capacity']) ? (int) $zone['capacity'] : 100;
            $zoneColor = $zone['color'] ?? '#FF5500';
            $zoneDescription = $zone['description'] ?? null;

            // Calcular boletos activos en esta zona (excluyendo los anulados por upgrade)
            $activeCount = EventTicket::where('event_id', $event->id)
                ->where('zone_name', $zoneName)
                ->where(function ($q) {
                    $q->whereNull('status')->orWhereNotIn('status', ['upgraded', 'cancelled', 'void']);
                })
                ->count();

            if ($activeCount === 0) {
                // Fallback a ticket_sales si no hay registros individuales en event_tickets
                $activeCount = (int) TicketSale::where('event_id', $event->id)
                    ->where('zone_name', $zoneName)
                    ->where(function ($q) {
                        $q->whereNull('status')->orWhereNotIn('status', ['upgraded', 'cancelled', 'void']);
                    })
                    ->sum('quantity');
            }

            $remainingCapacity = max(0, $zoneCapacity - $activeCount);
            $isCurrent = strtolower(trim($zoneName)) === strtolower(trim($sale->zone_name));

            $unitDifference = max(0, round($zonePrice - $currentUnitPrice, 2));
            $totalDifference = round($unitDifference * $quantity, 2);

            $availableForUpgrade = false;
            $badgeStatus = 'blocked';
            $statusReason = '';

            if ($isCurrent) {
                $statusReason = 'Tu zona actual';
                $badgeStatus = 'current';
            } elseif ($zonePrice <= $currentUnitPrice) {
                $statusReason = 'Zona de igual o menor precio';
                $badgeStatus = 'lower_tier';
            } elseif ($remainingCapacity < $quantity) {
                $statusReason = 'Agotado / Sin espacio suficiente';
                $badgeStatus = 'sold_out';
            } else {
                $availableForUpgrade = true;
                $badgeStatus = 'available';
                $hasAvailableUpgrades = true;
                $statusReason = 'Disponible para mejora';
            }

            $upgradeOptions[] = [
                'name' => $zoneName,
                'price' => $zonePrice,
                'price_formatted' => 'S/ ' . number_format($zonePrice, 2),
                'capacity' => $zoneCapacity,
                'remaining' => $remainingCapacity,
                'color' => $zoneColor,
                'description' => $zoneDescription,
                'is_current' => $isCurrent,
                'unit_difference' => $unitDifference,
                'unit_difference_formatted' => 'S/ ' . number_format($unitDifference, 2),
                'total_difference' => $totalDifference,
                'total_difference_formatted' => 'S/ ' . number_format($totalDifference, 2),
                'available_for_upgrade' => $availableForUpgrade,
                'badge_status' => $badgeStatus,
                'status_reason' => $statusReason,
            ];
        }

        $dateFormatted = 'Fecha por confirmar';
        if (!empty($event->event_date)) {
            try {
                $dateFormatted = $event->event_date instanceof \DateTimeInterface 
                    ? $event->event_date->format('d/m/Y') 
                    : \Carbon\Carbon::parse($event->event_date)->format('d/m/Y');
            } catch (\Exception $e) {
                $dateFormatted = (string) $event->event_date;
            }
        }

        return response()->json([
            'success' => true,
            'has_available_upgrades' => $hasAvailableUpgrades,
            'sale' => [
                'id' => $sale->id,
                'receipt_number' => $sale->receipt_number,
                'buyer_name' => $sale->buyer_name,
                'buyer_dni' => $sale->buyer_dni,
                'zone_name' => $sale->zone_name,
                'quantity' => $quantity,
                'unit_price' => $currentUnitPrice,
                'unit_price_formatted' => 'S/ ' . number_format($currentUnitPrice, 2),
                'total_amount' => (float) $sale->total_amount,
                'total_amount_formatted' => 'S/ ' . number_format((float) $sale->total_amount, 2),
            ],
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'slug' => $event->slug,
                'date_formatted' => $dateFormatted,
                'time_formatted' => $event->event_time ?: '20:00 HRS',
                'venue' => $event->venue_name ?? 'Recinto Oficial',
                'banner_image' => $event->banner_image,
            ],
            'zones' => $upgradeOptions,
        ]);
    }

    /**
     * Descargar / Ver Boleto Oficial en PDF con Autorización Segura
     */
    public function downloadTicketPdf(TicketSale $sale)
    {
        $customerEmail = session('customer_email');
        $customerDni = session('customer_dni');
        $isAdmin = session('admin_logged_in');

        $isAuthorized = false;
        if ($isAdmin) {
            $isAuthorized = true;
        } elseif ($customerDni && $sale->buyer_dni === $customerDni) {
            $isAuthorized = true;
        } elseif ($customerEmail && (str_contains($sale->tickets_data ?? '', $customerEmail) || str_contains($sale->buyer_email ?? '', $customerEmail))) {
            $isAuthorized = true;
        }

        if (!$isAuthorized) {
            abort(403, 'Acceso denegado: No tienes autorización para descargar este boleto.');
        }

        try {
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->set('chroot', [public_path(), base_path()]);

            $dompdf = new \Dompdf\Dompdf($options);
            $html = view('pdf.ticket_voucher', ['sale' => $sale])->render();
            $dompdf->loadHtml($html);
            $dompdf->setPaper([0, 0, 794, 1123], 'portrait');
            $dompdf->render();

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="Boleto_Oficial_' . $sale->receipt_number . '.pdf"',
            ]);
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo generar el boleto: ' . $e->getMessage());
        }
    }

    /**
     * Reenviar Boleto Oficial por Correo con PDF compilado de alta definición
     */
    public function emailTicketPdf(Request $request, TicketSale $sale)
    {
        $customerEmail = session('customer_email');
        $customerDni = session('customer_dni');
        $isAdmin = session('admin_logged_in');

        $isAuthorized = false;
        if ($isAdmin) {
            $isAuthorized = true;
        } elseif ($customerDni && $sale->buyer_dni === $customerDni) {
            $isAuthorized = true;
        } elseif ($customerEmail && (str_contains($sale->tickets_data ?? '', $customerEmail) || str_contains($sale->buyer_email ?? '', $customerEmail))) {
            $isAuthorized = true;
        }

        if (!$isAuthorized) {
            return response()->json(['success' => false, 'message' => 'Acceso denegado: No autorizado.'], 403);
        }

        $recipient = $sale->buyer_email ?: $customerEmail;
        if (empty($recipient) || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'No hay un correo electrónico válido registrado para este boleto.'], 422);
        }

        $pdfBase64 = $request->input('ticket_pdf_base64');

        try {
            \Illuminate\Support\Facades\Mail::to($recipient)->send(new \App\Mail\TicketPurchaseMail($sale, null, false, $pdfBase64));
            return response()->json([
                'success' => true,
                'message' => "¡Boleto oficial enviado exitosamente a {$recipient}!"
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error enviando boleto por correo desde el portal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el correo: ' . $e->getMessage()
            ], 500);
        }
    }
}
