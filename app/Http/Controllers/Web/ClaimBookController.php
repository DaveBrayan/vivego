<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Company;
use App\Models\Event;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClaimBookController extends Controller
{
    /**
     * Muestra el formulario virtual del Libro de Reclamaciones
     */
    public function index(): View
    {
        $settings = Setting::first();
        $company = Company::first();
        $events = Event::whereNotIn('status', ['Borrador', 'Oculto', 'draft', 'unlisted', 'no_marketplace', 'No Marketplace', 'Privado', 'Inactivo'])
            ->orderBy('id', 'desc')
            ->get();

        return view('web.claim_book', compact('settings', 'company', 'events'));
    }

    /**
     * Procesa y registra la Hoja de Reclamación Virtual
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'person_type' => 'required|in:natural,juridica',
            'full_name' => 'required|string|max:255',
            'document_type' => 'required|string|max:30',
            'document_number' => 'required|string|max:30',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:500',
            'department' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'is_minor' => 'nullable|boolean',
            'parent_name' => 'required_if:is_minor,1|nullable|string|max:255',
            'parent_document_type' => 'nullable|string|max:30',
            'parent_document_number' => 'required_if:is_minor,1|nullable|string|max:30',
            'parent_email' => 'nullable|email|max:150',
            'parent_phone' => 'nullable|string|max:30',
            'contracted_good_type' => 'required|in:PRODUCTO,SERVICIO',
            'claimed_amount' => 'nullable|numeric|min:0',
            'event_id' => 'nullable|exists:events,id',
            'order_code' => 'nullable|string|max:100',
            'good_description' => 'required|string|max:2000',
            'claim_type' => 'required|in:RECLAMO,QUEJA',
            'claim_detail' => 'required|string|max:4000',
            'consumer_request' => 'required|string|max:2000',
            'terms_accepted' => 'required',
        ], [
            'person_type.required' => 'Selecciona el tipo de persona.',
            'full_name.required' => 'Ingresa tus nombres y apellidos o razón social.',
            'document_number.required' => 'Ingresa el número de tu documento de identidad.',
            'email.required' => 'Ingresa tu correo electrónico de contacto.',
            'phone.required' => 'Ingresa un número telefónico de contacto.',
            'address.required' => 'Ingresa tu domicilio o dirección.',
            'good_description.required' => 'Describe el producto, evento o servicio contratado.',
            'claim_type.required' => 'Selecciona si se trata de un Reclamo o una Queja.',
            'claim_detail.required' => 'Describe con claridad el detalle de los hechos.',
            'consumer_request.required' => 'Indica tu pedido o solución esperada.',
            'terms_accepted.required' => 'Debes aceptar los términos y declarar la veracidad de los datos.',
        ]);

        $claimNumber = Claim::generateNextClaimNumber();

        $claim = new Claim();
        $claim->claim_number = $claimNumber;
        $claim->person_type = $validated['person_type'];
        $claim->full_name = trim($validated['full_name']);
        $claim->document_type = $validated['document_type'];
        $claim->document_number = trim($validated['document_number']);
        $claim->email = strtolower(trim($validated['email']));
        $claim->phone = trim($validated['phone']);
        $claim->address = trim($validated['address']);
        $claim->department = $validated['department'] ?? 'LIMA';
        $claim->province = $validated['province'] ?? 'LIMA';
        $claim->district = $validated['district'] ?? null;
        
        $claim->is_minor = !empty($request->input('is_minor'));
        if ($claim->is_minor) {
            $claim->parent_name = trim($request->input('parent_name', ''));
            $claim->parent_document_type = $request->input('parent_document_type', 'DNI');
            $claim->parent_document_number = trim($request->input('parent_document_number', ''));
            $claim->parent_email = trim($request->input('parent_email', ''));
            $claim->parent_phone = trim($request->input('parent_phone', ''));
        }

        $claim->contracted_good_type = $validated['contracted_good_type'];
        $claim->claimed_amount = !empty($validated['claimed_amount']) ? (float)$validated['claimed_amount'] : 0.00;
        $claim->event_id = !empty($validated['event_id']) ? (int)$validated['event_id'] : null;
        $claim->order_code = !empty($validated['order_code']) ? trim($validated['order_code']) : null;
        $claim->good_description = trim($validated['good_description']);
        $claim->claim_type = $validated['claim_type'];
        $claim->claim_detail = trim($validated['claim_detail']);
        $claim->consumer_request = trim($validated['consumer_request']);
        $claim->status = 'Pendiente';
        
        $claim->ip_address = $request->ip();
        $claim->user_agent = $request->userAgent();

        $claim->save();

        $confirmationUrl = route('web.claim_book.confirmation', ['code' => $claim->claim_number]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '¡Tu Hoja de Reclamación ha sido registrada exitosamente!',
                'claim_number' => $claim->claim_number,
                'redirect_url' => $confirmationUrl,
            ]);
        }

        return redirect()->to($confirmationUrl)->with('success', '¡Hoja de Reclamación registrada exitosamente!');
    }

    /**
     * Muestra la constancia oficial / comprobante de la Hoja de Reclamación
     */
    public function confirmation(string $code): View
    {
        $claim = Claim::with(['event'])->where('claim_number', $code)->firstOrFail();
        $settings = Setting::first();
        $company = Company::first();

        return view('web.claim_confirmation', compact('claim', 'settings', 'company'));
    }
}
