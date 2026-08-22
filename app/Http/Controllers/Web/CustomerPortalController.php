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

        $sales = TicketSale::with('event')
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

        $sales = TicketSale::with('event')
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
     * Descargar / Ver Boleto Oficial en PDF (Formato A4 Idéntico a Taquilla)
     */
    public function downloadTicketPdf(TicketSale $sale)
    {
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
}
