@extends('layouts.app')

@section('title', 'Política de Cookies y Tecnologías Similares | Vive Go')

@push('styles')
<style>
    .legal-page-root {
        background: #0A0A10;
        color: #F8FAFC;
        min-height: 100vh;
        padding-top: 2rem;
        padding-bottom: 5rem;
    }

    .legal-hero {
        background: radial-gradient(circle at 50% 0%, rgba(0, 242, 254, 0.15) 0%, rgba(15, 15, 20, 0) 70%),
                    linear-gradient(180deg, #14141E 0%, #0A0A10 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 3.5rem 1rem 2.5rem 1rem;
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .legal-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 1rem;
        border-radius: 9999px;
        background: rgba(0, 242, 254, 0.12);
        border: 1px solid rgba(0, 242, 254, 0.3);
        color: #00F2FE;
        font-size: 0.825rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1.25rem;
    }

    .legal-title {
        font-family: var(--font-heading, 'Outfit', sans-serif);
        font-size: 2.75rem;
        font-weight: 900;
        color: #FFFFFF;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .legal-subtitle {
        color: #94A3B8;
        font-size: 1.05rem;
        max-width: 680px;
        margin: 0 auto 1.5rem auto;
        line-height: 1.6;
    }

    .legal-nav-tabs {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }

    .legal-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.35rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #CBD5E1;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .legal-tab-btn:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.2);
        color: #FFFFFF;
        transform: translateY(-2px);
    }

    .legal-tab-btn.active {
        background: linear-gradient(135deg, #00C6FF, #0072FF);
        border-color: transparent;
        color: #FFFFFF;
        box-shadow: 0 4px 18px rgba(0, 198, 255, 0.35);
    }

    .legal-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    .legal-sidebar {
        position: sticky;
        top: 2rem;
        background: #14141E;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        padding: 1.25rem;
        max-height: calc(100vh - 4rem);
        overflow-y: auto;
    }

    .legal-sidebar-title {
        font-size: 0.85rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #00F2FE;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .legal-toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .legal-toc-link {
        display: block;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        color: #94A3B8;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        line-height: 1.35;
        transition: all 0.15s ease;
    }

    .legal-toc-link:hover {
        color: #FFFFFF;
        background: rgba(255, 255, 255, 0.05);
        padding-left: 0.95rem;
    }

    .legal-toc-link.active {
        color: #00F2FE;
        background: rgba(0, 242, 254, 0.1);
        font-weight: 700;
    }

    .legal-content-card {
        background: #14141E;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }

    .legal-section {
        margin-bottom: 2.5rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .legal-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .legal-section-num {
        font-family: var(--font-heading, 'Outfit', sans-serif);
        font-size: 1.4rem;
        font-weight: 800;
        color: #00F2FE;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .legal-section-title {
        color: #FFFFFF;
    }

    .legal-text {
        color: #CBD5E1;
        font-size: 0.95rem;
        line-height: 1.75;
        margin-bottom: 1rem;
    }

    .legal-list {
        list-style: none;
        padding-left: 0;
        margin: 1rem 0;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .legal-list li {
        position: relative;
        padding-left: 1.5rem;
        color: #CBD5E1;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .legal-list li::before {
        content: "•";
        position: absolute;
        left: 0.4rem;
        color: #00F2FE;
        font-size: 1.25rem;
        line-height: 1;
        top: 0.15rem;
    }

    .legal-highlight-box {
        background: rgba(0, 242, 254, 0.06);
        border: 1px solid rgba(0, 242, 254, 0.2);
        border-left: 4px solid #00F2FE;
        border-radius: 12px;
        padding: 1.25rem;
        margin: 1.25rem 0;
        color: #E2E8F0;
        font-size: 0.925rem;
        line-height: 1.65;
    }

    .cookie-table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        font-size: 0.9rem;
    }

    .cookie-table th {
        background: rgba(255, 255, 255, 0.06);
        color: #00F2FE;
        text-align: left;
        padding: 0.75rem 1rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        font-weight: 700;
    }

    .cookie-table td {
        padding: 0.75rem 1rem;
        border: 1px solid rgba(255, 255, 255, 0.06);
        color: #CBD5E1;
    }

    @media (max-width: 991px) {
        .legal-container {
            grid-template-columns: 1fr;
        }
        .legal-sidebar {
            display: none;
        }
        .legal-title {
            font-size: 2rem;
        }
        .legal-content-card {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="legal-page-root">
    <!-- Hero Header -->
    <div class="legal-hero">
        <div class="container">
            <span class="legal-badge">🍪 Tecnologías de Navegación</span>
            <h1 class="legal-title">Política de Cookies</h1>
            <p class="legal-subtitle">
                Conoce cómo <strong>IPEXA S.A.C.</strong> utiliza cookies y tecnologías similares en la plataforma <strong>VIVEGO.PE</strong> para garantizar el funcionamiento seguro del sitio web y mejorar tu experiencia.
            </p>
            <div style="font-size: 0.85rem; color: #64748B; font-weight: 600;">
                Última actualización: Agosto de 2026 • IPEXA S.A.C. (RUC N.° 20606476231)
            </div>

            <!-- Navegación entre Políticas -->
            <div class="legal-nav-tabs">
                <a href="{{ route('web.terms') }}" class="legal-tab-btn">
                    📜 Términos y Condiciones
                </a>
                <a href="{{ route('web.privacy') }}" class="legal-tab-btn">
                    🔒 Políticas de Privacidad
                </a>
                <a href="{{ route('web.cookies') }}" class="legal-tab-btn active">
                    🍪 Política de Cookies
                </a>
                <a href="{{ route('web.claim_book') }}" class="legal-tab-btn">
                    📖 Libro de Reclamaciones
                </a>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="legal-container">
        <!-- Sidebar TOC -->
        <aside class="legal-sidebar">
            <div class="legal-sidebar-title">
                <span>📑</span> Secciones
            </div>
            <ul class="legal-toc-list">
                <li><a href="#c-1" class="legal-toc-link">1. ¿Qué es esta Política?</a></li>
                <li><a href="#c-2" class="legal-toc-link">2. ¿Qué son las Cookies?</a></li>
                <li><a href="#c-3" class="legal-toc-link">3. ¿Para qué las usamos?</a></li>
                <li><a href="#c-4" class="legal-toc-link">4. Tipos de Cookies</a></li>
                <li><a href="#c-5" class="legal-toc-link">5. Cookies de Terceros</a></li>
                <li><a href="#c-6" class="legal-toc-link">6. Duración de las Cookies</a></li>
                <li><a href="#c-7" class="legal-toc-link">7. ¿Cómo controlarlas?</a></li>
                <li><a href="#c-8" class="legal-toc-link">8. Modificaciones y Contacto</a></li>
            </ul>
        </aside>

        <!-- Main Body -->
        <div class="legal-content-card">
            
            <!-- 1. ¿QUÉ ES ESTA POLÍTICA? -->
            <section id="c-1" class="legal-section">
                <div class="legal-section-num">
                    <span>1.</span>
                    <span class="legal-section-title">¿QUÉ ES ESTA POLÍTICA DE COOKIES?</span>
                </div>
                <p class="legal-text">
                    La presente Política de Cookies explica cómo <strong>IPEXA S.A.C.</strong>, identificada con RUC N.° <strong>20606476231</strong>, en adelante “IPEXA S.A.C.”, utiliza cookies y tecnologías similares en la plataforma digital <strong>VIVEGO.PE</strong> (“VIVEGO” o la “Plataforma”).
                </p>
                <p class="legal-text">
                    El objetivo de estas herramientas es contribuir al correcto funcionamiento del sitio web, recordar tus selecciones de boletos en el carrito, reforzar la seguridad de las transacciones y comprender cómo interactúan los usuarios con nuestros servicios para mejorarlos continuamente.
                </p>
            </section>

            <!-- 2. ¿QUÉ SON LAS COOKIES? -->
            <section id="c-2" class="legal-section">
                <div class="legal-section-num">
                    <span>2.</span>
                    <span class="legal-section-title">¿QUÉ SON LAS COOKIES?</span>
                </div>
                <p class="legal-text">
                    Las cookies son pequeños archivos de texto que los sitios web almacenan en tu navegador o dispositivo cuando los visitas. Permiten que el sistema recuerde tus acciones o preferencias durante una sesión o en visitas posteriores (por ejemplo, mantener activa tu sesión o recordar el estado de una compra).
                </p>
            </section>

            <!-- 3. ¿PARA QUÉ UTILIZAMOS COOKIES EN VIVEGO.PE? -->
            <section id="c-3" class="legal-section">
                <div class="legal-section-num">
                    <span>3.</span>
                    <span class="legal-section-title">¿PARA QUÉ UTILIZAMOS COOKIES EN VIVEGO.PE?</span>
                </div>
                <p class="legal-text">
                    En VIVEGO.PE empleamos estas tecnologías para las siguientes finalidades esenciales:
                </p>
                <ul class="legal-list">
                    <li><strong>Funcionamiento Esencial:</strong> Mantener la sesión activa, procesar el carrito de compras, verificar tokens CSRF de seguridad y permitir la navegación fluida.</li>
                    <li><strong>Preferencias y Personalización:</strong> Recordar preferencias de interfaz, filtros de eventos y datos previamente completados para facilitar tu navegación.</li>
                    <li><strong>Seguridad y Prevención de Fraudes:</strong> Detectar intentos de acceso no autorizados, frenar ataques automatizados (bots) y proteger los pagos electrónicos.</li>
                    <li><strong>Análisis y Rendimiento:</strong> Evaluar métricas generales de tráfico, páginas más visitadas y tiempos de carga para optimizar la velocidad de la web.</li>
                    <li><strong>Medición de Campañas:</strong> Conocer el impacto de promociones y cupones de descuento aplicados por los organizadores.</li>
                </ul>
            </section>

            <!-- 4. TIPOS DE COOKIES -->
            <section id="c-4" class="legal-section">
                <div class="legal-section-num">
                    <span>4.</span>
                    <span class="legal-section-title">TIPOS DE COOKIES QUE PODRÍAN UTILIZARSE</span>
                </div>
                <table class="cookie-table">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Propósito</th>
                            <th>Obligatoriedad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Técnicas / Esenciales</strong></td>
                            <td>Permiten la autenticación, seguridad de formularios y proceso de compra.</td>
                            <td>Necesarias</td>
                        </tr>
                        <tr>
                            <td><strong>De Personalización</strong></td>
                            <td>Guardan preferencias de filtros, categorías y vistas seleccionadas.</td>
                            <td>Opcionales</td>
                        </tr>
                        <tr>
                            <td><strong>Analíticas</strong></td>
                            <td>Recopilan información estadística anónima sobre el uso del sitio.</td>
                            <td>Opcionales</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- 5. COOKIES PROPIAS Y DE TERCEROS -->
            <section id="c-5" class="legal-section">
                <div class="legal-section-num">
                    <span>5.</span>
                    <span class="legal-section-title">COOKIES PROPIAS Y TECNOLOGÍAS DE TERCEROS</span>
                </div>
                <p class="legal-text">
                    Determinadas cookies son gestionadas directamente por VIVEGO.PE, mientras que otras corresponden a proveedores certificados como pasarelas de pago (Culqi, Izipay) para validar transacciones seguras o servicios de analítica web.
                </p>
            </section>

            <!-- 6. DURACIÓN DE LAS COOKIES -->
            <section id="c-6" class="legal-section">
                <div class="legal-section-num">
                    <span>6.</span>
                    <span class="legal-section-title">DURACIÓN DE LAS COOKIES</span>
                </div>
                <ul class="legal-list">
                    <li><strong>Cookies de Sesión:</strong> Temporales, se eliminan automáticamente cuando cierras el navegador.</li>
                    <li><strong>Cookies Persistentes:</strong> Permanecen en tu dispositivo durante un tiempo determinado para recordar tu sesión o preferencias en futuras visitas.</li>
                </ul>
            </section>

            <!-- 7. CONTROL DE COOKIES -->
            <section id="c-7" class="legal-section">
                <div class="legal-section-num">
                    <span>7.</span>
                    <span class="legal-section-title">¿CÓMO PUEDE EL USUARIO CONTROLAR LAS COOKIES?</span>
                </div>
                <p class="legal-text">
                    Puedes configurar o restringir el uso de cookies en cualquier momento a través de los ajustes de tu navegador web:
                </p>
                <ul class="legal-list">
                    <li><strong>Google Chrome:</strong> Configuración &gt; Privacidad y seguridad &gt; Cookies y otros datos de sitios.</li>
                    <li><strong>Mozilla Firefox:</strong> Opciones &gt; Privacidad y seguridad &gt; Cookies y datos del sitio.</li>
                    <li><strong>Apple Safari:</strong> Preferencias &gt; Privacidad &gt; Bloquear todas las cookies.</li>
                    <li><strong>Microsoft Edge:</strong> Configuración &gt; Permisos del sitio &gt; Cookies y datos almacenados.</li>
                </ul>
                <div class="legal-highlight-box">
                    <strong>Nota:</strong> Si decides bloquear las cookies esenciales, es posible que ciertas funcionalidades como el checkout o el acceso a "Mis Boletos" no funcionen correctamente.
                </div>
            </section>

            <!-- 8. MODIFICACIONES Y CONTACTO -->
            <section id="c-8" class="legal-section">
                <div class="legal-section-num">
                    <span>8.</span>
                    <span class="legal-section-title">MODIFICACIONES Y CONTACTO</span>
                </div>
                <p class="legal-text">
                    IPEXA S.A.C. podrá actualizar esta Política periódicamente. Cualquier cambio será publicado en esta página con su fecha de vigencia. Para cualquier consulta:
                </p>
                <ul class="legal-list">
                    <li><strong>Razón Social:</strong> IPEXA S.A.C. (RUC 20606476231)</li>
                    <li><strong>Dirección:</strong> Jr. Parinacochas N.º 11, Lima, Perú</li>
                    <li><strong>Libro de Reclamaciones:</strong> <a href="{{ route('web.claim_book') }}" style="color: #00F2FE; font-weight: 700;">vivego.pe/libro-de-reclamaciones</a></li>
                </ul>
            </section>

        </div>
    </div>
</div>
@endsection
