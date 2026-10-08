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
     * Muestra la vista principal de logs de checkout y pasarelas de pago
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('q', ''));
        $levelFilter = strtolower(trim((string) $request->input('level', 'all')));
        $gatewayFilter = strtolower(trim((string) $request->input('gateway', 'all')));
        $perPage = max(10, min(100, (int) $request->input('per_page', 30)));
        $page = max(1, (int) $request->input('page', 1));

        $logPath = $this->getLogFilePath();
        $fileSizeFormatted = '0 KB';
        $fileExists = File::exists($logPath);

        $parsedLogs = [];
        $totalCount = 0;
        $culqiCount = 0;
        $izipayCount = 0;
        $warningErrorCount = 0;

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
                if (str_contains(strtolower($parsed['gateway']), 'culqi')) $culqiCount++;
                if (str_contains(strtolower($parsed['gateway']), 'izipay')) $izipayCount++;
                if (in_array(strtolower($parsed['level']), ['warning', 'error', 'critical', 'alert', 'emergency'])) $warningErrorCount++;

                // Aplicar Filtros de Búsqueda
                if (!empty($search)) {
                    $searchable = strtolower($parsed['timestamp'] . ' ' . $parsed['level'] . ' ' . $parsed['gateway'] . ' ' . $parsed['category'] . ' ' . $parsed['message'] . ' ' . json_encode($parsed['context']));
                    if (!str_contains($searchable, strtolower($search))) {
                        continue;
                    }
                }

                if ($levelFilter !== 'all') {
                    if (strtolower($parsed['level']) !== $levelFilter) {
                        if ($levelFilter === 'error' && !in_array(strtolower($parsed['level']), ['error', 'critical', 'alert', 'emergency'])) {
                            continue;
                        } elseif ($levelFilter !== 'error') {
                            continue;
                        }
                    }
                }

                if ($gatewayFilter !== 'all') {
                    if (!str_contains(strtolower($parsed['gateway']), $gatewayFilter)) {
                        continue;
                    }
                }

                $parsedLogs[] = $parsed;
            }
        }

        // Paginación manual de los registros filtrados
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

        return view('web.checkout_logs', compact(
            'paginatedLogs',
            'totalCount',
            'culqiCount',
            'izipayCount',
            'warningErrorCount',
            'fileSizeFormatted',
            'fileExists',
            'logPath',
            'search',
            'levelFilter',
            'gatewayFilter',
            'perPage'
        ));
    }

    /**
     * Parsea una línea de texto del archivo de log en un objeto estructurado
     */
    protected function parseLogLine(string $line, int $id): ?array
    {
        // Formato estándar Monolog: [YYYY-MM-DD HH:MM:SS] env.LEVEL: Message {JSON context}
        if (!preg_match('/^\[(?P<datetime>\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (?P<env>\w+)\.(?P<level>[A-Z]+):\s*(?P<content>.*)$/s', $line, $matches)) {
            // Línea no estándar (ej. stack trace o texto plano)
            return [
                'id' => $id,
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'date_formatted' => now()->format('d/m/Y H:i:s'),
                'level' => 'INFO',
                'gateway' => 'Sistema',
                'category' => 'Log General',
                'message' => $line,
                'context' => null,
                'raw' => $line,
            ];
        }

        $datetime = $matches['datetime'];
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

        // Determinar Categoría / Etiqueta de Acción
        $category = 'Transacción';
        if (preg_match('/\[(.*?)\]/', $message, $catMatches)) {
            $category = trim($catMatches[1]);
        } elseif (str_contains(strtolower($message), 'webhook')) {
            $category = 'Webhook IPN';
        } elseif (str_contains(strtolower($message), 'polling')) {
            $category = 'Consulta en Vivo';
        } elseif (str_contains(strtolower($message), 'orden')) {
            $category = 'Orden de Pago';
        } elseif (str_contains(strtolower($message), 'tarjeta')) {
            $category = 'Cargo Tarjeta';
        }

        return [
            'id' => $id,
            'timestamp' => $datetime,
            'date_formatted' => date('d/m/Y H:i:s', strtotime($datetime)),
            'level' => $level,
            'gateway' => $gateway,
            'category' => $category,
            'message' => $message,
            'context' => $context,
            'raw' => $line,
        ];
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

        try {
            $initMsg = "[" . date('Y-m-d H:i:s') . "] local.INFO: Archivo de logs de checkout reiniciado por el Administrador. {" . '"action":"log_cleared"' . "}\n";
            File::put($logPath, $initMsg);

            // Si existen logs diarios anteriores, también truncarlos
            $dailyFiles = glob(storage_path('logs/checkout-*.log'));
            foreach ($dailyFiles as $df) {
                if (File::exists($df) && $df !== $logPath) {
                    @unlink($df);
                }
            }

            return back()->with('success', '¡El archivo de logs de checkout ha sido vaciado exitosamente!');
        } catch (\Throwable $e) {
            Log::error('Error al vaciar checkout.log: ' . $e->getMessage());
            return back()->with('error', 'No se pudo vaciar el archivo de logs: ' . $e->getMessage());
        }
    }

    /**
     * Endpoint API para refrescar los logs en vivo vía AJAX sin recargar toda la página
     */
    public function apiFeed(): JsonResponse
    {
        $logPath = $this->getLogFilePath();
        if (!File::exists($logPath)) {
            return response()->json(['success' => true, 'logs' => [], 'count' => 0]);
        }

        $rawContent = File::get($logPath);
        $lines = array_reverse(array_filter(explode("\n", $rawContent)));
        $recentLines = array_slice($lines, 0, 30);

        $parsed = [];
        $id = 0;
        foreach ($recentLines as $line) {
            $item = $this->parseLogLine($line, ++$id);
            if ($item) $parsed[] = $item;
        }

        return response()->json([
            'success' => true,
            'logs' => $parsed,
            'count' => count($parsed),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
