@extends('layouts.app')

@section('title', 'Ingresar a mi Cuenta | ViveGo Perú')

@section('content')
<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 3rem 1.25rem 5rem 1.25rem; background: #F8FAFC;">
    <div style="width: 100%; max-width: 460px;">
        
        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 24px; padding: 2.5rem 2rem; box-shadow: 0 20px 40px -10px rgba(0,0,0,0.06); position: relative; overflow: hidden;">
            
            <!-- Gradient Bar -->
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #FF5500, #FF0055, #00D2C4);"></div>

            <!-- Header -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="width: 60px; height: 60px; border-radius: 16px; background: #FFF7ED; border: 2px solid #FFEDD5; color: #FF5500; font-size: 1.8rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto;">
                    🔑
                </div>
                <h1 style="font-size: 1.6rem; font-weight: 900; color: #0F172A; margin: 0 0 0.4rem 0;">Portal de Clientes</h1>
                <p style="font-size: 0.9rem; color: #64748B; margin: 0;">Ingresa con tu correo y contraseña para ver tus entradas activas y descargar tus recibos oficiales.</p>
            </div>

            <!-- Error Box -->
            <div id="loginErrorAlert" style="display: none; background: #FEF2F2; border: 1.5px solid #FCA5A5; color: #DC2626; padding: 0.85rem 1rem; border-radius: 12px; font-size: 0.875rem; font-weight: 700; margin-bottom: 1.25rem;"></div>

            <!-- Form -->
            <form onsubmit="handlePortalLogin(event)">
                <div style="margin-bottom: 1.25rem;">
                    <label style="font-size: 0.85rem; font-weight: 800; color: #0F172A; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.4rem;">
                        Correo Electrónico
                    </label>
                    <input type="email" id="customerEmail" required placeholder="tu.correo@ejemplo.com" style="width: 100%; padding: 0.85rem 1rem; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 0.95rem; color: #0F172A; outline: none; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="font-size: 0.85rem; font-weight: 800; color: #0F172A; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 0.4rem;">
                        Contraseña (o Contraseña Temporal)
                    </label>
                    <input type="password" id="customerPassword" required placeholder="••••••••" style="width: 100%; padding: 0.85rem 1rem; border: 1.5px solid #CBD5E1; border-radius: 12px; font-size: 0.95rem; color: #0F172A; outline: none; box-sizing: border-box;">
                </div>

                <button type="submit" id="btnLoginSubmit" style="width: 100%; background: linear-gradient(135deg, #FF5500, #E64A00); color: #FFFFFF; border: none; padding: 0.95rem 1.5rem; font-size: 1rem; font-weight: 900; border-radius: 14px; cursor: pointer; box-shadow: 0 6px 18px rgba(255,85,0,0.35); transition: transform 0.15s;">
                    Ingresar a Mis Boletos
                </button>
            </form>

            <div style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1.5px solid #F1F5F9; text-align: center;">
                <p style="font-size: 0.85rem; color: #64748B; margin: 0;">
                    ¿Compraste entradas y no tienes contraseña? Revisa el correo de confirmación de tu compra donde te enviamos tu contraseña temporal.
                </p>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
    function handlePortalLogin(e) {
        e.preventDefault();
        const email = document.getElementById('customerEmail').value.trim();
        const password = document.getElementById('customerPassword').value;
        const errBox = document.getElementById('loginErrorAlert');
        const btn = document.getElementById('btnLoginSubmit');

        btn.disabled = true;
        btn.textContent = 'Verificando credenciales...';
        errBox.style.display = 'none';

        fetch("{{ route('web.customer.login') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email, password: password })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.textContent = 'Ingresar a Mis Boletos';
            if (data.success) {
                window.location.href = "{{ route('customer.my_tickets') }}";
            } else {
                errBox.textContent = data.message || 'Correo o contraseña incorrectos.';
                errBox.style.display = 'block';
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.textContent = 'Ingresar a Mis Boletos';
            errBox.textContent = 'Error de conexión. Intenta de nuevo.';
            errBox.style.display = 'block';
        });
    }
</script>
@endpush
@endsection
