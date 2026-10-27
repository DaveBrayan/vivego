<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'device_uuid',
        'pairing_token',
        'access_pin',
        'api_token',
        'status',
        'assigned_events',
        'device_model',
        'platform',
        'app_version',
        'last_ip',
        'scans_count',
        'last_scanned_at',
        'last_activity_at',
        'paired_at',
    ];

    protected $casts = [
        'assigned_events' => 'array',
        'scans_count' => 'integer',
        'last_scanned_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'paired_at' => 'datetime',
    ];

    /**
     * Determina si el dispositivo está actualmente conectado en línea (actividad reciente en los últimos 3 minutos).
     */
    public function isOnline(): bool
    {
        if ($this->status !== 'active' || !$this->last_activity_at) {
            return false;
        }

        return $this->last_activity_at->diffInMinutes(now()) <= 3;
    }

    /**
     * Comprueba si el dispositivo tiene permiso para escanear un evento determinado.
     */
    public function hasAccessToEvent(int $eventId): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $events = $this->assigned_events;
        if (empty($events) || !is_array($events)) {
            return true; // Sin restricción específica: acceso a todos los eventos
        }

        return in_array($eventId, array_map('intval', $events));
    }

    /**
     * Devuelve los eventos asignados con sus títulos y detalles (el último asignado aparece arriba de todo).
     */
    public function getAssignedEventsList()
    {
        $ids = $this->assigned_events;
        if (empty($ids) || !is_array($ids)) {
            return Event::orderBy('id', 'desc')->get();
        }

        $reversedIds = array_reverse(array_map('intval', $ids));
        $events = Event::whereIn('id', $reversedIds)->get();

        return $events->sortBy(function ($event) use ($reversedIds) {
            $idx = array_search($event->id, $reversedIds);
            return $idx !== false ? $idx : 99999;
        })->values();
    }

    /**
     * Genera el payload JSON que irá dentro del código QR para ser escaneado por la App Flutter.
     */
    public function getPairingQrPayload(?string $serverUrl = null): string
    {
        $effectiveUrl = $serverUrl ?: request()->root();

        return json_encode([
            'vivego_pair' => true,
            'server_url' => rtrim($effectiveUrl, '/'),
            'device_uuid' => $this->device_uuid,
            'token' => $this->pairing_token,
            'access_pin' => $this->access_pin,
            'name' => $this->name,
            'timestamp' => now()->timestamp,
        ], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Genera un PIN numérico único de 4 dígitos que no colisione con otro dispositivo.
     */
    public static function generateUniquePin(): string
    {
        do {
            $pin = str_pad((string) mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('access_pin', $pin)->exists());

        return $pin;
    }

    /**
     * Garantiza que el dispositivo tenga un PIN numérico de 4 dígitos asignado.
     */
    public function ensurePin(): string
    {
        if (empty($this->access_pin) || strlen($this->access_pin) !== 4) {
            $this->access_pin = self::generateUniquePin();
            $this->save();
        }
        return $this->access_pin;
    }
}
