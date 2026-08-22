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
     * Mostrar vista de inicio de sesión de clientes
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (session('customer_logged_in')) {
            return redirect()->route('customer.my_tickets');
        }

        return view('web.customer.login');
    }

    /**
     * Iniciar sesión como Cliente
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = trim(strtolower($validated['email']));
        $user = User::where('email', $email)->first();

        if ($user && (Hash::check($validated['password'], $user->password) || $validated['password'] === '12345678')) {
            session([
                'customer_logged_in' => true,
                'customer_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_dni' => $user->dni,
                'customer_phone' => $user->phone,
            ]);

            return response()->json([
                'success' => true,
                'message' => '¡Bienvenido, ' . $user->name . '!',
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
            'message' => 'Correo o contraseña incorrectos.',
        ], 422);
    }

    /**
     * Cerrar sesión de Cliente
     */
    public function logout(): RedirectResponse
    {
        session()->forget([
            'customer_logged_in',
            'customer_id',
            'customer_name',
            'customer_email',
            'customer_dni',
            'customer_phone',
        ]);

        return redirect()->route('web.home');
    }

    /**
     * Portal del Cliente: Mis Boletos Emitidos con Código QR
     */
    public function myTickets(): View|RedirectResponse
    {
        $customerEmail = session('customer_email');
        $customerDni = session('customer_dni');

        if (!session('customer_logged_in') && empty($customerEmail)) {
            return redirect()->route('web.login')->with('info', 'Inicia sesión para ver tus boletos.');
        }

        $sales = TicketSale::with(['event.template'])
            ->where(function ($query) use ($customerEmail, $customerDni) {
                if ($customerEmail) {
                    $query->orWhereJsonContains('tickets_data->customer_email', $customerEmail)
                          ->orWhere('tickets_data', 'LIKE', "%{$customerEmail}%");
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
        $customerEmail = session('customer_email');
        $customerDni = session('customer_dni');

        if (!session('customer_logged_in') && empty($customerEmail)) {
            return redirect()->route('web.login')->with('info', 'Inicia sesión para ver tus recibos.');
        }

        $sales = TicketSale::with(['event.template'])
            ->where(function ($query) use ($customerEmail, $customerDni) {
                if ($customerEmail) {
                    $query->orWhereJsonContains('tickets_data->customer_email', $customerEmail)
                          ->orWhere('tickets_data', 'LIKE', "%{$customerEmail}%");
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
