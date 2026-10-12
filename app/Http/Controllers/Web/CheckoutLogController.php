<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CheckoutLogController extends Controller
{
    /**
     * Retorna la ruta física del archivo de log de checkout principal
     */
    protected function getLogFilePath(): string
    {
        $singlePath = storage_path('logs/checkout.log');
        if (File::exists($singlePath)) {
            return $singlePath;
        }

        // Fallback a archivos diarios recientes si existen
        $dailyFiles = glob(storage_path('logs/checkout-*.log'));
        if (!empty($dailyFiles)) {
            rsort($dailyFiles);
            return $dailyFiles[0];
        }

        return $singlePath;
    }

    /**
     * Muestra la vista principal de logs de checkout y pasarelas de pago con modo Terminal y Tabla
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $levelFilter = strtolower(trim((string) $request->input('level', 'all')));
        $gatewayFilter = strtolower(trim((string) $request->input('gateway', 'all')));
        $actionFilter = strtolower(trim((string) $request->input('action', 'all')));
        $dateFilter = strtolower(trim((string) $request->input('date', 'today'))); // 'today', 'all', o 'YYYY-MM-DD'
        $limitFilter = trim((string) $request->input('limit', '500')); // '150', '300', '500', '1000', 'all'
        $perPage = max(10, min(100, (int) $request->input('per_page', 30)));
        $page = max(1, (int) $request->input('page', 1));
        $viewMode = $request->input('view_mode', 'terminal'); // 'terminal' o 'table'

        $logPath = $this->getLogFilePath();
        $logsDir = storage_path('logs');
        $isLogsWritable = is_dir($logsDir) ? is_writable($logsDir) : false;
        $fileExists = File::exists($logPath);

        // Auto-crear archivo de auditoría inicial si no existe en producción
        if (!$fileExists && $isLogsWritable) {
            try {
                if (!File::isDirectory($logsDir)) {
                    File::makeDirectory($logsDir, 0775, true, true);
                }
                $now = date('Y-m-d H:i:s');
                $env = app()->environment();
                $initMsg = "[{$now}] {$env}.INFO: 🚀 [SISTEMA] Archivo de auditoría checkout.log inicializado exitosamente en el servidor. {\"status\":\"ready\",\"auto_created\":true}\n";
                File::put($logPath, $initMsg);
                @chmod($logPath, 0664);
                $fileExists = true;
            } catch (\Throwable $e) {
                // Silencioso ante excepciones
            }
        }

        $fileSizeFormatted = '0 KB';

        $parsedLogs = [];
        $totalCount = 0;
        $todayCount = 0;
        $culqiCount = 0;
        $izipayCount = 0;
        $warningErrorCount = 0;
        $ticketEventsCount = 0;
        $emailEventsCount = 0;

        $todayDate = date('Y-m-d');
        $serverDate = now()->format('Y-m-d');

        if ($fileExists) {
            $bytes = File::size($logPath);
            $fileSizeFormatted = $bytes >= 1048576 
                ? number_format($bytes / 1048576, 2) . ' MB' 
                : number_format(max(0.1, $bytes / 1024), 1) . ' KB';

            $rawContent = File::get($logPath);
            $lines = explode("\n", $rawContent);

            // Invertir para mostrar lo más reciente primero
            $lines = array_reverse($lines);

            $idCounter = 0;
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                $parsed = $this->parseLogLine($line, ++$idCounter);
                if (!$parsed) continue;

                $totalCount++;
                $isTodayLog = ($parsed['date'] === $todayDate || $parsed['date'] === $serverDate);
                if ($isTodayLog) {
                    $todayCount++;
                }

                if (str_contains(strtolower($parsed['gateway']), 'culqi')) $culqiCount++;
                if (str_contains(strtolower($parsed['gateway']), 'izipay')) $izipayCount++;
                if (in_array(strtolower($parsed['level']), ['warning', 'error', 'critical', 'alert', 'emergency'])) $warningErrorCount++;
                if (str_contains($parsed['category'], 'ENTREGA_BOLETOS') || str_contains($parsed['message'], 'boletos') || str_contains($parsed['action_badge']['key'], 'boletos')) $ticketEventsCount++;
                if (str_contains($parsed['category'], 'CORREO') || str_contains($parsed['message'], 'correo') || str_contains($parsed['action_badge']['key'], 'correo')) $emailEventsCount++;

                // 1. Filtro por Fecha: 'today' (predeterminado) o 'all' o fecha específica
                if ($dateFilter === 'today') {
                    if (!$isTodayLog) {
                        continue;
                    }
                } elseif ($dateFilter !== 'all') {
                    // Fecha exacta seleccionada
                    if ($parsed['date'] !== $dateFilter) {
                        continue;
                    }
                }

                // 2. Filtro de Búsqueda de Texto
                if (!empty($search)) {
                    $searchable = strtolower(
                        $parsed['timestamp'] . ' ' . 
                        $parsed['level'] . ' ' . 
                        $parsed['gateway'] . ' ' . 
                        $parsed['category'] . ' ' . 
                        $parsed['action_badge']['text'] . ' ' . 
                        $parsed['clean_message'] . ' ' . 
                        $parsed['message'] . ' ' . 
                        json_encode($parsed['context'])
                    );
                    if (!str_contains($searchable, strtolower($search))) {
                        continue;
                    }
                }

                // 3. Filtro por Nivel
                if ($levelFilter !== 'all') {
                    if (strtolower($parsed['level']) !== $levelFilter) {
                        if ($levelFilter === 'error' && !in_array(strtolower($parsed['level']), ['error', 'critical', 'alert', 'emergency'])) {
                            continue;
                        } elseif ($levelFilter !== 'error') {
                            continue;
                        }
                    }
                }

                // 4. Filtro por Pasarela
                if ($gatewayFilter !== 'all') {
                    if (!str_contains(strtolower($parsed['gateway']), $gatewayFilter)) {
                        continue;
                    }
                }

                // 5. Filtro por Acción / Badge
                if ($actionFilter !== 'all') {
                    if ($parsed['action_badge']['key'] !== $actionFilter) {
                        continue;
                    }
                }

                $parsedLogs[] = $parsed;
            }
        }

        // Paginación de registros filtrados para la vista de tabla
        $filteredTotal = count($parsedLogs);
        $offset = ($page - 1) * $perPage;
        $currentPageItems = array_slice($parsedLogs, $offset, $perPage);

        $paginatedLogs = new LengthAwarePaginator(
            $currentPageItems,
            $filteredTotal,
            $perPage,
            $page,
            ['path' => route('web.checkout_logs'), 'query' => $request->query()]
        );

        // Logs para la vista de terminal según el límite seleccionado (150, 300, 500, 1000 o Todos)
        if ($limitFilter === 'all' || (int)$limitFilter <= 0) {
            $terminalLogs = $parsedLogs;
        } else {
            $terminalLogs = array_slice($parsedLogs, 0, (int)$limitFilter);
        }

        return view('web.checkout_logs', compact(
            'paginatedLogs',
            'terminalLogs',
            'totalCount',
            'todayCount',
            'culqiCount',
            'izipayCount',
            'warningErrorCount',
            'ticketEventsCount',
            'emailEventsCount',
            'fileSizeFormatted',
            'fileExists',
            'isLogsWritable',
            'logPath',
            'search',
            'dateFilter',
            'limitFilter',
            'levelFilter',
            'gatewayFilter',
            'actionFilter',
            'perPage',
            'viewMode'
        ));
    }

    /**
     * Parsea una línea de texto del archivo de log en un objeto estructurado con Action Badges coloridos
     */
    public function parseLogLine(string $line, int $id): ?array
    {
        // Formato estándar Monolog: [YYYY-MM-DD HH:MM:SS] env.LEVEL: Message {JSON context}
        if (!preg_match('/^\[(?P<datetime>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}(?:\.\d+)?)\] (?P<env>[\w\-]+)\.(?P<level>[A-Z]+):\s*(?P<content>.*)$/s', $line, $matches)) {
            // Línea no estándar (ej. texto plano)
            return [
                'id' => $id,
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'date' => now()->format('Y-m-d'),
                'time_only' => now()->format('H:i:s'),
                'date_formatted' => now()->format('d/m/Y H:i:s'),
                'level' => 'INFO',
                'gateway' => 'Sistema',
                'category' => 'Log General',
                'action_badge' => [
                    'key' => 'sistema',
                    'text' => 'SISTEMA',
                    'class' => 'badge-system',
                ],
                'message' => $line,
                'clean_message' => $line,
                'context' => null,
                'raw' => $line,
            ];
        }

        $datetime = $matches['datetime'];
        $logDate = substr($datetime, 0, 10);
        $level = strtoupper($matches['level']);
        $rawContent = trim($matches['content']);

        // Extraer JSON Context del final si existe
        $context = null;
        $message = $rawContent;

        $lastBrace = strrpos($rawContent, '{');
        if ($lastBrace !== false && str_ends_with($rawContent, '}')) {
            $possibleJson = substr($rawContent, $lastBrace);
            $decoded = json_decode($possibleJson, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $context = $decoded;
                $message = trim(substr($rawContent, 0, $lastBrace));
            }
        }

        // Determinar Pasarela
        $gateway = 'Checkout';
        if (str_contains($message, 'Culqi') || str_contains($rawContent, 'culqi') || str_contains($rawContent, 'ord_') || str_contains($rawContent, 'chr_')) {
            $gateway = 'Culqi Perú';
        } elseif (str_contains($message, 'Izipay') || str_contains($rawContent, 'izipay')) {
            $gateway = 'Izipay';
        } elseif (str_contains($message, 'Cortesía') || str_contains($rawContent, 'cortesia')) {
            $gateway = 'Cortesía Web';
        }

        // Determinar Categoría Base
        $category = 'TRANSMISIÓN';
        if (preg_match('/\[(.*?)\]/', $message, $catMatches)) {
            $category = trim($catMatches[1]);
        }

        // Determinar Action Badge específico y su diseño visual
        $actionBadge = $this->determineActionBadge($message, $level);

        // Limpiar mensaje eliminando prefijos redundantes
        $cleanMessage = $this->cleanMessageText($message, $actionBadge['text'], $gateway);

        $timeOnly = '';
        try {
            $timeOnly = date('H:i:s', strtotime($datetime));
        } catch (\Throwable $e) {
            $timeOnly = substr($datetime, 11, 8);
        }

        return [
            'id' => $id,
            'timestamp' => $datetime,
            'date' => $logDate,
            'time_only' => $timeOnly,
            'date_formatted' => date('d/m/Y H:i:s', strtotime($datetime)),
            'level' => $level,
            'gateway' => $gateway,
            'category' => $category,
            'action_badge' => $actionBadge,
            'clean_message' => $cleanMessage,
            'message' => $message,
            'context' => $context,
            'raw' => $line,
        ];
    }

    /**
     * Determina el badge de estado / evento según el contenido de la operación
     */
    protected function determineActionBadge(string $message, string $level): array
    {
        $lower = strtolower($message . ' ' . $level);

        // 1. Errores y Rechazos
        if (in_array(strtolower($level), ['error', 'critical', 'alert', 'emergency']) || 
            str_contains($lower, 'rechazado') || 
            str_contains($lower, 'falló') || 
            str_contains($lower, 'inválida') || 
            str_contains($lower, 'error')) {
            return [
                'key' => 'error',
                'text' => 'ERROR / RECHAZO',
                'class' => 'badge-error-alerta',
            ];
        }

        // 2. Orden Creada / Sesión Creada
        if (str_contains($lower, 'orden creada') || 
            str_contains($lower, 'orden qr generada') || 
            str_contains($lower, 'sesión token creada') || 
            str_contains($lower, 'orden generada')) {
            return [
                'key' => 'orden_creada',
                'text' => 'ORDEN CREADA',
                'class' => 'badge-orden-creada',
            ];
        }

        // 3. Esperando Pago (Polling silencioso)
        if (str_contains($lower, 'esperando pago') || 
            str_contains($lower, 'esperando que el cliente') || 
            str_contains($lower, 'esperando confirmación')) {
            return [
                'key' => 'esperando_pago',
                'text' => 'ESPERANDO PAGO',
                'class' => 'badge-esperando-pago',
            ];
        }

        // 4. Verificación en API
        if (str_contains($lower, 'verificación api') || 
            str_contains($lower, 'verificacion api') || 
            str_contains($lower, 'consultada en culqi')) {
            return [
                'key' => 'verificacion_api',
                'text' => 'VERIFICACIÓN API',
                'class' => 'badge-verificacion-api',
            ];
        }

        // 5. Pago Confirmado
        if (str_contains($lower, 'pago confirmado') || 
            str_contains($lower, 'pagada exitosamente') || 
            str_contains($lower, 'estado: paid') || 
            str_contains($lower, 'pago procesado exitosamente')) {
            return [
                'key' => 'pago_confirmado',
                'text' => 'PAGO CONFIRMADO',
                'class' => 'badge-pago-confirmado',
            ];
        }

        // 6. Venta Generada / Venta Completada
        if (str_contains($lower, 'venta completada') || 
            str_contains($lower, 'venta qr completada') || 
            str_contains($lower, 'venta tarjeta completada') || 
            str_contains($lower, 'venta emitida')) {
            return [
                'key' => 'venta_generada',
                'text' => 'VENTA GENERADA',
                'class' => 'badge-venta-generada',
            ];
        }

        // 7. Webhook Notificación Asíncrona (IPN)
        if (str_contains($lower, 'webhook')) {
            return [
                'key' => 'webhook',
                'text' => 'WEBHOOK RECIBIDO',
                'class' => 'badge-webhook',
            ];
        }

        // 8. Idempotencia (Orden ya registrada)
        if (str_contains($lower, 'idempotencia') || str_contains($lower, 'ya procesada') || str_contains($lower, 'ya existe en bd')) {
            return [
                'key' => 'idempotencia',
                'text' => 'IDEMPOTENCIA',
                'class' => 'badge-idempotencia',
            ];
        }

        // 9. Entrega de Boletos
        if (str_contains($lower, 'entrega_boletos') || str_contains($lower, 'entrega boletos') || str_contains($lower, 'boletos')) {
            return [
                'key' => 'boletos',
                'text' => 'ENTREGA BOLETOS',
                'class' => 'badge-entrega-boletos',
            ];
        }

        // 10. Despacho de Correo Electrónico
        if (str_contains($lower, 'correo') || str_contains($lower, 'email') || str_contains($lower, 'mail')) {
            return [
                'key' => 'correo',
                'text' => 'ENVÍO CORREO',
                'class' => 'badge-envio-correo',
            ];
        }

        // 11. Ingreso al Checkout (si hubiera históricos)
        if (str_contains($lower, 'ingreso_checkout') || str_contains($lower, 'ingresó al checkout')) {
            return [
                'key' => 'ingreso',
                'text' => 'INGRESO CHECKOUT',
                'class' => 'badge-ingreso',
            ];
        }

        // 12. Polling histórico
        if (str_contains($lower, 'polling')) {
            return [
                'key' => 'polling',
                'text' => 'POLLING',
                'class' => 'badge-polling',
            ];
        }

        // Default
        return [
            'key' => 'evento',
            'text' => 'EVENTO',
            'class' => 'badge-system',
        ];
    }

    /**
     * Limpia el texto del mensaje para que no repita innecesariamente el nombre de la pasarela o la categoría
     */
    protected function cleanMessageText(string $message, string $badgeText, string $gateway): string
    {
        $clean = $message;

        // Remover prefijos comunes como "Culqi [Verificación API]: " o "Izipay [Venta Completada]: "
        $patterns = [
            '/^(?:Culqi|Izipay|Cortesía)\s*\[.*?\]:\s*/iu',
            '/^\[(?:ENTREGA_BOLETOS|INGRESO_CHECKOUT|SISTEMA|REINICIO_LOGS)\]\s*/iu',
            '/^👤\s*\[INGRESO_CHECKOUT\]\s*/iu',
            '/^🎟️\s*\[ENTREGA_BOLETOS\]\s*/iu',
            '/^🚀\s*\[SISTEMA\]\s*/iu',
            '/^🚀\s*\[REINICIO_LOGS\]\s*/iu',
        ];

        foreach ($patterns as $pattern) {
            $replaced = preg_replace($pattern, '', $clean);
            if (!empty($replaced)) {
                $clean = trim($replaced);
            }
        }

        return $clean ?: $message;
    }

    /**
     * Descarga el archivo de log físico
     */
    public function download(): BinaryFileResponse|RedirectResponse
    {
        $logPath = $this->getLogFilePath();

        if (!File::exists($logPath)) {
            return back()->with('error', 'El archivo de log aún no contiene registros para descargar.');
        }

        $filename = 'checkout-logs-' . date('Y-m-d_His') . '.log';
        return response()->download($logPath, $filename, [
            'Content-Type' => 'text/plain',
        ]);
    }

    /**
     * Limpia / Vacía el archivo de log de checkout
     */
    public function clear(): RedirectResponse
    {
        $logPath = storage_path('logs/checkout.log');
        $logsDir = storage_path('logs');

        try {
            if (!File::isDirectory($logsDir)) {
                File::makeDirectory($logsDir, 0775, true, true);
            }

            $now = date('Y-m-d H:i:s');
            $env = app()->environment();
            $initMsg = "[{$now}] {$env}.INFO: 🚀 [REINICIO_LOGS] Archivo de logs de checkout reiniciado por el Administrador. " . json_encode(['action' => 'log_cleared', 'timestamp' => $now, 'environment' => $env]) . "\n";
            File::put($logPath, $initMsg);
            @chmod($logPath, 0664);

            // Si existen logs diarios anteriores, también truncarlos
            $dailyFiles = glob(storage_path('logs/checkout-*.log'));
            if ($dailyFiles) {
                foreach ($dailyFiles as $df) {
                    if (File::exists($df) && $df !== $logPath) {
                        @unlink($df);
                    }
                }
            }

            return back()->with('success', '¡El archivo de logs de checkout ha sido reiniciado e inicializado exitosamente!');
        } catch (\Throwable $e) {
            Log::error('Error al vaciar checkout.log: ' . $e->getMessage());
            return back()->with('error', 'No se pudo vaciar o inicializar el archivo de logs: ' . $e->getMessage());
        }
    }

    /**
     * Endpoint API para refrescar los logs en vivo vía AJAX sin recargar toda la página
     */
    public function apiFeed(Request $request): JsonResponse
    {
        $logPath = $this->getLogFilePath();
        if (!File::exists($logPath)) {
            return response()->json([
                'success' => true,
                'logs' => [],
                'count' => 0,
                'stats' => [
                    'total' => 0,
                    'today' => 0,
                    'culqi' => 0,
                    'izipay' => 0,
                    'errors' => 0,
                ]
            ]);
        }

        $dateFilter = strtolower(trim((string) $request->input('date', 'today')));
        $limit = max(10, min(1000, (int) $request->input('limit', 300)));

        $rawContent = File::get($logPath);
        $lines = array_reverse(array_filter(explode("\n", $rawContent)));

        $todayDate = date('Y-m-d');
        $serverDate = now()->format('Y-m-d');

        $parsed = [];
        $id = 0;
        $totalCount = 0;
        $todayCount = 0;
        $culqiCount = 0;
        $izipayCount = 0;
        $errorCount = 0;

        foreach ($lines as $line) {
            $item = $this->parseLogLine($line, ++$id);
            if (!$item) continue;

            $totalCount++;
            $isToday = ($item['date'] === $todayDate || $item['date'] === $serverDate);
            if ($isToday) $todayCount++;

            if (str_contains(strtolower($item['gateway']), 'culqi')) $culqiCount++;
            if (str_contains(strtolower($item['gateway']), 'izipay')) $izipayCount++;
            if (in_array(strtolower($item['level']), ['warning', 'error', 'critical', 'alert', 'emergency'])) $errorCount++;

            // Filtrar fecha si está activado 'today'
            if ($dateFilter === 'today' && !$isToday) {
                continue;
            } elseif ($dateFilter !== 'today' && $dateFilter !== 'all' && $item['date'] !== $dateFilter) {
                continue;
            }

            if (count($parsed) < $limit) {
                $parsed[] = $item;
            }
        }

        return response()->json([
            'success' => true,
            'logs' => $parsed,
            'count' => count($parsed),
            'stats' => [
                'total' => $totalCount,
                'today' => $todayCount,
                'culqi' => $culqiCount,
                'izipay' => $izipayCount,
                'errors' => $errorCount,
            ],
            'timestamp' => now()->format('Y-m-d H:i:s.v'),
        ]);
    }
}
