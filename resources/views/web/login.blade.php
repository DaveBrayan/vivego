<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Vive Go</title>
    <meta name="description" content="Portal oficial de acceso seguro para clientes y organizadores de Vive Go.">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --color-bg: #F8FAFC;
            --color-card: #FFFFFF;
            --color-border: #E2E8F0;
            --color-border-focus: #FF5500;
            --color-primary: #FF5500;
            --color-primary-hover: #E04B00;
            --color-primary-light: #FFF7ED;
            --color-primary-glow: rgba(255, 85, 0, 0.18);
            --color-text-main: #0F172A;
            --color-text-muted: #64748B;
            --color-text-subtle: #94A3B8;
            --color-input-bg: #F8FAFC;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: var(--color-bg);
            color: var(--color-text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Subtle Mesh Accents */
        .bg-decor-circle-1 {
            position: fixed;
            top: -120px;
            right: -100px;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 85, 0, 0.08) 0%, rgba(255, 85, 0, 0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        .bg-decor-circle-2 {
            position: fixed;
            bottom: -150px;
            left: -120px;
            width: 520px;
            height: 520px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.07) 0%, rgba(6, 182, 212, 0) 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* 2-Column Split Container */
        .login-split-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1080px;
            min-height: 640px;
            background: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 28px;
            box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(226, 232, 240, 0.6);
            display: grid;
            grid-template-columns: 1.05fr 1.15fr;
            overflow: hidden;
            animation: cardAppear 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(18px) scale(0.985);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* =========================================================================
           LEFT COLUMN: Brand & Experience Showcase
           ========================================================================= */
        .login-showcase-column {
            background: linear-gradient(145deg, #0F172A 0%, #1E293B 60%, #0F172A 100%);
            color: #FFFFFF;
            padding: 3.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-showcase-column::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(255, 85, 0, 0.28) 0%, rgba(255, 85, 0, 0) 70%);
            pointer-events: none;
        }

        .login-showcase-column::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -40px;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.22) 0%, rgba(6, 182, 212, 0) 70%);
            pointer-events: none;
        }

        .showcase-header {
            position: relative;
            z-index: 2;
        }

        .showcase-logo img {
            height: 42px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.3));
            margin-bottom: 2rem;
        }

        .showcase-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            font-size: 0.775rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #FED7AA;
            margin-bottom: 1.25rem;
        }

        .showcase-badge-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #FF5500;
            box-shadow: 0 0 10px #FF5500;
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.4;
                transform: scale(0.85);
            }
        }

        .showcase-title {
            font-size: 2.1rem;
            font-weight: 900;
            line-height: 1.2;
            letter-spacing: -0.5px;
            color: #FFFFFF;
            margin-bottom: 1rem;
        }

        .showcase-title span {
            background: linear-gradient(135deg, #FF7733 0%, #FFAA55 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .showcase-description {
            font-size: 0.95rem;
            color: #94A3B8;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            max-width: 380px;
        }

        /* Features List */
        .showcase-features {
            display: flex;
            flex-direction: column;
            gap: 1.15rem;
            position: relative;
            z-index: 2;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
        }

        .feature-icon-box {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .feature-text-title {
            font-size: 0.9rem;
            font-weight: 800;
            color: #FFFFFF;
            margin-bottom: 0.15rem;
        }

        .feature-text-desc {
            font-size: 0.8rem;
            color: #94A3B8;
            line-height: 1.4;
        }

        /* Showcase Trust Card */
        .showcase-footer {
            position: relative;
            z-index: 2;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .trust-stat {
            display: flex;
            flex-direction: column;
        }

        .trust-stat-num {
            font-size: 1.25rem;
            font-weight: 900;
            color: #FFFFFF;
        }

        .trust-stat-label {
            font-size: 0.75rem;
            color: #94A3B8;
            font-weight: 600;
        }

        /* =========================================================================
           RIGHT COLUMN: Clean Light Login Form
           ========================================================================= */
        .login-form-column {
            padding: 3.5rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #FFFFFF;
        }

        .form-top-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .btn-return-home {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            color: var(--color-text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 0.45rem 0.85rem;
            border-radius: 10px;
            background: #F1F5F9;
            transition: all 0.2s ease;
        }

        .btn-return-home:hover {
            color: var(--color-text-main);
            background: #E2E8F0;
            transform: translateX(-2px);
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-main-title {
            font-size: 1.85rem;
            font-weight: 900;
            letter-spacing: -0.5px;
            color: var(--color-text-main);
            margin-bottom: 0.45rem;
        }

        .form-main-subtitle {
            font-size: 0.925rem;
            color: var(--color-text-muted);
            line-height: 1.5;
        }

        /* Alerts (Light-Themed) */
        .alert {
            padding: 0.9rem 1.1rem;
            border-radius: 14px;
            font-size: 0.875rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            line-height: 1.4;
        }

        .alert-danger {
            background: #FEF2F2;
            border: 1.5px solid #FECACA;
            color: #DC2626;
        }

        .alert-success {
            background: #F0FDF4;
            border: 1.5px solid #BBF7D0;
            color: #16A34A;
        }

        .alert-warning {
            background: #FFFBEB;
            border: 1.5px solid #FDE68A;
            color: #D97706;
        }

        .alert-info {
            background: #EFF6FF;
            border: 1.5px solid #BFDBFE;
            color: #2563EB;
        }

        /* Form Group & Inputs */
        .form-group {
            margin-bottom: 1.35rem;
        }

        .form-label {
            display: block;
            font-size: 0.825rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .input-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 1.1rem;
            color: #94A3B8;
            pointer-events: none;
            transition: color 0.2s ease;
        }

        .form-input {
            width: 100%;
            padding: 0.95rem 1.1rem 0.95rem 3rem;
            background: var(--color-input-bg);
            border: 1.5px solid var(--color-border);
            border-radius: 14px;
            color: var(--color-text-main);
            font-size: 0.95rem;
            font-weight: 600;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: #94A3B8;
            font-weight: 500;
        }

        .form-input:focus {
            background: #FFFFFF;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px var(--color-primary-glow);
        }

        .form-input:focus+.input-icon-left,
        .input-box:focus-within .input-icon-left {
            color: var(--color-primary);
        }

        .toggle-password-btn {
            position: absolute;
            right: 1.1rem;
            background: none;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }

        .toggle-password-btn:hover {
            color: var(--color-text-main);
        }

        /* Form Row: Remember Me Only (No Forgot Password) */
        .form-options-row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 1.75rem;
        }

        .remember-label {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--color-text-muted);
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            user-select: none;
        }

        .remember-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--color-primary);
            border-radius: 5px;
            cursor: pointer;
        }

        /* Submit Button */
        .btn-submit-main {
            width: 100%;
            padding: 1.05rem 1.5rem;
            background: linear-gradient(135deg, #FF5500 0%, #E64A00 100%);
            border: none;
            border-radius: 14px;
            color: #FFFFFF;
            font-size: 1.05rem;
            font-weight: 900;
            letter-spacing: 0.2px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            box-shadow: 0 10px 24px rgba(255, 85, 0, 0.35);
            transition: all 0.25s ease;
        }

        .btn-submit-main:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #FF661A 0%, #ED5200 100%);
            box-shadow: 0 14px 28px rgba(255, 85, 0, 0.45);
        }

        .btn-submit-main:active {
            transform: translateY(0);
        }

        /* Form Footer */
        .form-footer-note {
            text-align: center;
            font-size: 0.8rem;
            color: var(--color-text-subtle);
            font-weight: 500;
            margin-top: 2rem;
        }

        /* Responsive Breakpoints */
        @media (max-width: 960px) {
            .login-split-wrapper {
                grid-template-columns: 1fr;
                max-width: 520px;
                min-height: auto;
            }

            .login-showcase-column {
                display: none;
            }

            .login-form-column {
                padding: 2.5rem 2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Background Light Accents -->
    <div class="bg-decor-circle-1"></div>
    <div class="bg-decor-circle-2"></div>

    <!-- 2-Column Split Authentication Card -->
    <div class="login-split-wrapper">

        <!-- LEFT COLUMN: Brand Experience Showcase -->
        <div class="login-showcase-column">
            <div class="showcase-header">
                <div class="showcase-logo">
                    <img src="{{ asset('images/logo-white.png') }}" alt="Vive Go">
                </div>

                <div class="showcase-badge">
                    <span class="showcase-badge-dot"></span>
                    Plataforma Oficial de Eventos
                </div>

                <h2 class="showcase-title">
                    Vive cada momento <span>al máximo</span>
                </h2>

                <p class="showcase-description">
                    Accede a tus entradas oficiales o ingresa a la central de gestión para administrar tus eventos en
                    tiempo real.
                </p>
            </div>

            <!-- Features -->
            <div class="showcase-features">
                <div class="feature-item">
                    <div class="feature-icon-box">🎟️</div>
                    <div>
                        <div class="feature-text-title">Tus Entradas y Recibos</div>
                        <div class="feature-text-desc">Descarga tus boletos en PDF con código QR y consulta
                            comprobantes.</div>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon-box">⚡</div>
                    <div>
                        <div class="feature-text-title">Control Total de Eventos</div>
                        <div class="feature-text-desc">Taquilla POS, escaneo en vivo de asistentes y analítica de
                            ventas.</div>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon-box">🛡️</div>
                    <div>
                        <div class="feature-text-title">Seguridad y Garantía</div>
                        <div class="feature-text-desc">Acceso protegido con cifrado de datos y validación de seguridad.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust Stats -->
            <div class="showcase-footer">
                <div class="trust-stat">
                    <span class="trust-stat-num">100%</span>
                    <span class="trust-stat-label">Boletos Oficiales</span>
                </div>
                <div class="trust-stat">
                    <span class="trust-stat-num">24/7</span>
                    <span class="trust-stat-label">Disponibilidad</span>
                </div>
                <div class="trust-stat">
                    <span class="trust-stat-num">⭐⭐⭐⭐⭐</span>
                    <span class="trust-stat-label">Experiencia Vive Go</span>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Light Login Form -->
        <div class="login-form-column">
            <div>
                <!-- Top Nav Action -->
                <div class="form-top-nav">
                    <a href="{{ route('web.home') }}" class="btn-return-home">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Volver a Inicio
                    </a>
                </div>

                <!-- Form Header -->
                <div class="form-header">
                    <h1 class="form-main-title">Iniciar Sesión</h1>
                    <p class="form-main-subtitle">Ingresa tus credenciales para acceder a tu cuenta o panel de control.
                    </p>
                </div>

                <!-- Alerts -->
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

                @if(session('info'))
                    <div class="alert alert-info">
                        <span>ℹ️</span>
                        <div>{{ session('info') }}</div>
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

                <!-- Unified Form -->
                <form action="{{ route('web.login.submit') }}" method="POST" id="loginForm">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="login">Usuario, Correo o DNI</label>
                        <div class="input-box">
                            <svg class="input-icon-left" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <input type="text" id="login" name="login" class="form-input"
                                placeholder="ejemplo@vivego.pe, usuario o DNI" value="{{ old('login') }}" required
                                autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Contraseña</label>
                        <div class="input-box">
                            <svg class="input-icon-left" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <input type="password" id="password" name="password" class="form-input"
                                placeholder="••••••••••••" required>
                            <button type="button" class="toggle-password-btn" id="togglePasswordBtn"
                                title="Mostrar/Ocultar Contraseña">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-options-row">
                        <label class="remember-label">
                            <input type="checkbox" name="remember" checked>
                            <span>Recordar mi sesión</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-submit-main">
                        🚀 Iniciar Sesión en Vive Go
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <footer class="form-footer-note">
                © {{ date('Y') }} Vive Go Todos los derechos reservados.
            </footer>
        </div>

    </div>

    <script>
        // Toggle visibilidad de contraseña
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (toggleBtn && passInput) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = passInput.type === 'password';
                passInput.type = isPassword ? 'text' : 'password';
                eyeIcon.style.color = isPassword ? '#FF5500' : '#94A3B8';
            });
        }
    </script>
</body>

</html>