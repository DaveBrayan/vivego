<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    public function reserve(): JsonResponse
    {
        return response()->json(['message' => 'Reserva iniciada exitosamente.']);
    }

    public function process(string $order): JsonResponse
    {
        return response()->json(['message' => 'Pago procesado exitosamente.', 'order' => $order]);
    }
}
