<?php

namespace App\Http\Middleware;

use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyGateDeviceToken
{
    /**
     * Valida que la solicitud provenga de un dispositivo móvil ViveGo debidamente autenticado y activo.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Device-Token');

        if (!$token) {
            $authHeader = $request->header('Authorization');
            if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
                $token = substr($authHeader, 7);
            }
        }

        if (!$token) {
            $token = $request->input('device_token');
        }

        if (!$token) {
            return response()->json([
                'success' => false,
                'status' => 'unauthorized',
                'message' => 'Token de dispositivo no proporcionado en la solicitud.',
            ], 401);
        }

        $device = Device::where('api_token', $token)->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'status' => 'deleted',
                'message' => 'Dispositivo no encontrado o ha sido eliminado del sistema.',
            ], 401);
        }

        if ($device->status !== 'active') {
            // Actualizar latido/actividad para que el panel web sepa que la app está en línea
            $device->updateQuietly([
                'last_activity_at' => now(),
                'last_ip' => $request->ip(),
            ]);

            // Si es la verificación de estado/latido, permitir continuar para reportar el estado al teléfono
            if ($request->is('api/gate/status')) {
                $request->attributes->set('device', $device);
                return $next($request);
            }

            return response()->json([
                'success' => false,
                'status' => 'revoked',
                'message' => "El dispositivo '{$device->name}' se encuentra bloqueado o revocado por el administrador.",
            ], 403);
        }

        // Actualizar latido / actividad
        $device->updateQuietly([
            'last_activity_at' => now(),
            'last_ip' => $request->ip(),
        ]);

        $request->attributes->set('device', $device);

        return $next($request);
    }
}
