@extends('layouts.app')

@section('title', 'Mis Boletos Oficiales | ViveGo Perú')

@section('content')
<div class="customer-portal-wrapper" style="background: #F8FAFC; min-height: 88vh; padding: 2rem 1.25rem 5rem 1.25rem;">
    <div class="container" style="max-width: 1140px; margin: 0 auto;">
        
        <!-- HERO GREETING & METRICS -->
        <div style="background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 60%, #0F172A 100%); border-radius: 24px; padding: 2rem 2.25rem; margin-bottom: 2.25rem; box-shadow: 0 15px 35px -10px rgba(15, 23, 42, 0.35); position: relative; overflow: hidden; color: #FFFFFF;">
            <!-- Glow Accent -->
            <div style="position: absolute; top: -50px; right: -50px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(255,85,0,0.3) 0%, rgba(255,85,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>

            <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
                <div>
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); padding: 0.3rem 0.85rem; border-radius: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #00D2C4; margin-bottom: 0.75rem;">
                        <span>👤</span> PORTAL EXCLUSIVO DE CLIENTES
                    </div>
                    <h1 style="font-size: 2.1rem; font-weight: 900; color: #FFFFFF; margin: 0 0 0.35rem 0; letter-spacing: -0.5px;">
                        ¡Hola, {{ session('customer_name') ?: 'Cliente ViveGo' }}! 👋
                    </h1>
                    <p style="color: #94A3B8; font-size: 0.95rem; margin: 0;">
                        Aquí tienes el control de todas tus entradas oficiales y boletos digitales adquiridos.
                    </p>
                </div>

                <!-- KPI Quick Stats -->
                @php
                    $totalTicketsCount = 0;
                    $activeCount = 0;
                    $pastCount = 0;

                    foreach($sales as $s) {
                        $tData = is_array($s->tickets_data) ? $s->tickets_data : json_decode($s->tickets_data, true);
                        $items = $tData['items'] ?? (is_array($tData) ? $tData : []);
                        $qty = count($items) > 0 ? array_sum(array_column($items, 'quantity')) : max(1, $s->quantity);
                        $totalTicketsCount += $qty;

                        $eDateStr = !empty($s->event?->event_date) ? (is_string($s->event->event_date) ? substr($s->event->event_date, 0, 10) : $s->event->event_date->format('Y-m-d')) : null;
                        $isPast = false;
                        if ($eDateStr) {
                            $eTime = $s->event?->event_time ?: '23:59:59';
                            $dt = \Carbon\Carbon::parse($eDateStr . ' ' . $eTime);
                            $isPast = $dt->isPast();
                        }
                        if ($isPast) {
                            $pastCount += $qty;
                        } else {
                            $activeCount += $qty;
                        }
                    }
                @endphp

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 16px; padding: 0.85rem 1.25rem; text-align: center; min-width: 110px; backdrop-filter: blur(8px);">
                        <span style="font-size: 1.5rem; font-weight: 900; color: #10B981; display: block;">{{ $activeCount }}</span>
                        <span style="font-size: 0.75rem; color: #CBD5E1; font-weight: 700; text-transform: uppercase;">Activas</span>
                    </div>
                    <div style="background: rgba(255,255,255,0.06); border: 1.5px solid rgba(255,255,255,0.12); border-radius: 16px; padding: 0.85rem 1.25rem; text-align: center; min-width: 110px; backdrop-filter: blur(8px);">
                        <span style="font-size: 1.5rem; font-weight: 900; color: #94A3B8; display: block;">{{ $pastCount }}</span>
                        <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 700; text-transform: uppercase;">Finalizadas</span>
                    </div>
                    <div style="background: rgba(255,85,0,0.15); border: 1.5px solid rgba(255,85,0,0.35); border-radius: 16px; padding: 0.85rem 1.25rem; text-align: center; min-width: 110px; backdrop-filter: blur(8px);">
                        <span style="font-size: 1.5rem; font-weight: 900; color: #FF5500; display: block;">{{ $totalTicketsCount }}</span>
                        <span style="font-size: 0.75rem; color: #FFD2BD; font-weight: 700; text-transform: uppercase;">Total Boletos</span>
                    </div>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div style="background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #991B1B; padding: 1rem 1.25rem; border-radius: 16px; margin-bottom: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.08);">
                <span style="font-size: 1.4rem;">⛔</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if(session('warning'))
            <div style="background: #FFFBEB; border: 1.5px solid #FDE68A; color: #92400E; padding: 1rem 1.25rem; border-radius: 16px; margin-bottom: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
                <span style="font-size: 1.4rem;">⚠️</span>
                <div>{{ session('warning') }}</div>
            </div>
        @endif

        @if(session('success'))
            <div style="background: #ECFDF5; border: 1.5px solid #A7F3D0; color: #065F46; padding: 1rem 1.25rem; border-radius: 16px; margin-bottom: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem;">
                <span style="font-size: 1.4rem;">✨</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if($sales->isEmpty())
            <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 24px; padding: 4rem 2rem; text-align: center; box-shadow: 0 10px 30px -5px rgba(0,0,0,0.05); animation: fadeIn 0.4s ease-out;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: #FFF7ED; border: 2px solid #FFEDD5; color: #FF5500; font-size: 2.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem auto;">
                    🎟️
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 900; color: #0F172A; margin: 0 0 0.5rem 0;">Aún no tienes boletos registrados</h3>
                <p style="color: #64748B; font-size: 0.95rem; max-width: 480px; margin: 0 auto 1.75rem auto; line-height: 1.5;">
                    Explora nuestra cartelera de eventos en vivo y adquiere tus entradas oficiales con código QR y validación instantánea.
                </p>
                <a href="{{ route('web.home') }}" style="background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFF; text-decoration: none; padding: 0.95rem 2.25rem; font-weight: 900; font-size: 1rem; border-radius: 14px; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 6px 20px rgba(255,85,0,0.35); transition: transform 0.2s;">
                    Explorar Cartelera ➔
                </a>
            </div>
        @else
            <!-- TICKETS GRID -->
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 2rem;">
                @foreach($sales as $sale)
                    @php
                        $tData = is_array($sale->tickets_data) ? $sale->tickets_data : json_decode($sale->tickets_data, true);
                        $items = $tData['items'] ?? (is_array($tData) ? $tData : []);
                        $totalTickets = count($items) > 0 ? array_sum(array_column($items, 'quantity')) : max(1, $sale->quantity);

                        // Evaluar si el evento ya pasó
                        $eventDateStr = !empty($sale->event?->event_date) ? (is_string($sale->event->event_date) ? substr($sale->event->event_date, 0, 10) : $sale->event->event_date->format('Y-m-d')) : null;
                        $isPastEvent = false;
                        if ($eventDateStr) {
                            $eventTimeStr = $sale->event?->event_time ?: '23:59:59';
                            $eventDateTime = \Carbon\Carbon::parse($eventDateStr . ' ' . $eventTimeStr);
                            $isPastEvent = $eventDateTime->isPast();
                        }

                        $isUpgraded = $sale->isUpgraded();
                        $pm = strtolower($sale->payment_method ?? '');
                        $isCourtesy = ($pm === 'cortesía' || $pm === 'cortesia' || !empty($tData['is_courtesy']) || (float)$sale->total_amount == 0 || str_contains(strtolower($sale->zone_name ?? ''), 'cortesía') || str_contains(strtolower($sale->zone_name ?? ''), 'cortesia'));

                        $cleanZoneName = function($name, $zoneFallback = '') {
                            $str = !empty($name) ? $name : $zoneFallback;
                            if (preg_match('/^(?:Mejora|Upgrade):\s*(?:.*?(?:➔|->)\s*)?(.+)/iu', $str, $m)) {
                                return trim($m[1]);
                            }
                            return $str;
                        };
                    @endphp

                    <div class="ticket-customer-card" style="background: #FFFFFF; border: 1.5px solid {{ $isPastEvent ? '#E2E8F0' : ($isUpgraded ? '#E0E7FF' : ($isCourtesy ? '#A7F3D0' : ($sale->is_upgrade ? '#818CF8' : '#CBD5E1'))) }}; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); display: flex; flex-direction: column; position: relative; transition: transform 0.2s, box-shadow 0.2s; opacity: {{ ($isPastEvent || $isUpgraded) ? '0.82' : '1' }};">
                        
                        <!-- Top Image Banner -->
                        <div style="position: relative; height: 160px; background: #0F172A; overflow: hidden;">
                            @if($sale->event && $sale->event->banner_image)
                                <img src="{{ $sale->event->banner_image }}" alt="{{ $sale->event->title }}" style="width: 100%; height: 100%; object-fit: cover; {{ ($isPastEvent || $isUpgraded) ? 'filter: grayscale(80%) contrast(90%); opacity: 0.75;' : 'transition: transform 0.3s;' }}">
                            @else
                                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #1E1B4B, #0F172A); display: flex; align-items: center; justify-content: center; color: #FFF; font-size: 2.5rem; {{ ($isPastEvent || $isUpgraded) ? 'filter: grayscale(100%);' : '' }}">🎟️</div>
                            @endif

                            <!-- Gradient Protection Overlay -->
                            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(15,23,42,0.8) 0%, transparent 60%);"></div>
                            
                            @if(function_exists('isSalePresale') && isSalePresale($sale))
                                <span style="position: absolute; top: 12px; left: 12px; background: linear-gradient(135deg, #FF5500, #FF1E3C); color: #FFFFFF; font-size: 0.72rem; font-weight: 900; padding: 0.35rem 0.85rem; border-radius: 20px; box-shadow: 0 4px 12px rgba(255,85,0,0.35); text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid rgba(255,255,255,0.3);">
                                    🔥 PREVENTA
                                </span>
                            @endif

                            <!-- Status Badge -->
                            @if($isUpgraded)
                                <span style="position: absolute; top: 12px; right: 12px; background: linear-gradient(135deg, #6366F1, #4F46E5); color: #FFFFFF; font-size: 0.72rem; font-weight: 900; padding: 0.35rem 0.85rem; border-radius: 20px; box-shadow: 0 4px 12px rgba(99,102,241,0.35); text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #A5B4FC;">
                                    🔄 MEJORADA (INACTIVA)
                                </span>
                            @elseif($isPastEvent)
                                <span style="position: absolute; top: 12px; right: 12px; background: #64748B; color: #FFFFFF; font-size: 0.75rem; font-weight: 900; padding: 0.35rem 0.85rem; border-radius: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.3); text-transform: uppercase; letter-spacing: 0.5px;">
                                    🕒 EVENTO FINALIZADO
                                </span>
                            @elseif($isCourtesy)
                                <span style="position: absolute; top: 12px; right: 12px; background: linear-gradient(135deg, #10B981, #059669); color: #FFFFFF; font-size: 0.75rem; font-weight: 900; padding: 0.35rem 0.85rem; border-radius: 20px; box-shadow: 0 4px 12px rgba(16,185,129,0.35); text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #6EE7B7;">
                                    🎁 CORTESÍA (GRATIS)
                                </span>
                            @elseif($sale->is_upgrade)
                                <span style="position: absolute; top: 12px; right: 12px; background: linear-gradient(135deg, #6366F1, #4F46E5); color: #FFFFFF; font-size: 0.75rem; font-weight: 900; padding: 0.35rem 0.85rem; border-radius: 20px; box-shadow: 0 4px 12px rgba(99,102,241,0.35); text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #A5B4FC;">
                                    ⭐ ENTRADA MEJORADA (VÁLIDA)
                                </span>
                            @else
                                <span style="position: absolute; top: 12px; right: 12px; background: #10B981; color: #FFFFFF; font-size: 0.75rem; font-weight: 900; padding: 0.35rem 0.85rem; border-radius: 20px; box-shadow: 0 4px 12px rgba(16,185,129,0.35); text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #34D399;">
                                    ✓ ENTRADA VÁLIDA
                                </span>
                            @endif

                            <!-- Event Date Tag -->
                            <span style="position: absolute; bottom: 12px; left: 14px; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(8px); color: #FFFFFF; font-size: 0.775rem; font-weight: 800; padding: 0.35rem 0.75rem; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2); display: inline-flex; align-items: center; gap: 0.4rem;">
                                📅 {{ !empty($sale->event?->event_date) ? (is_string($sale->event->event_date) ? substr($sale->event->event_date, 0, 10) : $sale->event->event_date->format('d/m/Y')) : $sale->created_at->format('d/m/Y') }}
                                {{ $sale->event?->event_time ? ' • ' . $sale->event->event_time : '' }}
                            </span>
                        </div>

                        <!-- Card Content -->
                        <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                                <span style="font-size: 0.75rem; font-weight: 900; color: #EA580C; font-family: monospace; letter-spacing: 0.5px;">
                                    OPERACIÓN #{{ $sale->receipt_number }}
                                </span>
                                @if($isCourtesy)
                                    <span style="font-size: 0.85rem; color: #059669; font-weight: 900; background: #ECFDF5; padding: 0.2rem 0.6rem; border-radius: 8px; border: 1px solid #A7F3D0;">
                                        🎁 GRATIS
                                    </span>
                                @elseif($sale->is_upgrade)
                                    <div style="text-align: right;">
                                        <span style="font-size: 0.68rem; color: #6366F1; font-weight: 800; display: block; text-transform: uppercase; letter-spacing: 0.5px;">
                                            DIFERENCIA PAGADA
                                        </span>
                                        <span style="font-size: 0.95rem; color: #4F46E5; font-weight: 900;">
                                            +S/ {{ number_format($sale->total_amount, 2) }}
                                        </span>
                                    </div>
                                @else
                                    <span style="font-size: 0.85rem; color: #059669; font-weight: 900;">
                                        S/ {{ number_format($sale->total_amount, 2) }}
                                    </span>
                                @endif
                            </div>

                            <h3 style="font-size: 1.25rem; font-weight: 900; color: #0F172A; margin: 0 0 0.9rem 0; line-height: 1.25;">
                                {{ $sale->event?->title ?? 'Evento ViveGo' }}
                            </h3>

                            @if($isUpgraded)
                                <div style="background: #EEF2FF; border: 1px solid #C7D2FE; border-radius: 12px; padding: 0.6rem 0.85rem; margin-bottom: 0.9rem; font-size: 0.8rem; color: #4338CA; font-weight: 700; display: flex; align-items: flex-start; gap: 0.45rem;">
                                    <span style="font-size: 1rem;">🔄</span>
                                    <div>
                                        Esta entrada fue canjeada por un upgrade a una zona superior. Tu nuevo boleto vigente con código QR actualizado se encuentra disponible en tu lista.
                                    </div>
                                </div>
                            @elseif($isCourtesy)
                                <div style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 12px; padding: 0.6rem 0.85rem; margin-bottom: 0.9rem; font-size: 0.8rem; color: #065F46; font-weight: 700; display: flex; align-items: flex-start; gap: 0.45rem;">
                                    <span style="font-size: 1rem;">🎁</span>
                                    <div>
                                        Boleto Oficial de Cortesía emitido sin costo. Válido para ingreso directo al recinto del evento.
                                    </div>
                                </div>
                            @elseif($sale->is_upgrade)
                                @php
                                    $origZone = $sale->upgrade_original_zone ?: 'Zona Anterior';
                                    $prevSale = $sale->upgradedFromSale;
                                    $prevAmount = $prevSale ? (float)$prevSale->total_amount : 0;
                                    $fullZonePrice = (float)($sale->original_subtotal > 0 ? $sale->original_subtotal : ($prevAmount + (float)$sale->total_amount));
                                    if ($fullZonePrice <= (float)$sale->total_amount && $prevAmount > 0) {
                                        $fullZonePrice = $prevAmount + (float)$sale->total_amount;
                                    }
                                @endphp
                                <div style="background: linear-gradient(135deg, #F5F3FF 0%, #EEF2FF 100%); border: 1.5px solid #C7D2FE; border-radius: 12px; padding: 0.75rem 0.95rem; margin-bottom: 0.9rem; font-size: 0.82rem; color: #3730A3;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem; flex-wrap: wrap; gap: 0.3rem;">
                                        <strong style="color: #4F46E5; display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.85rem;">
                                            <span>⭐</span> Mejora de Zona Aplicada
                                        </strong>
                                        @if($fullZonePrice > 0)
                                            <span style="background: #E0E7FF; color: #3730A3; padding: 2px 8px; border-radius: 6px; font-weight: 800; font-size: 0.75rem; border: 1px solid #A5B4FC;">
                                                Precio Regular Zona: S/ {{ number_format($fullZonePrice, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div style="font-size: 0.78rem; color: #4338CA; line-height: 1.4;">
                                        Canjeaste tu entrada de <strong>{{ $cleanZoneName($origZone) }}</strong> pagando solo la diferencia de <strong>S/ {{ number_format($sale->total_amount, 2) }}</strong>. Tu zona actual y vigente es <strong>{{ $cleanZoneName($sale->zone_name) }}</strong>.
                                    </div>
                                </div>
                            @endif

                            <!-- Desglose por Sectores / Zonas Adquiridas -->
                            <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 16px; padding: 0.95rem; margin-bottom: 1.25rem;">
                                <span style="font-size: 0.725rem; font-weight: 800; color: #64748B; text-transform: uppercase; display: block; margin-bottom: 0.5rem; letter-spacing: 0.5px;">
                                    Sectores Adquiridos ({{ $totalTickets }} entrada{{ $totalTickets > 1 ? 's' : '' }}):
                                </span>
                                
                                <div style="display: flex; flex-direction: column; gap: 0.45rem;">
                                    @php
                                        $groupedTickets = [];
                                        foreach ($items as $t) {
                                            if (!is_array($t)) continue;
                                            if (empty($t['zone']) && empty($t['zone_name']) && empty($t['name']) && empty($t['ticket_code']) && !isset($t['price'])) continue;

                                            $zoneName = $cleanZoneName($t['zone'] ?? ($t['zone_name'] ?? ($t['name'] ?? '')), $sale->zone_name);
                                            if (empty($zoneName)) {
                                                $zoneName = !empty($sale->zone_name) ? $cleanZoneName($sale->zone_name) : 'Entrada';
                                            }
                                            $groupKey = mb_strtolower($zoneName);

                                            $seats = [];
                                            if (!empty($t['seat'])) {
                                                $seats[] = $t['seat'];
                                            } elseif (!empty($t['seat_label'])) {
                                                $seats[] = $t['seat_label'];
                                            } elseif (!empty($t['seats'])) {
                                                $seats = is_array($t['seats']) ? $t['seats'] : (json_decode($t['seats'], true) ?: [$t['seats']]);
                                            } elseif (preg_match('/\(([^)]+)\)/', $t['zone'] ?? ($t['zone_name'] ?? ''), $zm)) {
                                                $seats[] = $zm[1];
                                            }

                                            $qty = (int)($t['quantity'] ?? 1);
                                            if ($qty <= 0) $qty = 1;

                                            $subtotal = isset($t['subtotal']) ? (float)$t['subtotal'] : ((float)($t['price'] ?? 0) * $qty);
                                            $isPresale = function_exists('isSalePresale') && (isSalePresale($t) || isSalePresale($sale));

                                            if (!isset($groupedTickets[$groupKey])) {
                                                $groupedTickets[$groupKey] = [
                                                    'name' => $zoneName,
                                                    'quantity' => 0,
                                                    'subtotal' => 0.0,
                                                    'seats' => [],
                                                    'is_presale' => $isPresale,
                                                ];
                                            }

                                            $groupedTickets[$groupKey]['quantity'] += $qty;
                                            $groupedTickets[$groupKey]['subtotal'] += $subtotal;
                                            if ($isPresale) {
                                                $groupedTickets[$groupKey]['is_presale'] = true;
                                            }
                                            foreach ((array)$seats as $s) {
                                                if (!empty($s)) {
                                                    $groupedTickets[$groupKey]['seats'][] = $s;
                                                }
                                            }
                                        }

                                        if (empty($groupedTickets)) {
                                            $zoneName = $cleanZoneName($sale->zone_name);
                                            $groupKey = mb_strtolower($zoneName);
                                            $groupedTickets[$groupKey] = [
                                                'name' => $zoneName,
                                                'quantity' => (int)($sale->quantity ?? 1),
                                                'subtotal' => (float)$sale->total_amount,
                                                'seats' => [],
                                                'is_presale' => function_exists('isSalePresale') && isSalePresale($sale),
                                            ];
                                        }

                                        foreach ($groupedTickets as $gKey => &$group) {
                                            if (empty($group['seats']) && $sale->eventTickets && $sale->eventTickets->count() > 0) {
                                                foreach ($sale->eventTickets as $et) {
                                                    $etZoneClean = $cleanZoneName($et->zone_name);
                                                    if (mb_strtolower($etZoneClean) === $gKey || str_contains(mb_strtolower($et->zone_name), $gKey)) {
                                                        if (preg_match('/\(([^)]+)\)/', $et->zone_name, $sm)) {
                                                            $group['seats'][] = $sm[1];
                                                        } elseif (!empty($et->seat)) {
                                                            $group['seats'][] = $et->seat;
                                                        }
                                                    }
                                                }
                                            }
                                            $group['formatted_seats'] = array_values(array_unique(array_filter(array_map('formatShortSeatCode', (array)$group['seats']))));
                                        }
                                        unset($group);
                                    @endphp
                                    @if(count($groupedTickets) > 0)
                                        @foreach($groupedTickets as $t)
                                            <div style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 0.5rem 0.75rem;">
                                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem;">
                                                    <span style="font-weight: 800; color: #0F172A;">
                                                        <span style="background: rgba(255,85,0,0.12); color: #FF5500; padding: 2px 6px; border-radius: 6px; font-weight: 900;">{{ $t['quantity'] }}x</span>
                                                        {{ $t['name'] }}
                                                        @if(!empty($t['is_presale']))
                                                            <span style="background: rgba(255,85,0,0.12); color: #FF5500; border: 1px solid rgba(255,85,0,0.3); font-size: 0.65rem; font-weight: 900; padding: 1px 5px; border-radius: 4px; margin-left: 0.3rem;">🔥 PREVENTA</span>
                                                        @endif
                                                    </span>
                                                    <span style="font-weight: 800; color: #059669; font-size: 0.85rem;">
                                                        @if($isCourtesy)
                                                            Gratis
                                                        @elseif($sale->is_upgrade)
                                                            <span style="font-size: 0.78rem; color: #6366F1; font-weight: 800;">+S/ {{ number_format($t['subtotal'], 2) }}</span>
                                                        @else
                                                            S/ {{ number_format($t['subtotal'], 2) }}
                                                        @endif
                                                    </span>
                                                </div>
                                                @if(!empty($t['formatted_seats']))
                                                    <div style="display: flex; flex-wrap: wrap; gap: 0.3rem; align-items: center; margin-top: 0.35rem; padding-top: 0.35rem; border-top: 1px dashed #F1F5F9;">
                                                        <span style="font-size: 0.72rem; font-weight: 800; color: #059669;">🪑 Butacas:</span>
                                                        @foreach($t['formatted_seats'] as $fs)
                                                            <span style="background: rgba(16, 185, 129, 0.12); color: #047857; border: 1.5px solid rgba(16, 185, 129, 0.3); font-size: 0.72rem; font-weight: 900; padding: 1px 6px; border-radius: 5px;">
                                                                {{ $fs }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        @php
                                            $sDisplayName = $cleanZoneName($sale->zone_name);
                                        @endphp
                                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem; background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 10px; padding: 0.45rem 0.75rem;">
                                            <span style="font-weight: 800; color: #0F172A;">
                                                <span style="background: rgba(255,85,0,0.12); color: #FF5500; padding: 2px 6px; border-radius: 6px; font-weight: 900;">{{ $sale->quantity }}x</span>
                                                {{ $sDisplayName }}
                                            </span>
                                            <span style="font-weight: 800; color: #059669; font-size: 0.85rem;">
                                                @if($isCourtesy)
                                                    Gratis
                                                @elseif($sale->is_upgrade)
                                                    <span style="font-size: 0.78rem; color: #6366F1; font-weight: 800;">+S/ {{ number_format($sale->total_amount, 2) }}</span>
                                                @else
                                                    S/ {{ number_format($sale->total_amount, 2) }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div style="margin-top: 0.75rem; padding-top: 0.65rem; border-top: 1px dashed #CBD5E1; font-size: 0.8rem; color: #64748B; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 0.3rem;">
                                    <span>📍 {{ $sale->event?->venue_name ?? 'Recinto Oficial' }}</span>
                                    <span>👤 {{ $sale->buyer_name }}</span>
                                </div>
                            </div>

                            <!-- Botones de Acción: Generar Boleto & Mejorar Entrada -->
                            <div style="margin-top: auto; display: flex; flex-direction: column; gap: 0.6rem;">
                                @if($isUpgraded)
                                    <button type="button" disabled style="width: 100%; border: 1.5px dashed #CBD5E1; background: #F1F5F9; color: #64748B; padding: 0.85rem 1rem; font-size: 0.875rem; font-weight: 800; border-radius: 14px; cursor: not-allowed; display: inline-flex; align-items: center; justify-content: center; gap: 0.45rem;">
                                        <span>🔒 Entrada Canjeada por Upgrade</span>
                                    </button>
                                @elseif($isPastEvent)
                                    <button type="button" disabled style="width: 100%; background: #F1F5F9; color: #94A3B8; border: 1.5px solid #E2E8F0; padding: 0.9rem 1rem; font-size: 0.9rem; font-weight: 800; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; cursor: not-allowed;">
                                        <span>🔒 Entrada Caducada</span>
                                    </button>
                                @else
                                    <button type="button" class="btn-generar-boleto" onclick="downloadPosSalePdf({{ $sale->id }})" data-sale-id="{{ $sale->id }}" data-sale-payload="{{ base64_encode(json_encode($sale)) }}" style="width: 100%; border: none; cursor: pointer; box-sizing: border-box; background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFFFFF; text-align: center; text-decoration: none; padding: 0.85rem 1rem; font-size: 0.95rem; font-weight: 900; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 6px 18px rgba(255, 85, 0, 0.35); transition: transform 0.15s, box-shadow 0.15s;">
                                        <span>🎟️ Generar Boleto</span>
                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                    </button>

                                    @php
                                        $eventZones = is_array($sale->event?->zones) ? $sale->event->zones : (json_decode($sale->event?->zones ?? '[]', true) ?: []);
                                        $currentUnitPrice = (float)($sale->unit_price ?? 0);
                                        if ($currentUnitPrice <= 0 && (float)$sale->total_amount > 0 && (int)$sale->quantity > 0) {
                                            $currentUnitPrice = round((float)$sale->total_amount / (int)$sale->quantity, 2);
                                        }

                                        $hasHigherTierZone = false;
                                        foreach ($eventZones as $ez) {
                                            $zPrice = (float)($ez['price'] ?? 0);
                                            $zCap = (int)($ez['capacity'] ?? 0);
                                            // La zona debe ser de mayor precio que el boleto actual y tener aforo disponible
                                            if ($zPrice > $currentUnitPrice && $zCap > 0) {
                                                $hasHigherTierZone = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if(!$isCourtesy && $hasHigherTierZone)
                                        <!-- Botón Mejorar Entrada (Habilitado únicamente si existe una zona de mayor precio) -->
                                        <button type="button" class="btn-mejorar-boleto" onclick="openTicketUpgradeModal({{ $sale->id }})" style="width: 100%; border: 1.5px solid #6366F1; cursor: pointer; box-sizing: border-box; background: linear-gradient(135deg, #F5F3FF 0%, #EEF2FF 100%); color: #4F46E5; text-align: center; padding: 0.7rem 1rem; border-radius: 14px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.15rem; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.12); transition: all 0.2s;">
                                            <div style="display: flex; align-items: center; gap: 0.45rem;">
                                                <span style="font-size: 0.92rem; font-weight: 900;">⭐ Mejorar mi Entrada</span>
                                                <span style="font-size: 0.65rem; background: #6366F1; color: #FFFFFF; padding: 0.15rem 0.45rem; border-radius: 6px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">UPGRADE</span>
                                            </div>
                                            <span style="font-size: 0.74rem; color: #6366F1; font-weight: 800; opacity: 0.95;">⚡ Solo paga la diferencia</span>
                                        </button>
                                    @endif
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DE MEJORA DE ENTRADA (TICKET UPGRADE) -->
<!-- ========================================== -->
<div id="ticketUpgradeModal" style="display: none; position: fixed; inset: 0; z-index: 999999; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); overflow-y: auto; padding: 1.5rem 1rem; align-items: center; justify-content: center;">
    <div style="background: #FFFFFF; border-radius: 28px; width: 100%; max-width: 680px; box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.35); overflow: hidden; position: relative; border: 1.5px solid #E2E8F0; margin: auto; animation: modalSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
        
        <!-- Header del Modal con Gradiente Premium -->
        <div style="background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 60%, #312E81 100%); padding: 1.75rem 2rem; color: #FFFFFF; position: relative;">
            <button type="button" onclick="closeTicketUpgradeModal()" style="position: absolute; top: 1.25rem; right: 1.25rem; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); color: #FFFFFF; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; cursor: pointer; transition: background 0.2s;">
                ✕
            </button>
            <div style="display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(99,102,241,0.25); border: 1px solid rgba(165,180,252,0.4); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #A5B4FC; margin-bottom: 0.6rem;">
                <span>⭐</span> VIVEGO UPGRADE PASS
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 900; margin: 0 0 0.25rem 0; letter-spacing: -0.5px; color: #FFFFFF;">
                Mejorar mi Entrada
            </h2>
            <p style="color: #94A3B8; font-size: 0.875rem; margin: 0; line-height: 1.4;">
                Sube de zona y vive la mejor experiencia pagando <strong style="color: #38BDF8;">únicamente la diferencia</strong>.
            </p>
        </div>

        <!-- Cuerpo del Modal -->
        <div style="padding: 1.75rem 2rem; max-height: calc(85vh - 200px); overflow-y: auto;">
            
            <!-- Loading Spinner -->
            <div id="upgradeModalLoading" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 3rem 1rem; gap: 1rem;">
                <div style="width: 44px; height: 44px; border: 4px solid #E2E8F0; border-top-color: #6366F1; border-radius: 50%; animation: spinUpgrade 0.8s linear infinite;"></div>
                <span style="font-size: 0.95rem; font-weight: 700; color: #64748B;">Consultando aforo y zonas disponibles en vivo...</span>
            </div>

            <!-- Error State -->
            <div id="upgradeModalError" style="display: none; background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #991B1B; padding: 1.25rem; border-radius: 16px; font-weight: 700; text-align: center;">
                <span style="font-size: 1.8rem; display: block; margin-bottom: 0.5rem;">⚠️</span>
                <div id="upgradeModalErrorMessage" style="font-size: 0.95rem; margin-bottom: 1rem;"></div>
                <button type="button" onclick="closeTicketUpgradeModal()" style="background: #DC2626; color: #FFFFFF; border: none; padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 800; cursor: pointer;">Cerrar</button>
            </div>

            <!-- Content Area (Rendered when loaded) -->
            <div id="upgradeModalContent" style="display: none;">
                
                <!-- Ticket Actual Summary Card -->
                <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
                    <div>
                        <span style="font-size: 0.72rem; font-weight: 800; color: #64748B; text-transform: uppercase; display: block; letter-spacing: 0.5px;">Tu Entrada Actual:</span>
                        <div style="font-size: 1.1rem; font-weight: 900; color: #0F172A; display: flex; align-items: center; gap: 0.5rem;">
                            <span id="uCurrentQuantityBadge" style="background: rgba(255,85,0,0.12); color: #FF5500; padding: 2px 8px; border-radius: 8px; font-size: 0.9rem;">1x</span>
                            <span id="uCurrentZoneName">Zona General</span>
                        </div>
                        <span id="uCurrentEventTitle" style="font-size: 0.8rem; color: #64748B; font-weight: 600; display: block; margin-top: 2px;"></span>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 0.72rem; font-weight: 800; color: #64748B; text-transform: uppercase; display: block;">Monto Abonado:</span>
                        <span id="uCurrentTotalPaid" style="font-size: 1.15rem; font-weight: 900; color: #059669;">S/ 0.00</span>
                    </div>
                </div>

                <div style="margin-bottom: 0.85rem; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.82rem; font-weight: 900; color: #0F172A; text-transform: uppercase; letter-spacing: 0.5px;">
                        Selecciona la Zona a la que deseas mejorar:
                    </span>
                    <span style="font-size: 0.75rem; color: #64748B; font-weight: 700;">Aforo en Tiempo Real ⚡</span>
                </div>

                <!-- Lista Dinámica de Zonas Disponibles -->
                <div id="uZonesList" style="display: flex; flex-direction: column; gap: 0.85rem;">
                    <!-- Inserted dynamically via JavaScript -->
                </div>

                <!-- Resumen de Pago de Diferencia -->
                <div id="uUpgradeSummaryBox" style="display: none; margin-top: 1.5rem; background: linear-gradient(135deg, #FAF5FF 0%, #EEF2FF 100%); border: 2px solid #818CF8; border-radius: 20px; padding: 1.25rem 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem;">
                        <span style="font-size: 0.9rem; font-weight: 800; color: #4338CA;">Nueva Zona Elegida:</span>
                        <strong id="uSummarySelectedZone" style="font-size: 1.05rem; font-weight: 900; color: #312E81;">-</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: #64748B; margin-bottom: 0.4rem;">
                        <span>Diferencia por entrada:</span>
                        <strong id="uSummaryUnitDiff" style="color: #0F172A;">S/ 0.00</strong>
                    </div>
                    <div style="border-top: 1.5px dashed #CBD5E1; margin: 0.6rem 0; padding-top: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 1rem; font-weight: 900; color: #0F172A; display: block;">Total a Pagar (Solo Diferencia):</span>
                            <span style="font-size: 0.75rem; color: #6366F1; font-weight: 800;">🔒 Se anula el boleto previo y recibes el nuevo QR</span>
                        </div>
                        <span id="uSummaryTotalDiff" style="font-size: 1.6rem; font-weight: 900; color: #059669;">S/ 0.00</span>
                    </div>

                    <!-- Botón CTA Ir al Checkout -->
                    <button type="button" id="btnProceedUpgradeCheckout" onclick="proceedToUpgradeCheckout()" style="width: 100%; border: none; cursor: pointer; background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%); color: #FFFFFF; font-size: 1rem; font-weight: 900; padding: 0.95rem 1.5rem; border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35); margin-top: 0.75rem; transition: transform 0.15s, box-shadow 0.15s;">
                        <span>Continuar al Checkout y Pagar Diferencia</span>
                        <span>➔</span>
                    </button>
                </div>

            </div>

        </div>

    </div>
</div>

<style>
    .ticket-customer-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1) !important;
    }
    .ticket-customer-card:hover img {
        transform: scale(1.03);
    }
    .btn-generar-boleto:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(255, 85, 0, 0.45) !important;
    }
    .btn-mejorar-boleto:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.25) !important;
        background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%) !important;
    }
    .zone-upgrade-card {
        transition: all 0.2s ease;
    }
    .zone-upgrade-card.selectable:hover {
        border-color: #6366F1 !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(99, 102, 241, 0.12) !important;
    }
    .zone-upgrade-card.selected {
        border-color: #6366F1 !important;
        background: #FAF5FF !important;
        box-shadow: 0 0 0 2px #6366F1 !important;
    }
    @keyframes spinUpgrade {
        to { transform: rotate(360deg); }
    }
    @keyframes modalSlideUp {
        from { opacity: 0; transform: translateY(20px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
</style>
@endsection

@push('scripts')
    <script>
        window.posSalesMap = window.posSalesMap || {};
        Object.assign(window.posSalesMap, @json($sales->keyBy('id')));
    </script>
    @include('web.customer.partials.ticket_generator_js')

    <script>
        let currentUpgradeData = null;
        let selectedUpgradeZone = null;

        function openTicketUpgradeModal(saleId) {
            const modal = document.getElementById('ticketUpgradeModal');
            const loading = document.getElementById('upgradeModalLoading');
            const errorBox = document.getElementById('upgradeModalError');
            const content = document.getElementById('upgradeModalContent');
            const summaryBox = document.getElementById('uUpgradeSummaryBox');

            if (!modal) return;

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            loading.style.display = 'flex';
            errorBox.style.display = 'none';
            content.style.display = 'none';
            summaryBox.style.display = 'none';
            selectedUpgradeZone = null;

            fetch(`/mi-cuenta/boleto/${saleId}/opciones-mejora`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                loading.style.display = 'none';
                if (!data.success) {
                    errorBox.style.display = 'block';
                    document.getElementById('upgradeModalErrorMessage').textContent = data.message || 'No se pudieron consultar las opciones de mejora.';
                    return;
                }

                currentUpgradeData = data;
                renderUpgradeModal(data);
                content.style.display = 'block';
            })
            .catch(err => {
                console.error('Error cargando opciones de mejora:', err);
                loading.style.display = 'none';
                errorBox.style.display = 'block';
                document.getElementById('upgradeModalErrorMessage').textContent = 'Ocurrió un error de conexión al consultar las opciones. Por favor intenta nuevamente.';
            });
        }

        function closeTicketUpgradeModal() {
            const modal = document.getElementById('ticketUpgradeModal');
            if (modal) modal.style.display = 'none';
            document.body.style.overflow = '';
            currentUpgradeData = null;
            selectedUpgradeZone = null;
        }

        function renderUpgradeModal(data) {
            const sale = data.sale;
            const event = data.event;
            const zones = data.zones || [];

            document.getElementById('uCurrentQuantityBadge').textContent = `${sale.quantity}x`;
            document.getElementById('uCurrentZoneName').textContent = sale.zone_name;
            document.getElementById('uCurrentEventTitle').textContent = `📅 ${event.title} • ${event.date_formatted}`;
            document.getElementById('uCurrentTotalPaid').textContent = `S/ ${parseFloat(sale.total_amount).toFixed(2)}`;

            const zonesList = document.getElementById('uZonesList');
            zonesList.innerHTML = '';

            if (zones.length === 0) {
                zonesList.innerHTML = `<div style="text-align: center; padding: 2rem; color: #64748B; font-weight: 700;">No hay zonas registradas para este evento.</div>`;
                return;
            }

            zones.forEach((z, index) => {
                const isAvailable = z.available_for_upgrade;
                const card = document.createElement('div');
                card.className = `zone-upgrade-card ${isAvailable ? 'selectable' : 'disabled'}`;
                card.id = `zoneCard_${index}`;
                
                let cardStyle = "border-radius: 18px; padding: 1.1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; border: 1.5px solid #E2E8F0;";
                if (z.is_current) {
                    cardStyle += " background: #F8FAFC; opacity: 0.85;";
                } else if (!isAvailable) {
                    cardStyle += " background: #F1F5F9; opacity: 0.7; cursor: not-allowed;";
                } else {
                    cardStyle += " background: #FFFFFF; cursor: pointer;";
                }
                card.style.cssText = cardStyle;

                if (isAvailable) {
                    card.onclick = () => selectUpgradeZone(z, index);
                }

                let badgeHtml = '';
                if (z.is_current) {
                    badgeHtml = `<span style="background: #E2E8F0; color: #475569; font-size: 0.72rem; font-weight: 800; padding: 0.25rem 0.6rem; border-radius: 8px;">📍 ZONA ACTUAL</span>`;
                } else if (z.badge_status === 'sold_out') {
                    badgeHtml = `<span style="background: #FEE2E2; color: #DC2626; font-size: 0.72rem; font-weight: 800; padding: 0.25rem 0.6rem; border-radius: 8px;">🚫 AGOTADO / SIN AFORO</span>`;
                } else if (z.badge_status === 'lower_tier') {
                    badgeHtml = `<span style="background: #E2E8F0; color: #64748B; font-size: 0.72rem; font-weight: 800; padding: 0.25rem 0.6rem; border-radius: 8px;">ℹ️ PRECIO MENOR O IGUAL</span>`;
                } else {
                    badgeHtml = `<span style="background: #DCFCE7; color: #15803D; font-size: 0.72rem; font-weight: 900; padding: 0.25rem 0.6rem; border-radius: 8px;">⚡ ${z.remaining} CUPOS DISPONIBLES</span>`;
                }

                card.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 1rem; min-width: 0;">
                        ${isAvailable ? `
                            <input type="radio" name="selected_upgrade_zone_radio" id="radioZone_${index}" style="accent-color: #6366F1; width: 18px; height: 18px; cursor: pointer;">
                        ` : `
                            <span style="font-size: 1.1rem; color: #94A3B8;">🔒</span>
                        `}
                        <div style="min-width: 0;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                                <strong style="font-size: 1.05rem; color: #0F172A;">${z.name}</strong>
                                ${badgeHtml}
                            </div>
                            <span style="font-size: 0.8rem; color: #64748B; display: block; margin-top: 2px;">
                                Precio Regular: <strong>${z.price_formatted}</strong> c/u
                            </span>
                        </div>
                    </div>

                    <div style="text-align: right; flex-shrink: 0;">
                        ${isAvailable ? `
                            <span style="font-size: 0.7rem; font-weight: 800; color: #6366F1; text-transform: uppercase; display: block;">Solo Paga Diferencia:</span>
                            <span style="font-size: 1.15rem; font-weight: 900; color: #059669; display: block;">
                                + ${z.unit_difference_formatted} <span style="font-size: 0.75rem; color: #64748B; font-weight: 600;">c/u</span>
                            </span>
                            <span style="font-size: 0.72rem; color: #475569; font-weight: 700;">
                                Total (${sale.quantity}x): ${z.total_difference_formatted}
                            </span>
                        ` : `
                            <span style="font-size: 0.8rem; color: #94A3B8; font-weight: 700;">No disponible</span>
                        `}
                    </div>
                `;

                zonesList.appendChild(card);
            });
        }

        function selectUpgradeZone(zone, index) {
            selectedUpgradeZone = zone;

            document.querySelectorAll('.zone-upgrade-card').forEach(c => c.classList.remove('selected'));
            const selectedCard = document.getElementById(`zoneCard_${index}`);
            if (selectedCard) selectedCard.classList.add('selected');

            const radio = document.getElementById(`radioZone_${index}`);
            if (radio) radio.checked = true;

            const summaryBox = document.getElementById('uUpgradeSummaryBox');
            summaryBox.style.display = 'block';

            document.getElementById('uSummarySelectedZone').textContent = zone.name;
            document.getElementById('uSummaryUnitDiff').textContent = `${zone.unit_difference_formatted} c/u (${currentUpgradeData.sale.quantity} entrada(s))`;
            document.getElementById('uSummaryTotalDiff').textContent = zone.total_difference_formatted;
        }

        function proceedToUpgradeCheckout() {
            if (!currentUpgradeData || !selectedUpgradeZone) {
                alert('Por favor selecciona una zona superior para continuar con la mejora.');
                return;
            }

            const saleId = currentUpgradeData.sale.id;
            const zoneName = selectedUpgradeZone.name;
            const checkoutUrl = `{{ route('web.checkout') }}?upgrade_sale_id=${saleId}&upgrade_zone=${encodeURIComponent(zoneName)}`;

            window.location.href = checkoutUrl;
        }
    </script>
@endpush

