<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\TicketSale;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Muestra el listado completo de clientes registrados y compradores
     */
    public function index(): View
    {
        // 1. Obtener usuarios clientes registrados
        $customers = User::where('role', 'customer')
            ->orWhereNull('role')
            ->latest()
            ->get()
            ->map(function ($cust) {
                $sales = TicketSale::where(function ($q) use ($cust) {
                    if (!empty($cust->dni) && $cust->dni !== '00000000') {
                        $q->orWhere('buyer_dni', $cust->dni);
                    }
                    if (!empty($cust->email)) {
                        $q->orWhere('tickets_data', 'LIKE', "%{$cust->email}%");
                    }
                })->get();

                $cust->total_tickets = $sales->sum('quantity');
                $cust->total_spent = (float) $sales->sum('total_amount');
                $cust->orders_count = $sales->count();
                $cust->last_order = $sales->sortByDesc('created_at')->first();
                return $cust;
            });

        $stats = [
            'total_customers' => $customers->count(),
            'total_tickets_bought' => $customers->sum('total_tickets'),
            'total_revenue' => $customers->sum('total_spent'),
        ];

        return view('web.customers', compact('customers', 'stats'));
    }

    /**
     * Obtiene el detalle de boletos y compras de un cliente específico
     */
    public function getCustomerDetails(int $id): JsonResponse
    {
        $customer = User::findOrFail($id);

        $sales = TicketSale::with('event')
            ->where(function ($q) use ($customer) {
                if ($customer->dni) {
                    $q->orWhere('buyer_dni', $customer->dni);
                }
                if ($customer->email) {
                    $q->orWhere('tickets_data', 'LIKE', "%{$customer->email}%");
                }
            })
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'customer' => $customer,
            'sales' => $sales,
        ]);
    }

    /**
     * Resetea la contraseña de un cliente y genera una nueva contraseña temporal
     */
    public function resetPassword(Request $request, int $id): JsonResponse
    {
        $customer = User::findOrFail($id);

        $newPassword = 'VG-' . rand(100000, 999999);
        if ($request->filled('custom_password')) {
            $newPassword = $request->input('custom_password');
        }

        $customer->password = Hash::make($newPassword);
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Contraseña reseteada exitosamente para ' . $customer->name,
            'new_password' => $newPassword,
            'customer_email' => $customer->email,
        ]);
    }

    /**
     * Elimina el cliente y su cuenta de usuario (conservando intactas las entradas y ventas en el sistema)
     */
    public function destroy(int $id): JsonResponse
    {
        $customer = User::findOrFail($id);
        $customerName = $customer->name;

        // Eliminar únicamente el registro de usuario del cliente
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => "El cliente \"{$customerName}\" y su cuenta fueron eliminados correctamente (las entradas y ventas se conservan en el sistema).",
        ]);
    }
}
