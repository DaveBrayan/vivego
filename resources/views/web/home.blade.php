@extends('layouts.app')

@section('title', 'Vive Go - VIVE CADA MOMENTO | Plataforma Oficial de Eventos')

@section('content')
<!-- Hero Stage Principal: Slider Hero 70% + Tarjetas Destacadas Laterales 30% -->
<section class="hero-wide-container">
    <div class="hero-wide-grid">
        <!-- Columna Izquierda: Slider Hero Principal -->
        <div class="hero-carousel-curved" id="heroCarousel">
            @foreach($heroEvents as $index => $hero)
                <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                    <img src="{{ $hero['image'] }}" alt="{{ $hero['title'] }}">
                    <div class="carousel-overlay">
                        <div class="carousel-caption">
                            <div class="hero-live-pill">
                                <span class="live-pulse-dot"></span>
                                <span>{{ $hero['badge'] }} · ENTRADAS OFICIALES EN VIVO</span>
                            </div>
                            <h2>{{ $hero['title'] }}</h2>
                            <div class="carousel-meta-row">
                                <div class="carousel-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <span>{{ $hero['date'] }}</span>
                                </div>
                                <div class="carousel-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span>{{ $hero['venue'] }}</span>
                                </div>
                                <div class="carousel-price-tag">
                                    Desde S/ {{ $hero['price'] }}
                                </div>
                            </div>
                            <div class="hero-slide-actions">
                                <a href="{{ route('web.event.detail', ['slug' => $hero['slug']]) }}" class="btn btn-primary btn-hero-glow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                                        <circle cx="12" cy="12" r="2"></circle>
                                    </svg>
                                    Comprar Entradas Ahora
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Navigation Arrows -->
            <button class="carousel-arrow prev" id="carouselPrev" aria-label="Anterior">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            <button class="carousel-arrow next" id="carouselNext" aria-label="Siguiente">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>

            <!-- Navigation Dots -->
            <div class="carousel-dots">
                @foreach($heroEvents as $index => $hero)
                    <div class="carousel-dot {{ $index === 0 ? 'active' : '' }}" data-dot="{{ $index }}"></div>
                @endforeach
            </div>
        </div>

        <!-- Columna Derecha: Tarjetas Destacadas al Costado -->
        <div class="hero-side-cards">
            @foreach($sideEvents as $side)
                <div class="hero-side-card">
                    <img src="{{ $side['image'] }}" alt="{{ $side['title'] }}">
                    <div class="side-card-overlay">
                        <span class="badge {{ $side['badge_color'] ?? 'badge-red' }} side-card-badge">🔥 {{ $side['badge'] }}</span>
                        <h3 class="side-card-title">{{ $side['title'] }}</h3>
                        <div class="side-card-meta">
                            <span>📍 {{ $side['venue'] }}</span>
                            <span class="side-card-price">S/ {{ $side['price'] }}</span>
                        </div>
                        <div class="side-card-progress">
                            <span>{{ $side['sold_percent'] ?? '88%' }} Vendido</span>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ $side['sold_percent'] ?? '88%' }};"></div>
                            </div>
                        </div>
                        <a href="{{ route('web.event.detail', ['slug' => $side['slug']]) }}" class="btn btn-primary btn-sm" style="width: 100%;">
                            Comprar Entradas
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<div class="container">
    <!-- Barra de Filtros Rápidos (Cápsulas Centradas) -->
    <section class="filters-section">
        <div class="filters-bar" id="filtersBar">
            <button class="filter-capsule active" data-filter="all">✨ Todos los Eventos</button>
            <button class="filter-capsule" data-filter="CONCIERTO">🎤 Conciertos</button>
            <button class="filter-capsule" data-filter="FESTIVAL">🎪 Festivales</button>
            <button class="filter-capsule" data-filter="TEATRO">🎭 Teatro & Cultura</button>
            <button class="filter-capsule" data-filter="FIESTA">🎉 Fiestas & Clubes</button>
            <button class="filter-capsule" data-filter="DEPORTE">⚽ Deportes</button>
        </div>
    </section>

    <!-- Grilla de Eventos Principal Bento V7 Pro Max -->
    <section id="eventos-grid">
        <div class="section-header" style="text-align: center; display: block; margin-bottom: 2.75rem;">
            <h2 style="font-size: 2.5rem; font-weight: 900; font-family: var(--font-heading);">Próximos Eventos Masivos</h2>
            <p style="color: var(--text-secondary); font-size: 1.1rem; font-weight: 500;">Descubre los mejores conciertos, festivales y experiencias únicas en tu ciudad.</p>
        </div>

        <div class="events-grid-4col" id="eventsContainer">
            @foreach($events as $event)
                <article class="event-card-v6" data-category="{{ $event['category'] }}">
                    <div class="event-card-v6-media">
                        <!-- Holographic Badge Top Right -->
                        <div class="badge-v6-holographic">
                            <span>{{ $event['badge'] }}</span>
                        </div>

                        <!-- Date Stage 3D Glassmorphic Bottom Left -->
                        <div class="date-stage-v6">
                            <span class="date-stage-day">{{ $event['day'] ?? '15' }}</span>
                            <div class="date-stage-details">
                                <span class="date-stage-month">{{ $event['month'] ?? 'AGO' }}</span>
                                <span class="date-stage-time">{{ $event['time'] ?? '20:00 HRS' }}</span>
                            </div>
                        </div>

                        <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}">
                    </div>
                    
                    <div class="event-card-v6-body">
                        <div class="event-v6-category-tag">
                            {{ $event['category'] ?? 'EVENTO' }}
                        </div>

                        <h3 class="event-card-v6-title">{{ $event['title'] }}</h3>

                        <div class="event-v6-venue">
                            <div class="event-v6-venue-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <span>{{ $event['venue'] }}</span>
                        </div>

                        <div class="event-card-v6-footer">
                            <div class="event-v6-price-group">
                                <span class="event-v6-price-label">Desde</span>
                                <span class="event-v6-price-value">S/ {{ $event['price'] }}</span>
                            </div>
                            <a href="{{ route('web.event.detail', ['slug' => $event['slug']]) }}" class="btn-buy-v6">
                                <span>Ver Entradas</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <!-- SECCIÓN: VALORES AGREGADOS Y SEGURIDAD DIGITAL -->
    <section class="trust-benefits-grid">
        <div class="trust-card">
            <div class="trust-icon-box">📱</div>
            <h3>Nominación Digital en Vivo</h3>
            <p>Protege tus entradas contra la reventa. Asigna el DNI o Pasaporte del asistente directamente desde tu cuenta.</p>
        </div>
        <div class="trust-card">
            <div class="trust-icon-box">⚡</div>
            <h3>Pagos Rápidos con Yape y Plin</h3>
            <p>Completa tu compra en segundos mediante código QR sin comisiones ocultas y con emisión instantánea de e-ticket.</p>
        </div>
        <div class="trust-card">
            <div class="trust-icon-box">🛡️</div>
            <h3>Garantía Anti-Fraude QR</h3>
            <p>Cada entrada cuenta con código QR dinámico de alta seguridad y validación oficial en los accesos del recinto.</p>
        </div>
    </section>

    <!-- SECCIÓN INTERESES CON EL TEMA DE VIVE GO -->
    <section class="interests-section-theme">
        <div class="interests-header-theme">
            <div class="interests-header-subtitle-theme">EXPLORA TUS PASIONES</div>
            <h2 class="interests-header-title-theme">DESCUBRE TUS <span class="gradient-text">INTERESES</span></h2>
        </div>

        <div class="interests-grid-theme" id="interestsGrid">
            <div class="interest-item-theme">
                <div class="interest-icon-box-theme">🏛️</div>
                <span class="interest-label-theme">Arte & Cultura</span>
            </div>
            <div class="interest-item-theme">
                <div class="interest-icon-box-theme">🍔</div>
                <span class="interest-label-theme">Comidas & Bebidas</span>
            </div>
            <div class="interest-item-theme">
                <div class="interest-icon-box-theme">🎸</div>
                <span class="interest-label-theme">Conciertos</span>
            </div>
            <div class="interest-item-theme">
                <div class="interest-icon-box-theme">📚</div>
                <span class="interest-label-theme">Cursos y talleres</span>
            </div>
            <div class="interest-item-theme">
                <div class="interest-icon-box-theme">👟</div>
                <span class="interest-label-theme">Deportes</span>
            </div>
            <div class="interest-item-theme">
                <div class="interest-icon-box-theme">🎪</div>
                <span class="interest-label-theme">Entretenimiento</span>
            </div>
            <div class="interest-item-theme">
                <div class="interest-icon-box-theme">🎪</div>
                <span class="interest-label-theme">Festivales</span>
            </div>

            <!-- Extra Colapsables -->
            <div class="interest-item-theme interest-extra">
                <div class="interest-icon-box-theme">🪩</div>
                <span class="interest-label-theme">Fiestas</span>
            </div>
            <div class="interest-item-theme interest-extra">
                <div class="interest-icon-box-theme">⚽</div>
                <span class="interest-label-theme">Fútbol</span>
            </div>
            <div class="interest-item-theme interest-extra">
                <div class="interest-icon-box-theme">🛝</div>
                <span class="interest-label-theme">Niños</span>
            </div>
            <div class="interest-item-theme interest-extra">
                <div class="interest-icon-box-theme">🏛️</div>
                <span class="interest-label-theme">Seminarios</span>
            </div>
            <div class="interest-item-theme interest-extra">
                <div class="interest-icon-box-theme">🎙️</div>
                <span class="interest-label-theme">Stand up</span>
            </div>
            <div class="interest-item-theme interest-extra">
                <div class="interest-icon-box-theme">🎭</div>
                <span class="interest-label-theme">Teatro</span>
            </div>
            <div class="interest-item-theme interest-extra">
                <div class="interest-icon-box-theme">🎈</div>
                <span class="interest-label-theme">Aventuras</span>
            </div>
        </div>

        <div class="interests-toggle-container-theme">
            <button class="btn-toggle-interests-theme" id="btnToggleInterests">
                <span id="toggleInterestsText">Ver todas las categorías</span>
                <span id="toggleInterestsIcon">▼</span>
            </button>
        </div>
    </section>

    <!-- SECCIÓN: BANNER ORGANIZADORES CON IMAGEN DE FONDO (V2 PRO MAX) -->
    <section class="organizers-section-container">
        <div class="organizers-cta-banner-v2">
            <!-- Imagen de Fondo Atmosférica de Conciertos/Festivales -->
            <div class="organizers-banner-bg-img" style="background-image: url('https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1920&q=80');"></div>
            <div class="organizers-banner-overlay-v2"></div>

            <div class="organizers-cta-grid-v2">
                <div class="organizers-cta-content-v2">
                    <span class="organizers-pill-badge">⚡ SOLUCIÓN PARA ORGANIZADORES</span>
                    <h2 class="organizers-title-v2">¿Tienes un evento masivo o corporativo?</h2>
                    <p class="organizers-subtitle-v2">Crea tu ticketera oficial en minutos. Vende entradas nominadas en tiempo real, gestiona RRPP y controla el acceso en puerta con nuestra tecnología inteligente.</p>

                    <div class="organizers-stats-row">
                        <div class="stat-item-v2">
                            <span class="stat-number">+8,000</span>
                            <span class="stat-label">Eventos Creados</span>
                        </div>
                        <div class="stat-item-v2">
                            <span class="stat-number">100%</span>
                            <span class="stat-label">Nominación Digital</span>
                        </div>
                        <div class="stat-item-v2">
                            <span class="stat-number">⚡ 3 seg</span>
                            <span class="stat-label">Escaneo QR Puerta</span>
                        </div>
                    </div>

                    <div class="organizers-actions-v2">
                        <a href="{{ route('web.events.create') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            ¡Publica tu Evento Gratis!
                        </a>
                        <a href="{{ route('web.events') }}" class="btn btn-glass-white">
                            Gestionar Mis Eventos
                        </a>
                    </div>
                </div>

                <div class="organizers-glass-card-v2">
                    <div class="glass-card-header">
                        <div class="glass-card-icon">🚀</div>
                        <h3>Panel SaaS 360°</h3>
                    </div>
                    <ul class="glass-feature-list">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Zonas VIP, Generales y Box numerados</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Liquidación de ventas a tu cuenta bancaria</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>App Móvil de Escaneo en Puerta</span>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FF5500" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Integración con Yape, Plin y Tarjetas</span>
                        </li>
                    </ul>
                    <a href="{{ route('web.dashboard') }}" class="btn btn-primary btn-sm" style="width: 100%;">
                        Probar Demo del Panel ➔
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Carousel Auto Slider
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.carousel-dot');
    const prevBtn = document.getElementById('carouselPrev');
    const nextBtn = document.getElementById('carouselNext');
    let currentSlide = 0;
    let slideInterval = null;

    function showSlide(index) {
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));
        if (slides[index]) slides[index].classList.add('active');
        if (dots[index]) dots[index].classList.add('active');
        currentSlide = index;
    }

    function nextSlide() {
        if (slides.length <= 1) return;
        let index = (currentSlide + 1) % slides.length;
        showSlide(index);
    }

    function prevSlide() {
        if (slides.length <= 1) return;
        let index = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(index);
    }

    if (slides.length > 1) {
        slideInterval = setInterval(nextSlide, 6000);
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (slideInterval) clearInterval(slideInterval);
            nextSlide();
            if (slides.length > 1) slideInterval = setInterval(nextSlide, 6000);
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (slideInterval) clearInterval(slideInterval);
            prevSlide();
            if (slides.length > 1) slideInterval = setInterval(nextSlide, 6000);
        });
    }

    dots.forEach((dot, idx) => {
        dot.addEventListener('click', function() {
            if (slideInterval) clearInterval(slideInterval);
            showSlide(idx);
            if (slides.length > 1) slideInterval = setInterval(nextSlide, 6000);
        });
    });

    // Interests Toggle Logic
    const toggleBtn = document.getElementById('btnToggleInterests');
    const extras = document.querySelectorAll('.interest-extra');
    const toggleText = document.getElementById('toggleInterestsText');
    const toggleIcon = document.getElementById('toggleInterestsIcon');
    let expanded = false;

    extras.forEach(e => e.style.display = 'none');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            expanded = !expanded;
            extras.forEach(e => e.style.display = expanded ? 'flex' : 'none');
            toggleText.textContent = expanded ? 'Mostrar menos' : 'Ver todas las categorías';
            toggleIcon.textContent = expanded ? '▲' : '▼';
        });
    }

    // Filter capsules interactive filtering
    const capsules = document.querySelectorAll('.filter-capsule');
    const eventCards = document.querySelectorAll('.event-card-v6');

    capsules.forEach(c => {
        c.addEventListener('click', function() {
            capsules.forEach(cap => cap.classList.remove('active'));
            this.classList.add('active');

            const filterVal = (this.getAttribute('data-filter') || 'all').toUpperCase();

            eventCards.forEach(card => {
                const cardCat = (card.getAttribute('data-category') || '').toUpperCase();
                if (filterVal === 'ALL' || cardCat.includes(filterVal)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endpush