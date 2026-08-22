<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleto Oficial de Entrada - ViveGo</title>
    <style>
        @page {
            margin: 0;
            size: 794px 1123px; /* Exact A4 dimensions */
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            width: 794px;
            height: 1123px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            background: #FFFFFF;
        }
        .page-sheet {
            position: relative;
            width: 794px;
            height: 1123px;
            overflow: hidden;
            page-break-after: always;
        }
        .page-sheet:last-child {
            page-break-after: avoid;
        }
        .bg-sheet-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 794px;
            height: 1123px;
            z-index: 1;
        }
        .ticket-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 794px;
            height: 1123px;
            z-index: 10;
        }
        .ticket-card-bg {
            position: absolute;
            top: 12px;
            left: 11px;
            width: 771px;
            height: 370px;
            background: #0D1527;
            border-radius: 18px;
            z-index: 11;
        }
        .el {
            position: absolute;
            z-index: 20;
        }
    </style>
</head>
<body>
    @php
        $boletoPath = public_path('images/Boleto.jpg');
        $boletoBase64 = file_exists($boletoPath) ? ('data:image/jpeg;base64,' . base64_encode(file_get_contents($boletoPath))) : '';

        $logoPath = public_path('images/logo.png');
        $logoBase64 = file_exists($logoPath) ? ('data:image/png;base64,' . base64_encode(file_get_contents($logoPath))) : '';

        $tData = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data, true);
        $items = $tData['items'] ?? (is_array($tData) ? $tData : []);
        $totalTickets = count($items) > 0 ? count($items) : max(1, $sale->quantity);

        $event = $sale->event;
        $eventTitle = strtoupper($event?->title ?? 'CONCIERTO EN VIVO');
        $eventVenue = $event?->venue_name ?? 'LOCAL: DEJAVU DISCOTECA';
        $eventAddress = $event?->address ?? 'Av. Arenas 153- Abancay 03001';
        $eventDate = !empty($event?->event_date) ? (is_string($event->event_date) ? substr($event->event_date, 0, 10) : $event->event_date->format('Y-m-d')) : '2026-09-11';
        $eventTime = $event?->event_time ?? '18:00';
    @endphp

    @for($i = 0; $i < $totalTickets; $i++)
        @php
            $ticketItem = $items[$i] ?? null;
            $zoneName = $ticketItem['name'] ?? $sale->zone_name;
            $ticketPrice = $ticketItem['price'] ?? $sale->unit_price;
            $numSeq = $sale->id ? ($sale->id + $i) : ($i + 1);
            $ticketNumStr = 'N° ' . str_pad($numSeq, 5, '0', STR_PAD_LEFT);
            $hashVal = 'VG' . strtoupper(substr(md5($sale->receipt_number . $i . $sale->id), 0, 8));

            $qrPayload = "VIVEGO|{$sale->receipt_number}|EVT-{$sale->event_id}|DNI-{$sale->buyer_dni}|TICK-" . ($i + 1);
            $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&margin=0&data=" . urlencode($qrPayload);
            
            $qrImageContent = @file_get_contents($qrApiUrl);
            if (!$qrImageContent && function_exists('curl_init')) {
                $ch = curl_init($qrApiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $qrImageContent = curl_exec($ch);
                curl_close($ch);
            }
            $qrBase64 = $qrImageContent ? ('data:image/png;base64,' . base64_encode($qrImageContent)) : '';
        @endphp

        <div class="page-sheet">
            <!-- 1. FULL PAGE A4 BACKGROUND (Boleto.jpg con Términos y Gracias por tu Compra) -->
            @if($boletoBase64)
                <img src="{{ $boletoBase64 }}" class="bg-sheet-img" alt="Boleto Background">
            @endif

            <!-- 2. TICKET CARD CONTAINER OVERLAY (Exactamente como en Taquilla) -->
            <div class="ticket-overlay">
                <div class="ticket-card-bg"></div>

                <!-- TÍTULO DEL EVENTO -->
                <div class="el" style="top: 106px; left: 165px; color: #FFFFFF; font-size: 15px; font-weight: bold; letter-spacing: 0.5px;">
                    {{ $eventTitle }}
                </div>

                <!-- COMPRADOR -->
                <div class="el" style="top: 142px; left: 112px; color: #FFFFFF; font-size: 12px; font-weight: bold;">
                    Comprador: {{ strtoupper($sale->buyer_name) }}
                </div>

                <!-- DNI -->
                <div class="el" style="top: 164px; left: 110px; color: #FFFFFF; font-size: 12px; font-weight: bold;">
                    DNI: {{ $sale->buyer_dni }}
                </div>

                <!-- LOCAL -->
                <div class="el" style="top: 248px; left: 111px; color: #FFFFFF; font-size: 12px; font-weight: bold;">
                    {{ $eventVenue }}
                </div>

                <!-- DIRECCIÓN -->
                <div class="el" style="top: 265px; left: 110px; color: #94A3B8; font-size: 12px; font-weight: bold;">
                    {{ $eventAddress }}
                </div>

                <!-- FECHA -->
                <div class="el" style="top: 288px; left: 109px; color: #FFFFFF; font-size: 12px; font-weight: bold;">
                    Fecha: {{ $eventDate }}
                </div>

                <!-- HORA -->
                <div class="el" style="top: 287px; left: 243px; color: #FFFFFF; font-size: 12px;">
                    Hora: {{ $eventTime }}
                </div>

                <!-- ZONA -->
                <div class="el" style="top: 207px; left: 361px; color: #00D2C4; font-size: 13px; font-weight: bold;">
                    Zona: {{ strtoupper($zoneName) }}
                </div>

                <!-- PRECIO -->
                <div class="el" style="top: 229px; left: 361px; color: #10B981; font-size: 13px; font-weight: bold;">
                    Precio: S/ {{ number_format($ticketPrice, 2) }}
                </div>

                <!-- LOGO VIVEGO -->
                @if($logoBase64)
                    <div class="el" style="top: 312px; left: 154px; width: 120px;">
                        <img src="{{ $logoBase64 }}" style="width: 110px; height: auto;" alt="Logo">
                    </div>
                @endif

                <!-- N° DE TICKET -->
                <div class="el" style="top: 62px; left: 588px; width: 151px; text-align: center; color: #FFFFFF; font-size: 16px; font-weight: bold;">
                    {{ $ticketNumStr }}
                </div>

                <!-- QR CODE -->
                <div class="el" style="top: 97px; left: 588px; width: 125px; height: 125px; background: #FFFFFF; border-radius: 12px; padding: 6px; text-align: center;">
                    @if($qrBase64)
                        <img src="{{ $qrBase64 }}" style="width: 100%; height: 100%; display: block; border-radius: 4px;" alt="QR Code">
                    @endif
                </div>

                <!-- HASH CODE -->
                <div class="el" style="top: 269px; left: 580px; width: 155px; text-align: center; color: #FFFFFF; font-family: monospace; font-size: 13px; font-weight: bold; letter-spacing: 1px;">
                    {{ $hashVal }}
                </div>

                <!-- DISCLAIMER -->
                <div class="el" style="top: 300px; left: 575px; width: 174px; text-align: center; color: #64748B; font-size: 8px; line-height: 1.15;">
                    La responsabilidad de este boleto es exclusiva del cliente, no compartir ni publicar. Se recomienda llevar impreso.
                </div>
            </div>
        </div>
    @endfor
</body>
</html>
