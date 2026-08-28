@extends('layouts.app')

@section('title', 'Políticas de Privacidad y Protección de Datos | Vive Go')

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
        background: radial-gradient(circle at 50% 0%, rgba(255, 30, 60, 0.18) 0%, rgba(15, 15, 20, 0) 70%),
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
        background: rgba(255, 30, 60, 0.12);
        border: 1px solid rgba(255, 30, 60, 0.3);
        color: #FF1E3C;
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
        background: linear-gradient(135deg, #FF1E3C, #FF5500);
        border-color: transparent;
        color: #FFFFFF;
        box-shadow: 0 4px 18px rgba(255, 30, 60, 0.35);
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
        color: #FF1E3C;
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
        color: #FF1E3C;
        background: rgba(255, 30, 60, 0.1);
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
        color: #FF1E3C;
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
        color: #FF1E3C;
        font-size: 1.25rem;
        line-height: 1;
        top: 0.15rem;
    }

    .legal-highlight-box {
        background: rgba(255, 30, 60, 0.06);
        border: 1px solid rgba(255, 30, 60, 0.2);
        border-left: 4px solid #FF1E3C;
        border-radius: 12px;
        padding: 1.25rem;
        margin: 1.25rem 0;
        color: #E2E8F0;
        font-size: 0.925rem;
        line-height: 1.65;
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
            <span class="legal-badge">🔒 Protección de Datos Personales</span>
            <h1 class="legal-title">Políticas de Privacidad</h1>
            <p class="legal-subtitle">
                En <strong>IPEXA S.A.C.</strong> garantizamos la confidencialidad, seguridad y el tratamiento responsable de tus datos personales conforme a la Ley N.° 29733 (Ley de Protección de Datos Personales del Perú).
            </p>
            <div style="font-size: 0.85rem; color: #64748B; font-weight: 600;">
                Última actualización: Agosto de 2026 • IPEXA S.A.C. (RUC N.° 20606476231)
            </div>

            <!-- Navegación entre Políticas -->
            <div class="legal-nav-tabs">
                <a href="{{ route('web.terms') }}" class="legal-tab-btn">
                    📜 Términos y Condiciones
                </a>
                <a href="{{ route('web.privacy') }}" class="legal-tab-btn active">
                    🔒 Políticas de Privacidad
                </a>
                <a href="{{ route('web.cookies') }}" class="legal-tab-btn">
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
                <li><a href="#p-1" class="legal-toc-link">1. Alcance de la Política</a></li>
                <li><a href="#p-2" class="legal-toc-link">2. Información Recopilada</a></li>
                <li><a href="#p-3" class="legal-toc-link">3. Veracidad de Datos</a></li>
                <li><a href="#p-4" class="legal-toc-link">4. Finalidades del Tratamiento</a></li>
                <li><a href="#p-5" class="legal-toc-link">5. Comunicaciones del Servicio</a></li>
                <li><a href="#p-6" class="legal-toc-link">6. Análisis y Mejora</a></li>
                <li><a href="#p-7" class="legal-toc-link">7. Base Legal</a></li>
                <li><a href="#p-8" class="legal-toc-link">8. Plazo de Conservación</a></li>
                <li><a href="#p-9" class="legal-toc-link">9. Destinatarios y Terceros</a></li>
                <li><a href="#p-10" class="legal-toc-link">10. Flujo Transfronterizo</a></li>
                <li><a href="#p-11" class="legal-toc-link">11. Medidas de Seguridad</a></li>
                <li><a href="#p-12" class="legal-toc-link">12. Derechos ARCO</a></li>
                <li><a href="#p-13" class="legal-toc-link">13. Cookies y Tecnologías</a></li>
                <li><a href="#p-14" class="legal-toc-link">14. Enlaces de Terceros</a></li>
                <li><a href="#p-15" class="legal-toc-link">15. Modificaciones</a></li>
                <li><a href="#p-16" class="legal-toc-link">16. Autoridad de Control</a></li>
                <li><a href="#p-17" class="legal-toc-link">17. Contacto para Privacidad</a></li>
            </ul>
        </aside>

        <!-- Main Body -->
        <div class="legal-content-card">
            
            <!-- 1. ALCANCE -->
            <section id="p-1" class="legal-section">
                <div class="legal-section-num">
                    <span>1.</span>
                    <span class="legal-section-title">ALCANCE DE LA PRESENTE POLÍTICA</span>
                </div>
                <p class="legal-text">
                    La presente Política de Privacidad establece las condiciones bajo las cuales <strong>INDUSTRIA PERUANA DE EXPORTACIÓN E IMPORTACIÓN ANDINA S.A.C.</strong>, identificada con RUC N.° <strong>20606476231</strong>, en adelante “IPEXA S.A.C.”, realiza el tratamiento de los datos personales obtenidos a través de la plataforma digital <strong>VIVEGO.PE</strong>, sus páginas, formularios de compra, canales de atención y demás medios digitales vinculados.
                </p>
                <div class="legal-highlight-box">
                    <strong>Compromiso Institucional:</strong> En IPEXA S.A.C. nos preocupamos por la protección y privacidad de los datos personales de nuestros usuarios. Garantizamos la confidencialidad de tu información y empleamos altos estándares de seguridad técnica y organizativa.
                </div>
            </section>

            <!-- 2. INFORMACIÓN QUE PODRÁ SER RECOPILADA -->
            <section id="p-2" class="legal-section">
                <div class="legal-section-num">
                    <span>2.</span>
                    <span class="legal-section-title">INFORMACIÓN QUE PODRÁ SER RECOPILADA</span>
                </div>
                <p class="legal-text">
                    Dependiendo del tipo de servicio utilizado en la Plataforma, podremos recopilar:
                </p>
                <ul class="legal-list">
                    <li><strong>Datos de Identificación:</strong> Nombres, apellidos, tipo y número de documento de identidad (DNI, Carné de Extranjería, Pasaporte, RUC), fecha de nacimiento y nacionalidad.</li>
                    <li><strong>Datos de Contacto:</strong> Correo electrónico, número telefónico celular, dirección física o de entrega, ciudad y distrito.</li>
                    <li><strong>Información de Transacciones:</strong> Historial de compras, entradas adquiridas, fechas de eventos, importes pagados y método de pago utilizado (sin almacenar datos sensibles de tarjetas).</li>
                    <li><strong>Datos de Organizadores / Vendedores:</strong> Razón social, RUC, nombre comercial, datos de representantes y cuentas bancarias para liquidación de eventos.</li>
                    <li><strong>Datos Técnicos de Navegación:</strong> Dirección IP, tipo de navegador, sistema operativo, dispositivo y registros de acceso por motivos de seguridad y prevención de fraudes.</li>
                </ul>
            </section>

            <!-- 3. VERACIDAD Y ACTUALIZACIÓN DE LA INFORMACIÓN -->
            <section id="p-3" class="legal-section">
                <div class="legal-section-num">
                    <span>3.</span>
                    <span class="legal-section-title">VERACIDAD Y ACTUALIZACIÓN DE LA INFORMACIÓN</span>
                </div>
                <p class="legal-text">
                    El Usuario garantiza que los datos personales proporcionados son verdaderos, completos, exactos y vigentes. Toda consecuencia derivada de información falsa, errónea o incompleta será responsabilidad exclusiva del Usuario.
                </p>
            </section>

            <!-- 4. FINALIDADES DEL TRATAMIENTO -->
            <section id="p-4" class="legal-section">
                <div class="legal-section-num">
                    <span>4.</span>
                    <span class="legal-section-title">FINALIDADES DEL TRATAMIENTO DE DATOS</span>
                </div>
                <p class="legal-text">
                    Tus datos personales serán tratados exclusivamente para:
                </p>
                <ul class="legal-list">
                    <li>Gestionar el registro, autenticación y administración de cuentas de usuario.</li>
                    <li>Procesar la compra de entradas y emisión de boletos oficiales con código QR.</li>
                    <li>Validar la identidad del comprador y prevenir transacciones fraudulentas o reventas ilegales.</li>
                    <li>Enviar entradas digitales, confirmaciones de pago, tickets PDF y notificaciones sobre el evento.</li>
                    <li>Atender consultas, solicitudes de soporte técnico y requerimientos a través del Libro de Reclamaciones.</li>
                    <li>Proporcionar a los Organizadores la lista oficial de asistentes para control de acceso y aforo en puerta.</li>
                    <li>Cumplir obligaciones legales, tributarias y requerimientos formulados por autoridades competentes.</li>
                </ul>
            </section>

            <!-- 5. COMUNICACIONES -->
            <section id="p-5" class="legal-section">
                <div class="legal-section-num">
                    <span>5.</span>
                    <span class="legal-section-title">COMUNICACIONES RELACIONADAS CON EL SERVICIO</span>
                </div>
                <p class="legal-text">
                    IPEXA S.A.C. podrá comunicarse mediante correo electrónico, SMS, WhatsApp o notificaciones push para informar sobre el estado de las órdenes, cambios de programación, reprogramaciones, alertas de seguridad o resolución de quejas y reclamos.
                </p>
            </section>

            <!-- 6. ANÁLISIS Y MEJORA -->
            <section id="p-6" class="legal-section">
                <div class="legal-section-num">
                    <span>6.</span>
                    <span class="legal-section-title">ANÁLISIS Y MEJORA DE LOS SERVICIOS</span>
                </div>
                <p class="legal-text">
                    Podremos procesar datos de uso anonimizados o disociados para generar estadísticas agregadas, medir el rendimiento del marketplace y optimizar la experiencia de usuario en la Plataforma.
                </p>
            </section>

            <!-- 7. BASE LEGAL -->
            <section id="p-7" class="legal-section">
                <div class="legal-section-num">
                    <span>7.</span>
                    <span class="legal-section-title">BASE LEGAL PARA EL TRATAMIENTO</span>
                </div>
                <p class="legal-text">
                    El tratamiento de datos se sustenta en: (i) la ejecución de la relación contractual de compraventa y emisión de tickets, (ii) el consentimiento libre, previo, expreso e informado del Usuario, (iii) el cumplimiento de obligaciones legales impuestas por la legislación peruana, y (iv) el interés legítimo en la prevención de fraudes y seguridad informática.
                </p>
            </section>

            <!-- 8. PLAZO DE CONSERVACIÓN -->
            <section id="p-8" class="legal-section">
                <div class="legal-section-num">
                    <span>8.</span>
                    <span class="legal-section-title">PLAZO DE CONSERVACIÓN DE DATOS</span>
                </div>
                <p class="legal-text">
                    Los datos personales serán conservados durante el tiempo necesario para la prestación del servicio y, con posterioridad, durante los plazos legales aplicables para la atención de responsabilidades civiles, contractuales o tributarias (máximo 10 años conforme a la legislación peruana).
                </p>
            </section>

            <!-- 9. DESTINATARIOS Y TRANSFERENCIAS -->
            <section id="p-9" class="legal-section">
                <div class="legal-section-num">
                    <span>9.</span>
                    <span class="legal-section-title">DESTINATARIOS Y TRANSFERENCIA DE DATOS</span>
                </div>
                <p class="legal-text">
                    Tus datos podrán ser comunicados a:
                </p>
                <ul class="legal-list">
                    <li>Organizadores del evento adquirido, exclusivamente para la validación y control de acceso.</li>
                    <li>Pasarelas de pago y entidades financieras procesadoras (Culqi, Izipay, bancos) para el cobro seguro.</li>
                    <li>Proveedores tecnológicos de alojamiento en la nube, envío de correos transaccionales y mensajería.</li>
                    <li>Autoridades judiciales, policiales o administrativas que lo requieran formalmente en el marco de sus competencias.</li>
                </ul>
            </section>

            <!-- 10. FLUJO TRANSFRONTERIZO -->
            <section id="p-10" class="legal-section">
                <div class="legal-section-num">
                    <span>10.</span>
                    <span class="legal-section-title">FLUJO TRANSFRONTERIZO DE DATOS</span>
                </div>
                <p class="legal-text">
                    Para la operación de la Plataforma y respaldo seguro en la nube, IPEXA S.A.C. utiliza servidores e infraestructura tecnológica ubicados en centros de datos internacionales con estándares de seguridad equiparables a los exigidos por la normativa peruana.
                </p>
            </section>

            <!-- 11. MEDIDAS DE SEGURIDAD -->
            <section id="p-11" class="legal-section">
                <div class="legal-section-num">
                    <span>11.</span>
                    <span class="legal-section-title">MEDIDAS DE SEGURIDAD TÉCNICAS Y ORGANIZATIVAS</span>
                </div>
                <p class="legal-text">
                    Adoptamos medidas de cifrado SSL/TLS de 256 bits, firewalls, almacenamiento seguro de contraseñas con algoritmos hash irreversibles y controles de acceso restringido para proteger la información contra accesos no autorizados, pérdida o alteración.
                </p>
            </section>

            <!-- 12. DERECHOS ARCO -->
            <section id="p-12" class="legal-section">
                <div class="legal-section-num">
                    <span>12.</span>
                    <span class="legal-section-title">EJERCICIO DE DERECHOS ARCO</span>
                </div>
                <p class="legal-text">
                    Conforme a la Ley N.° 29733, puedes ejercer en cualquier momento tus derechos de <strong>Acceso, Rectificación, Cancelación y Oposición (ARCO)</strong> respecto de tus datos personales, enviando una solicitud formal con copia de tu DNI a nuestro correo oficial de privacidad.
                </p>
            </section>

            <!-- 13. COOKIES -->
            <section id="p-13" class="legal-section">
                <div class="legal-section-num">
                    <span>13.</span>
                    <span class="legal-section-title">COOKIES Y TECNOLOGÍAS SIMILARES</span>
                </div>
                <p class="legal-text">
                    VIVEGO.PE utiliza cookies esenciales, analíticas y de personalización para el funcionamiento del carrito de compras y la sesión de usuario. Para mayor detalle, consulta nuestra <a href="{{ route('web.cookies') }}" style="color: #FF1E3C; font-weight: 700;">Política de Cookies</a>.
                </p>
            </section>

            <!-- 14. ENLACES A TERCEROS -->
            <section id="p-14" class="legal-section">
                <div class="legal-section-num">
                    <span>14.</span>
                    <span class="legal-section-title">ENLACES A SITIOS DE TERCEROS</span>
                </div>
                <p class="legal-text">
                    Esta Política de Privacidad aplica únicamente a VIVEGO.PE. Si accedes a enlaces externos de redes sociales o promotores, te recomendamos revisar sus respectivas políticas de privacidad.
                </p>
            </section>

            <!-- 15. MODIFICACIONES -->
            <section id="p-15" class="legal-section">
                <div class="legal-section-num">
                    <span>15.</span>
                    <span class="legal-section-title">MODIFICACIONES A LA POLÍTICA DE PRIVACIDAD</span>
                </div>
                <p class="legal-text">
                    IPEXA S.A.C. podrá actualizar esta Política para adecuarla a cambios legislativos o mejoras operativas. Las modificaciones entrarán en vigencia desde su publicación en este sitio web.
                </p>
            </section>

            <!-- 16. AUTORIDAD DE CONTROL -->
            <section id="p-16" class="legal-section">
                <div class="legal-section-num">
                    <span>16.</span>
                    <span class="legal-section-title">LEGISLACIÓN Y AUTORIDAD DE CONTROL</span>
                </div>
                <p class="legal-text">
                    Esta Política se rige por las leyes de la República del Perú. La Autoridad Nacional de Protección de Datos Personales (Ministerio de Justicia y Derechos Humanos) es el órgano competente para la tutela de estos derechos.
                </p>
            </section>

            <!-- 17. CONTACTO -->
            <section id="p-17" class="legal-section">
                <div class="legal-section-num">
                    <span>17.</span>
                    <span class="legal-section-title">CONTACTO PARA PRIVACIDAD</span>
                </div>
                <p class="legal-text">
                    Para consultas sobre el tratamiento de tus datos o ejercer tus derechos ARCO:
                </p>
                <ul class="legal-list">
                    <li><strong>Responsable:</strong> IPEXA S.A.C. (RUC 20606476231)</li>
                    <li><strong>Domicilio:</strong> Jr. Parinacochas N.º 11, Lima, Perú</li>
                    <li><strong>Canal Digital:</strong> <a href="{{ route('web.home') }}" style="color: #FF1E3C; font-weight: 700;">www.vivego.pe</a></li>
                    <li><strong>Libro de Reclamaciones:</strong> <a href="{{ route('web.claim_book') }}" style="color: #FF1E3C; font-weight: 700;">vivego.pe/libro-de-reclamaciones</a></li>
                </ul>
            </section>

        </div>
    </div>
</div>
@endsection
