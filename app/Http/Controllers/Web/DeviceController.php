<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Device;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DeviceController extends Controller
{
    /**
     * Muestra la lista de dispositivos de control de acceso y terminales móviles.
     */
    public function index(Request $request): View
    {
        $settings = Setting::first();
        $organizer = Company::first();

        $devices = Device::orderBy('id', 'desc')->get();
        $allEvents = Event::orderBy('id', 'desc')->get()->map(function ($ev) {
            $zones = is_string($ev->zones) ? json_decode($ev->zones, true) : (is_array($ev->zones) ? $ev->zones : []);
            $totalCapacity = 0;
            if (!empty($zones)) {
                foreach ($zones as $z) {
                    $totalCapacity += (int) ($z['capacity'] ?? $z['stock'] ?? 0);
                }
            }
            $ticketsCount = EventTicket::where('event_id', $ev->id)->where('status', '!=', 'upgraded')->count();
            $ticketsUsed = EventTicket::where('event_id', $ev->id)->where('is_used', true)->where('status', '!=', 'upgraded')->count();
            if ($totalCapacity == 0) {
                $totalCapacity = max($ticketsCount, 100);
            }

            $ev->total_capacity = $totalCapacity;
            $ev->tickets_count = $ticketsCount;
            $ev->tickets_used = $ticketsUsed;
            return $ev;
        });

        // Métricas de dispositivos
        $totalDevices = $devices->count();
        $activeDevices = $devices->where('status', 'active')->count();
        $pendingDevices = $devices->where('status', 'pending')->count();
        $totalScans = (int) $devices->sum('scans_count');

        // Determinar la mejor URL sugerida para el emparejamiento (detectando IP local de red o dominio público)
        $serverHost = request()->getHttpHost();
        $scheme = request()->getScheme();
        $detectedUrl = "{$scheme}://{$serverHost}";

        // Si se accede desde localhost o 127.0.0.1, sugerir la IP de red local para que el celular se conecte por Wi-Fi
        if (str_contains($serverHost, '127.0.0.1') || str_contains($serverHost, 'localhost')) {
            $localIp = '192.168.100.129';
            $port = request()->getPort() ?: 8000;
            $detectedUrl = "http://{$localIp}:{$port}";
        }

        return view('web.devices', compact(
            'devices',
            'allEvents',
            'totalDevices',
            'activeDevices',
            'pendingDevices',
            'totalScans',
            'detectedUrl',
            'settings',
            'organizer'
        ));
    }

    /**
     * Crea un nuevo dispositivo y genera su token y código QR de vinculación.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'assigned_events' => 'nullable|array',
            'server_url' => 'nullable|string',
        ]);

        $device = Device::create([
            'name' => trim($validated['name']),
            'device_uuid' => (string) Str::uuid(),
            'pairing_token' => Str::random(32),
            'status' => 'pending',
            'assigned_events' => !empty($validated['assigned_events']) ? array_map('intval', $validated['assigned_events']) : null,
        ]);

        $serverUrl = !empty($validated['server_url']) ? trim($validated['server_url']) : null;
        $qrPayload = $device->getPairingQrPayload($serverUrl);

        return response()->json([
            'success' => true,
            'message' => "¡Dispositivo '{$device->name}' registrado con éxito! Escanea el código QR para vincularlo.",
            'device' => $device,
            'qr_payload' => $qrPayload,
        ]);
    }

    /**
     * Actualiza el nombre o los eventos asignados a un dispositivo existente.
     */
    public function update(Request $request, Device $device): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'assigned_events' => 'nullable|array',
        ]);

        $device->update([
            'name' => trim($validated['name']),
            'assigned_events' => !empty($validated['assigned_events']) ? array_map('intval', $validated['assigned_events']) : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Dispositivo '{$device->name}' actualizado correctamente.",
            'device' => $device,
        ]);
    }

    /**
     * Elimina un dispositivo del sistema.
     */
    public function destroy(Device $device): JsonResponse
    {
        $name = $device->name;
        $device->delete();

        return response()->json([
            'success' => true,
            'message' => "El dispositivo '{$name}' ha sido eliminado del sistema.",
        ]);
    }

    /**
     * Regenera el código QR y token de vinculación para re-emparejar un teléfono.
     */
    public function regenerateQr(Request $request, Device $device): JsonResponse
    {
        $serverUrl = $request->input('server_url');

        $device->update([
            'pairing_token' => Str::random(32),
            'api_token' => null,
            'status' => 'pending',
        ]);

        $qrPayload = $device->getPairingQrPayload($serverUrl);

        return response()->json([
            'success' => true,
            'message' => 'Nuevo código QR de vinculación generado.',
            'device' => $device,
            'qr_payload' => $qrPayload,
        ]);
    }

    /**
     * Cambia el estado del dispositivo (bloquear/revocar o reactivar).
     */
    public function toggleStatus(Device $device): JsonResponse
    {
        $newStatus = $device->status === 'revoked' ? ($device->api_token ? 'active' : 'pending') : 'revoked';

        $device->update([
            'status' => $newStatus,
        ]);

        $statusMsg = $newStatus === 'revoked' ? 'bloqueado/revocado' : 'reactivado';

        return response()->json([
            'success' => true,
            'message' => "El dispositivo '{$device->name}' fue {$statusMsg}.",
            'status' => $newStatus,
        ]);
    }
}
