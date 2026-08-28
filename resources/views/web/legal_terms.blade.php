@extends('layouts.app')

@section('title', 'Términos y Condiciones de Uso | Vive Go')

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
        background: radial-gradient(circle at 50% 0%, rgba(255, 85, 0, 0.18) 0%, rgba(15, 15, 20, 0) 70%),
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
        background: rgba(255, 85, 0, 0.12);
        border: 1px solid rgba(255, 85, 0, 0.3);
        color: #FF5500;
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
        max-width: 650px;
        margin: 0 auto 1.5rem auto;
        line-height: 1.6;
    }

    /* Tabs de Navegación Legal */
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
        background: linear-gradient(135deg, #FF5500, #FF1E3C);
        border-color: transparent;
        color: #FFFFFF;
        box-shadow: 0 4px 18px rgba(255, 85, 0, 0.35);
    }

    /* Grid Layout */
    .legal-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    /* Sidebar TOC */
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
        color: #FF5500;
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
        color: #FF5500;
        background: rgba(255, 85, 0, 0.1);
        font-weight: 700;
    }

    /* Contenido Legal */
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
        color: #FF5500;
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
        color: #FF5500;
        font-size: 1.25rem;
        line-height: 1;
        top: 0.15rem;
    }

    .legal-highlight-box {
        background: rgba(255, 85, 0, 0.06);
        border: 1px solid rgba(255, 85, 0, 0.2);
        border-left: 4px solid #FF5500;
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
            <span class="legal-badge">📄 Marco Legal Oficial</span>
            <h1 class="legal-title">Términos y Condiciones de Uso</h1>
            <p class="legal-subtitle">
                Conoce las condiciones generales que rigen el acceso, navegación y utilización de la plataforma digital <strong>VIVEGO.PE</strong> y la adquisición de entradas o servicios.
            </p>
            <div style="font-size: 0.85rem; color: #64748B; font-weight: 600;">
                Última actualización: Agosto de 2026 • IPEXA S.A.C. (RUC N.° 20606476231)
            </div>

            <!-- Navegación entre Políticas -->
            <div class="legal-nav-tabs">
                <a href="{{ route('web.terms') }}" class="legal-tab-btn active">
                    📜 Términos y Condiciones
                </a>
                <a href="{{ route('web.privacy') }}" class="legal-tab-btn">
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
                <span>📑</span> Índice de Contenidos
            </div>
            <ul class="legal-toc-list">
                <li><a href="#sec-1" class="legal-toc-link">1. Identificación de la Empresa</a></li>
                <li><a href="#sec-2" class="legal-toc-link">2. ¿Qué es ViveGo.pe?</a></li>
                <li><a href="#sec-3" class="legal-toc-link">3. Comunicaciones Electrónicas</a></li>
                <li><a href="#sec-4" class="legal-toc-link">4. Definiciones</a></li>
                <li><a href="#sec-5" class="legal-toc-link">5. Registro y Cuenta de Usuario</a></li>
                <li><a href="#sec-6" class="legal-toc-link">6. Publicación por Terceros</a></li>
                <li><a href="#sec-7" class="legal-toc-link">7. Compra de Entradas</a></li>
                <li><a href="#sec-8" class="legal-toc-link">8. Responsabilidad del Organizador</a></li>
                <li><a href="#sec-9" class="legal-toc-link">9. Cambios y Cancelaciones</a></li>
                <li><a href="#sec-10" class="legal-toc-link">10. Devoluciones y Reembolsos</a></li>
                <li><a href="#sec-11" class="legal-toc-link">11. Compra de Productos</a></li>
                <li><a href="#sec-12" class="legal-toc-link">12. Precios y Medios de Pago</a></li>
                <li><a href="#sec-13" class="legal-toc-link">13. Nominación y Transferencia</a></li>
                <li><a href="#sec-14" class="legal-toc-link">14. Control de Acceso y Seguridad</a></li>
                <li><a href="#sec-15" class="legal-toc-link">15. Conducta del Usuario</a></li>
                <li><a href="#sec-16" class="legal-toc-link">16. Propiedad Intelectual</a></li>
                <li><a href="#sec-17" class="legal-toc-link">17. Servicios de Terceros</a></li>
                <li><a href="#sec-18" class="legal-toc-link">18. Disponibilidad Técnica</a></li>
                <li><a href="#sec-19" class="legal-toc-link">19. Limitación de Responsabilidad</a></li>
                <li><a href="#sec-20" class="legal-toc-link">20. Modificaciones</a></li>
                <li><a href="#sec-21" class="legal-toc-link">21. Ley y Jurisdicción</a></li>
                <li><a href="#sec-22" class="legal-toc-link">22. Contacto y Reclamaciones</a></li>
            </ul>

            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.08); text-align: center;">
                <a href="{{ route('web.claim_book') }}" style="display: block; background: rgba(255,85,0,0.1); border: 1px solid rgba(255,85,0,0.3); color: #FF5500; font-size: 0.85rem; font-weight: 800; padding: 0.65rem; border-radius: 10px; text-decoration: none;">
                    📖 Libro de Reclamaciones
                </a>
            </div>
        </aside>

        <!-- Main Body -->
        <div class="legal-content-card">
            
            <!-- 1. IDENTIFICACIÓN DE LA EMPRESA -->
            <section id="sec-1" class="legal-section">
                <div class="legal-section-num">
                    <span>1.</span>
                    <span class="legal-section-title">IDENTIFICACIÓN DE LA EMPRESA</span>
                </div>
                <p class="legal-text">
                    <strong>IPEXA S.A.C.</strong>, identificada con RUC N.° <strong>20606476231</strong>, en adelante denominada “IPEXA S.A.C.”, es una persona jurídica constituida conforme a las leyes de la República del Perú, con domicilio en Jr. Parinacochas N.º 11, titular y administradora de la plataforma digital <strong>VIVEGO.PE</strong>, en adelante, la “Plataforma” o “VIVEGO”.
                </p>
                <p class="legal-text">
                    VIVEGO.PE es una plataforma digital mediante la cual los usuarios pueden acceder, según los servicios habilitados, a la publicación, promoción, difusión, compra y venta de entradas para conciertos, espectáculos y otros eventos, así como a la comercialización de productos y otros servicios ofrecidos directamente por IPEXA S.A.C. o por terceros vendedores, organizadores, comercios o empresas que utilicen la Plataforma.
                </p>
                <div class="legal-highlight-box">
                    <strong>Aceptación Plena:</strong> El acceso, registro, navegación o utilización de VIVEGO.PE implica que el usuario declara haber leído, comprendido y aceptado íntegramente los presentes Términos y Condiciones de Uso, así como las políticas, condiciones particulares y demás disposiciones que resulten aplicables. Si el usuario no está de acuerdo con alguno de ellos, deberá abstenerse de utilizar la Plataforma.
                </div>
            </section>

            <!-- 2. ¿QUÉ ES VIVEGO.PE? -->
            <section id="sec-2" class="legal-section">
                <div class="legal-section-num">
                    <span>2.</span>
                    <span class="legal-section-title">¿QUÉ ES VIVEGO.PE?</span>
                </div>
                <p class="legal-text">
                    VIVEGO.PE es una plataforma digital administrada por IPEXA S.A.C., que permite, entre otras funcionalidades:
                </p>
                <ul class="legal-list">
                    <li>Publicar, promocionar y difundir eventos culturales, artísticos, deportivos y recreativos.</li>
                    <li>Comercializar entradas o tickets digitales para conciertos, espectáculos y actividades afines.</li>
                    <li>Permitir que organizadores externos publiquen y comercialicen sus eventos de forma oficial.</li>
                    <li>Facilitar la compra de productos ofrecidos por IPEXA S.A.C. o por terceros vendedores.</li>
                    <li>Procesar y gestionar pagos mediante medios electrónicos habilitados y pasarelas autorizadas.</li>
                    <li>Facilitar la comunicación transparente entre compradores, organizadores, vendedores y la Plataforma.</li>
                </ul>
                <p class="legal-text">
                    Dependiendo del servicio contratado o utilizado, VIVEGO.PE podrá actuar como plataforma tecnológica, intermediaria, canal de promoción, facilitadora de pagos o canal de comercialización, sin que ello implique necesariamente que IPEXA S.A.C. sea la organizadora del evento o la propietaria de los productos ofrecidos por terceros.
                </p>
            </section>

            <!-- 3. COMUNICACIONES ELECTRÓNICAS -->
            <section id="sec-3" class="legal-section">
                <div class="legal-section-num">
                    <span>3.</span>
                    <span class="legal-section-title">COMUNICACIONES ELECTRÓNICAS</span>
                </div>
                <p class="legal-text">
                    Al utilizar VIVEGO.PE, registrarse, realizar una compra o proporcionar un medio de contacto, el Usuario acepta recibir comunicaciones relacionadas con los servicios contratados a través de:
                </p>
                <ul class="legal-list">
                    <li>Correo electrónico oficial de confirmación y emisión de entradas digitales.</li>
                    <li>Mensajes SMS o WhatsApp con alertas de compra, códigos de acceso y cambios en eventos.</li>
                    <li>Notificaciones operativas dentro de la Plataforma y en el Portal del Cliente.</li>
                    <li>Llamadas telefónicas de soporte y validación de seguridad cuando resulte estrictamente necesario.</li>
                </ul>
            </section>

            <!-- 4. DEFINICIONES -->
            <section id="sec-4" class="legal-section">
                <div class="legal-section-num">
                    <span>4.</span>
                    <span class="legal-section-title">DEFINICIONES</span>
                </div>
                <ul class="legal-list">
                    <li><strong>Cliente o Comprador:</strong> Persona natural o jurídica que adquiere una entrada, producto o servicio a través de VIVEGO.PE.</li>
                    <li><strong>Usuario:</strong> Toda persona que accede, navega, se registra o utiliza la Plataforma.</li>
                    <li><strong>Organizador:</strong> Persona natural o jurídica responsable de planificar, organizar, ejecutar y realizar un evento publicado o comercializado.</li>
                    <li><strong>Vendedor:</strong> Persona natural o jurídica que publica, promociona o comercializa productos a través de la Plataforma.</li>
                    <li><strong>Entrada o Ticket:</strong> Documento, código digital o código QR dinámico que acredita el derecho de acceso a un evento.</li>
                    <li><strong>Comisión o Cargo por Servicio:</strong> Importe adicional correspondiente al uso de la infraestructura tecnológica y pasarelas de pago.</li>
                </ul>
            </section>

            <!-- 5. REGISTRO Y CUENTA DE USUARIO -->
            <section id="sec-5" class="legal-section">
                <div class="legal-section-num">
                    <span>5.</span>
                    <span class="legal-section-title">REGISTRO Y CUENTA DE USUARIO</span>
                </div>
                <p class="legal-text">
                    Para comprar boletos o administrar eventos en VIVEGO.PE, el Usuario se compromete a:
                </p>
                <ul class="legal-list">
                    <li>Proporcionar información verdadera, exacta, vigente y verificable (Nombres, DNI/CE, Correo, Teléfono).</li>
                    <li>Custodiar adecuadamente sus credenciales de acceso y no compartirlas con terceros.</li>
                    <li>Informar oportunamente sobre cualquier uso no autorizado de su cuenta.</li>
                    <li>No crear cuentas fraudulentas ni utilizar identidades ajenas sin consentimiento legal.</li>
                </ul>
            </section>

            <!-- 6. PUBLICACIÓN DE EVENTOS POR TERCEROS -->
            <section id="sec-6" class="legal-section">
                <div class="legal-section-num">
                    <span>6.</span>
                    <span class="legal-section-title">PUBLICACIÓN DE EVENTOS Y PRODUCTOS POR TERCEROS</span>
                </div>
                <p class="legal-text">
                    Los Organizadores y Vendedores garantizan contar con las licencias, autorizaciones municipales, contratos artísticos y permisos legales necesarios para la realización de los espectáculos. IPEXA S.A.C. se reserva el derecho de retirar o suspender eventos que incumplan la normativa vigente o las condiciones de seguridad.
                </p>
            </section>

            <!-- 7. COMPRA DE ENTRADAS -->
            <section id="sec-7" class="legal-section">
                <div class="legal-section-num">
                    <span>7.</span>
                    <span class="legal-section-title">COMPRA DE ENTRADAS PARA EVENTOS</span>
                </div>
                <p class="legal-text">
                    Al adquirir entradas en VIVEGO.PE, el Comprador debe revisar con atención los datos del evento (Fecha, Hora, Recinto, Zona, Precio, Restricciones de edad). Una vez procesado el pago con éxito a través de los medios autorizados, se emitirán los boletos digitales con código QR único para su descarga e ingreso al recinto.
                </p>
            </section>

            <!-- 8. RESPONSABILIDAD DEL ORGANIZADOR -->
            <section id="sec-8" class="legal-section">
                <div class="legal-section-num">
                    <span>8.</span>
                    <span class="legal-section-title">RESPONSABILIDAD DEL ORGANIZADOR DEL EVENTO</span>
                </div>
                <p class="legal-text">
                    Salvo que se indique expresamente que IPEXA S.A.C. es el productor directo, el Organizador del evento es el único responsable civil, penal y administrativo de la producción del espectáculo, calidad acústica y escénica, seguridad del recinto, cumplimiento de horarios, aforo, permisos municipales y de la emisión de comprobantes de pago correspondientes.
                </p>
            </section>

            <!-- 9. CAMBIOS, REPROGRAMACIONES Y CANCELACIONES -->
            <section id="sec-9" class="legal-section">
                <div class="legal-section-num">
                    <span>9.</span>
                    <span class="legal-section-title">CAMBIOS, REPROGRAMACIONES Y CANCELACIONES</span>
                </div>
                <p class="legal-text">
                    En caso de modificación de fecha, recinto, artistas o cancelación de un evento por causas de fuerza mayor o decisión del Organizador, se notificará a los compradores a través de correo electrónico y canales oficiales de VIVEGO.PE, informando el procedimiento y plazos para la validez de entradas o solicitudes de devolución conforme a ley.
                </p>
            </section>

            <!-- 10. DEVOLUCIONES Y REEMBOLSOS -->
            <section id="sec-10" class="legal-section">
                <div class="legal-section-num">
                    <span>10.</span>
                    <span class="legal-section-title">DEVOLUCIONES Y REEMBOLSOS DE ENTRADAS</span>
                </div>
                <p class="legal-text">
                    Conforme a la naturaleza del ticketing para espectáculos en vivo, no proceden cambios ni devoluciones una vez concluida la compra, salvo en los supuestos de cancelación definitiva del evento o modificación sustancial de sus condiciones esenciales comunicadas formalmente por el Organizador.
                </p>
            </section>

            <!-- 11. COMPRA DE PRODUCTOS -->
            <section id="sec-11" class="legal-section">
                <div class="legal-section-num">
                    <span>11.</span>
                    <span class="legal-section-title">COMPRA DE PRODUCTOS Y MERCHANDISING</span>
                </div>
                <p class="legal-text">
                    Las compras de productos físicos, merchandising oficial o experiencias complementarias estarán sujetas a los tiempos de entrega, condiciones de stock y políticas de garantía informadas en cada publicación.
                </p>
            </section>

            <!-- 12. PRECIOS, COMISIONES Y MEDIOS DE PAGO -->
            <section id="sec-12" class="legal-section">
                <div class="legal-section-num">
                    <span>12.</span>
                    <span class="legal-section-title">PRECIOS, COMISIONES Y MEDIOS DE PAGO</span>
                </div>
                <p class="legal-text">
                    Todos los precios se encuentran expresados en Soles (S/.) e incluyen los impuestos de ley cuando corresponda. VIVEGO.PE admite pagos mediante tarjetas de crédito, débito, billeteras digitales (Yape, Plin) y pasarelas de pago seguras certificadas con protocolo SSL / PCI-DSS.
                </p>
            </section>

            <!-- 13. NOMINACIÓN Y TRANSFERENCIA -->
            <section id="sec-13" class="legal-section">
                <div class="legal-section-num">
                    <span>13.</span>
                    <span class="legal-section-title">NOMINACIÓN Y TRANSFERENCIA DE ENTRADAS</span>
                </div>
                <p class="legal-text">
                    Para eventos con política de nominación obligatoria por seguridad, las entradas deberán ser registradas con el DNI/documento del asistente final. La reventa no autorizada o comercialización ilegal de boletos está estrictamente prohibida y faculta a la anulación inmediata del ticket sin derecho a reembolso.
                </p>
            </section>

            <!-- 14. CONTROL DE ACCESO Y SEGURIDAD -->
            <section id="sec-14" class="legal-section">
                <div class="legal-section-num">
                    <span>14.</span>
                    <span class="legal-section-title">CONTROL DE ACCESO Y MEDIDAS DE SEGURIDAD</span>
                </div>
                <p class="legal-text">
                    El ingreso al recinto se realizará mediante la validación electrónica del código QR del boleto oficial. Cada código permite un único acceso. El personal de seguridad del Organizador se reserva el derecho de admisión y permanencia conforme a las normas de seguridad pública.
                </p>
            </section>

            <!-- 15. CONDUCTA DEL USUARIO -->
            <section id="sec-15" class="legal-section">
                <div class="legal-section-num">
                    <span>15.</span>
                    <span class="legal-section-title">CONDUCTA DEL USUARIO Y USOS PROHIBIDOS</span>
                </div>
                <p class="legal-text">
                    Queda terminantemente prohibido el uso de bots, scripts automatizados para acaparamiento de entradas, ataques de denegación de servicio, suplantación de identidad o cualquier conducta que vulnere la integridad de la Plataforma.
                </p>
            </section>

            <!-- 16. PROPIEDAD INTELECTUAL -->
            <section id="sec-16" class="legal-section">
                <div class="legal-section-num">
                    <span>16.</span>
                    <span class="legal-section-title">PROPIEDAD INTELECTUAL</span>
                </div>
                <p class="legal-text">
                    Las marcas, logotipos, diseños, código fuente, interfaz visual y contenidos de <strong>VIVEGO.PE</strong> son propiedad exclusiva de IPEXA S.A.C. o cuentan con las respectivas licencias de uso, protegidas por las leyes de propiedad intelectual peruanas e internacionales.
                </p>
            </section>

            <!-- 17. SERVICIOS DE TERCEROS -->
            <section id="sec-17" class="legal-section">
                <div class="legal-section-num">
                    <span>17.</span>
                    <span class="legal-section-title">ENLACES Y SERVICIOS DE TERCEROS</span>
                </div>
                <p class="legal-text">
                    La Plataforma puede contener enlaces hacia sitios de terceros o integrar procesadores de pago independientes. IPEXA S.A.C. no se responsabiliza por las políticas o contenidos de sitios externos fuera de su control.
                </p>
            </section>

            <!-- 18. DISPONIBILIDAD TÉCNICA -->
            <section id="sec-18" class="legal-section">
                <div class="legal-section-num">
                    <span>18.</span>
                    <span class="legal-section-title">DISPONIBILIDAD DE LA PLATAFORMA Y FALLAS TÉCNICAS</span>
                </div>
                <p class="legal-text">
                    IPEXA S.A.C. realiza sus mejores esfuerzos técnicos para garantizar la máxima disponibilidad de VIVEGO.PE; no obstante, no garantiza la ausencia total de interrupciones derivadas de mantenimiento, cortes de conectividad de telecomunicaciones o incidencias de pasarelas de pago externas.
                </p>
            </section>

            <!-- 19. LIMITACIÓN DE RESPONSABILIDAD -->
            <section id="sec-19" class="legal-section">
                <div class="legal-section-num">
                    <span>19.</span>
                    <span class="legal-section-title">LIMITACIÓN DE RESPONSABILIDAD</span>
                </div>
                <p class="legal-text">
                    En la máxima medida permitida por las leyes peruanas, la responsabilidad de IPEXA S.A.C. frente al Comprador por fallas imputables a su servicio directo se limitará al monto efectivamente pagado por el cargo por servicio de la entrada o transacción objeto del reclamo.
                </p>
            </section>

            <!-- 20. MODIFICACIONES -->
            <section id="sec-20" class="legal-section">
                <div class="legal-section-num">
                    <span>20.</span>
                    <span class="legal-section-title">MODIFICACIONES A LOS TÉRMINOS Y CONDICIONES</span>
                </div>
                <p class="legal-text">
                    IPEXA S.A.C. podrá actualizar o modificar estos Términos y Condiciones en cualquier momento para adecuarlos a mejoras en el servicio o cambios normativos. La versión vigente estará permanentemente publicada en este enlace con su fecha de actualización.
                </p>
            </section>

            <!-- 21. LEY APLICABLE Y JURISDICCIÓN -->
            <section id="sec-21" class="legal-section">
                <div class="legal-section-num">
                    <span>21.</span>
                    <span class="legal-section-title">LEY APLICABLE Y SOLUCIÓN DE CONTROVERSIAS</span>
                </div>
                <p class="legal-text">
                    Los presentes Términos y Condiciones se rigen e interpretan conforme a las leyes de la República del Perú. Cualquier controversia será sometida a la competencia de los jueces y tribunales del distrito judicial de Lima, Perú.
                </p>
            </section>

            <!-- 22. CONTACTO Y LIBRO DE RECLAMACIONES -->
            <section id="sec-22" class="legal-section">
                <div class="legal-section-num">
                    <span>22.</span>
                    <span class="legal-section-title">CANALES DE CONTACTO Y LIBRO DE RECLAMACIONES</span>
                </div>
                <p class="legal-text">
                    Para consultas, dudas o soporte técnico respecto al uso de la Plataforma, puedes contactarnos a través de nuestros canales oficiales:
                </p>
                <ul class="legal-list">
                    <li><strong>Razón Social:</strong> IPEXA S.A.C. (RUC 20606476231)</li>
                    <li><strong>Dirección:</strong> Jr. Parinacochas N.º 11, Lima, Perú</li>
                    <li><strong>Plataforma:</strong> <a href="{{ route('web.home') }}" style="color: #FF5500; font-weight: 700;">www.vivego.pe</a></li>
                    <li><strong>Libro de Reclamaciones Virtual:</strong> Disponible las 24 horas en <a href="{{ route('web.claim_book') }}" style="color: #FF5500; font-weight: 700;">vivego.pe/libro-de-reclamaciones</a> conforme a la Ley N.° 29571.</li>
                </ul>
            </section>

        </div>
    </div>
</div>
@endsection
