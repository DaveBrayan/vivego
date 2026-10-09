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
     * Muestra el listado completo de clientes registrados y compradores con buscador y paginación
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $perPage = max(5, min(100, (int) $request->input('per_page', 15)));

        $query = User::where(function ($q) {
            $q->where('role', 'customer')
              ->orWhereNull('role');
        });

        if (!empty($search)) {
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('dni', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate($perPage)->withQueryString();

        // Para cada cliente en la página actual, calcular los totales
        $customers->getCollection()->transform(function ($cust) {
            $sales = TicketSale::where(function ($q) use ($cust) {
                if (!empty($cust->dni) && $cust->dni !== '00000000') {
                    $q->orWhere('buyer_dni', $cust->dni);
                }
                if (!empty($cust->email)) {
                    $q->orWhere('tickets_data', 'LIKE', "%{$cust->email}%");
                }
            })->get();

            $cust->total_tickets = (int) $sales->sum('quantity');
            $cust->total_spent = (float) $sales->sum('total_amount');
            $cust->orders_count = $sales->count();
            $cust->last_order = $sales->sortByDesc('created_at')->first();
            return $cust;
        });

        // Métricas globales
        $totalCustomers = User::where(function ($q) {
            $q->where('role', 'customer')
              ->orWhereNull('role');
        })->count();

        $stats = [
            'total_customers' => $totalCustomers,
            'total_tickets_bought' => (int) TicketSale::sum('quantity'),
            'total_revenue' => (float) TicketSale::sum('total_amount'),
        ];

        return view('web.customers', compact('customers', 'stats', 'search', 'perPage'));
    }

    /**
     * Obtiene el detalle completo de boletos, eventos y compras de un cliente específico
     */
    public function getCustomerDetails(int $id): JsonResponse
    {
        $customer = User::findOrFail($id);

        $sales = TicketSale::with(['event', 'eventTickets'])
            ->where(function ($q) use ($customer) {
                if (!empty($customer->dni) && $customer->dni !== '00000000') {
                    $q->orWhere('buyer_dni', $customer->dni);
                }
                if (!empty($customer->email)) {
                    $q->orWhere('tickets_data', 'LIKE', "%{$customer->email}%");
                }
                if (!empty($customer->phone)) {
                    $q->orWhere('buyer_phone', $customer->phone);
                }
            })
            ->latest()
            ->get();

        $summary = [
            'total_tickets' => (int) $sales->sum('quantity'),
            'total_spent' => (float) $sales->sum('total_amount'),
            'total_orders' => $sales->count(),
            'total_events' => $sales->pluck('event_id')->unique()->count(),
        ];

        return response()->json([
            'success' => true,
            'customer' => $customer,
            'summary' => $summary,
            'sales' => $sales,
        ]);
    }

    /**
     * Actualiza los datos del perfil de un cliente
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $customer = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dni' => 'nullable|string|max:30',
            'email' => 'required|email|max:255|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:6',
            'status' => 'nullable|string|in:active,inactive,blocked',
        ], [
            'name.required' => 'El nombre del cliente es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.unique' => 'Ya existe otro usuario registrado con este correo electrónico.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $customer->name = trim($validated['name']);
        $customer->dni = !empty($validated['dni']) ? trim($validated['dni']) : null;
        $customer->email = strtolower(trim($validated['email']));
        $customer->phone = !empty($validated['phone']) ? trim($validated['phone']) : null;
        if (!empty($validated['status'])) {
            $customer->status = $validated['status'];
        }
        if (!empty($validated['password'])) {
            $customer->password = Hash::make($validated['password']);
        }
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => "Datos del cliente \"{$customer->name}\" actualizados correctamente.",
            'customer' => $customer,
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
        $adminId = session('admin_id');
        $loggedAdmin = $adminId ? \App\Models\Administrator::find($adminId) : null;
        if ($loggedAdmin && !$loggedAdmin->canDelete()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para eliminar clientes.',
            ], 403);
        }

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
