<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Vive Go Dashboard</title>
    <meta name="description" content="Acceso seguro al panel de administración y control de eventos de Vive Go.">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-bg: #0B0E14;
            --color-card: rgba(20, 24, 36, 0.75);
            --color-card-border: rgba(255, 255, 255, 0.12);
            --color-primary: #FF5500;
            --color-primary-hover: #E04B00;
            --color-primary-glow: rgba(255, 85, 0, 0.35);
            --color-text-main: #FFFFFF;
            --color-text-muted: #94A3B8;
            --color-input-bg: rgba(15, 23, 42, 0.6);
            --color-input-border: rgba(255, 255, 255, 0.15);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--color-bg);
            color: var(--color-text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow-x: hidden;
        }

        /* Glow Elements */
        .bg-glow-top {
            position: absolute;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 85, 0, 0.22) 0%, rgba(11, 14, 20, 0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        .bg-glow-bottom {
            position: absolute;
            bottom: -200px;
            right: -100px;
            width: 600px;
            height: 500px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.15) 0%, rgba(11, 14, 20, 0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Top Header Navigation */
        .login-header {
            position: relative;
            z-index: 10;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        .login-brand-logo img {
            height: 42px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 0 12px rgba(255, 85, 0, 0.4));
        }

        .btn-back-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 700;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.25s ease;
        }

        .btn-back-home:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateX(-3px);
        }

        /* Main Form Container */
        .login-main {
            position: relative;
            z-index: 10;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--color-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--color-card-border);
            border-radius: 24px;
            padding: 2.75rem 2.25rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 40px rgba(255, 85, 0, 0.12);
            animation: cardAppear 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .login-title {
            font-size: 1.75rem;
            font-weight: 900;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #FFFFFF 0%, #CBD5E1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-subtitle {
            font-size: 0.9rem;
            color: var(--color-text-muted);
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        /* Alerts */
        .alert {
            padding: 0.85rem 1rem;
            border-radius: 14px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            line-height: 1.4;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #FCA5A5;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6EE7B7;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.12);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #FDE68A;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 1.35rem;
        }

        .form-label {
            display: block;
            font-size: 0.825rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #CBD5E1;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: #64748B;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.85rem;
            background: var(--color-input-bg);
            border: 1.5px solid var(--color-input-border);
            border-radius: 14px;
            color: #FFFFFF;
            font-size: 0.95rem;
            font-weight: 600;
            outline: none;
            transition: all 0.25s ease;
        }

        .form-control::placeholder {
            color: #64748B;
            font-weight: 500;
        }

        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px var(--color-primary-glow);
            background: rgba(15, 23, 42, 0.85);
        }

        .form-control:focus + .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: var(--color-primary);
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            background: none;
            border: none;
            color: #64748B;
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }

        .toggle-password:hover {
            color: #FFFFFF;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            font-size: 0.85rem;
        }

        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-text-muted);
            cursor: pointer;
            user-select: none;
        }

        .remember-checkbox input {
            accent-color: var(--color-primary);
            width: 16px;
            height: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 700;
            transition: opacity 0.2s ease;
        }

        .forgot-link:hover {
            opacity: 0.85;
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-login-submit {
            width: 100%;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #FF5500 0%, #E04B00 100%);
            border: none;
            border-radius: 14px;
            color: #FFFFFF;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            box-shadow: 0 10px 25px rgba(255, 85, 0, 0.4);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 85, 0, 0.5);
            background: linear-gradient(135deg, #FF661A 0%, #EE5200 100%);
        }

        .btn-login-submit:active {
            transform: translateY(0);
        }

        /* Footer */
        .login-footer {
            position: relative;
            z-index: 10;
            padding: 1.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: #64748B;
        }
    </style>
</head>
<body>
    <div class="bg-glow-top"></div>
    <div class="bg-glow-bottom"></div>

    <!-- Header Navigation -->
    <header class="login-header">
        <a href="{{ route('web.home') }}" class="login-brand-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Vive Go">
        </a>
        <a href="{{ route('web.home') }}" class="btn-back-home">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Volver al Inicio
        </a>
    </header>

    <!-- Main Login Card -->
    <main class="login-main">
        <div class="login-card">
            <h1 class="login-title">Iniciar Sesión</h1>
            <p class="login-subtitle">Ingresa tus credenciales autorizadas para acceder al Panel de Administración.</p>

            @if(session('error'))
                <div class="alert alert-danger">
                    <span>⛔</span>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning">
                    <span>🔒</span>
                    <div>{{ session('warning') }}</div>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <span>✨</span>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <span>⚠️</span>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('web.login.submit') }}" method="POST" id="loginForm">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="login">Usuario o Correo Electrónico</label>
                    <div class="input-wrapper">
                        <input type="text" id="login" name="login" class="form-control" placeholder="ejemplo@vivego.pe o usuario" value="{{ old('login') }}" required autofocus>
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••••••" required>
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <button type="button" class="toggle-password" id="togglePasswordBtn" title="Mostrar/Ocultar Contraseña">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-checkbox">
                        <input type="checkbox" name="remember" checked>
                        <span>Recordar mi sesión</span>
                    </label>
                    <a href="#" class="forgot-link" onclick="alert('Contacta al Administrador Principal para restablecer tu contraseña.'); return false;">¿Olvidaste clave?</a>
                </div>

                <button type="submit" class="btn-login-submit">
                    🚀 Iniciar Sesión en el Dashboard
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="login-footer">
        © {{ date('Y') }} Vive Go S.A.C. Todos los derechos reservados. Sistema Oficial de Gestión de Eventos.
    </footer>

    <script>
        // Toggle contraseña
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (toggleBtn && passInput) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = passInput.type === 'password';
                passInput.type = isPassword ? 'text' : 'password';
                eyeIcon.style.color = isPassword ? '#FF5500' : '#64748B';
            });
        }
    </script>
</body>
</html>
