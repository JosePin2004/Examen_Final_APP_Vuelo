<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>App Vuelo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/js/app.js'])
    <style>
        body { background: radial-gradient(circle at 20% 20%, rgba(255,45,32,0.25), transparent 35%), linear-gradient(135deg, #0b0b0f 0%, #12121c 60%, #0b0b0f 100%); }
    </style>
</head>
<body class="text-white min-h-screen font-sans">
    <header class="sticky top-0 z-40 bg-black/50 backdrop-blur border-b border-white/10">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2 text-xl font-semibold">
                <svg class="w-7 h-7 text-red-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.8 7.6 12.9 3c-.57-.32-1.27-.32-1.84 0L3.2 7.6c-.57.32-.92.93-.92 1.59v5.62c0 .66.35 1.27.92 1.59l7.86 4.6c.57.33 1.27.33 1.84 0l7.9-4.6c.57-.32.92-.93.92-1.59V9.19c0-.66-.35-1.27-.92-1.59ZM12 13.6l-7.1-4.15L12 5.3l7.12 4.15L12 13.6Zm-.92 2.37-6.48-3.78v-1.9l6.48 3.78v1.9Zm1.84 0v-1.9l6.48-3.78v1.9l-6.48 3.78Z"/></svg>
                <span>App Vuelo</span>
            </div>
            <nav class="flex items-center gap-4 text-sm font-semibold">
                @auth
                    <a class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 transition" href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a class="px-4 py-2 rounded-lg border border-red-500 text-red-300 hover:bg-red-600 hover:border-red-600 hover:text-white transition" href="{{ route('login') }}">Log in</a>
                    @if (Route::has('register'))
                        <a class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 transition" href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-14 space-y-12">
        <section class="flex flex-col lg:flex-row gap-10 items-center">
            <div class="flex-1 space-y-6">
                <p class="text-sm uppercase tracking-[0.3em] text-red-400">Catálogo en línea</p>
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">Encuentra tu próximo vuelo y reserva en minutos</h1>
                <p class="text-lg text-gray-300 max-w-2xl">Consulta horarios, destinos y precios en tiempo real. Regístrate para crear y gestionar tus reservas con autenticación segura.</p>
                <div class="flex flex-wrap gap-4 pt-2">
                    <a href="#catalogo" class="px-6 py-3 rounded-lg bg-red-600 hover:bg-red-700 font-semibold">Ver catálogo</a>
                    @guest
                        <a href="{{ route('login') }}" class="px-6 py-3 rounded-lg border border-white/20 hover:border-red-400 hover:text-red-300 font-semibold transition">Iniciar sesión</a>
                    @endguest
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-3 rounded-lg border border-white/20 hover:border-red-400 hover:text-red-300 font-semibold transition">Ir al Dashboard</a>
                    @endauth
                </div>
            </div>
            <div class="flex-1 w-full">
                <div class="rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-black border border-white/10 p-6 shadow-xl">
                    <div class="flex items-center gap-3 mb-4 text-red-400 font-semibold">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M2 12h20M12 2l4 4-4 4M12 22l4-4-4-4"/></svg>
                        <span>Vista rápida</span>
                    </div>
                    <p class="text-gray-200 text-sm leading-relaxed mb-4">Previsualiza algunos vuelos disponibles. Los datos vienen de la API pública de vuelos.</p>
                    <div id="preview-flights" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="rounded-lg border border-white/10 bg-white/5 p-4 text-gray-300">Cargando vuelos...</div>
                    </div>
                </div>
            </div>
        </section>

        <section id="catalogo" class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-bold">Catálogo de vuelos</h2>
                <span class="text-sm text-gray-400">Datos en vivo desde /api/flights</span>
            </div>
            <div id="catalog-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="rounded-xl bg-white/5 border border-white/10 p-5 text-gray-300">Cargando catálogo...</div>
            </div>
        </section>
    </main>

    <footer class="border-t border-white/10 bg-black/40 backdrop-blur">
        <div class="max-w-6xl mx-auto px-4 py-6 text-sm text-gray-400 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</span>
            <span>App Vuelo · Catálogo y reservas</span>
        </div>
    </footer>

    <script>
        // CUANDO LA PÁGINA CARGUE → OBTENER VUELOS
        document.addEventListener('DOMContentLoaded', () => {
            loadFlights();
        });

        // OBTENER ESTADO DE AUTENTICACIÓN DEL SERVIDOR
        const isAuth = {{ auth()->check() ? 'true' : 'false' }};
        const dashboardUrl = '{{ url('/dashboard') }}';
        const loginUrl = '{{ route('login') }}';

        function formatDate(dateString) {
            if (!dateString) return 'Fecha no disponible';
            // Formato directo ISO: YYYY-MM-DDTHH:mm[:ss]
            const parts = String(dateString).split('T');
            if (parts.length < 2) return 'Fecha no disponible';
            const [y,m,d] = parts[0].split('-');
            const hhmm = parts[1].substring(0,5);
            return `${d}/${m}/${y}, ${hhmm}`;
        }

        async function loadFlights() {
            const previewContainer = document.getElementById('preview-flights');
            const catalogContainer = document.getElementById('catalog-grid');

            try {
                // GET /api/flights - Obtener todos los vuelos del backend
                const response = await fetch('/api/flights');
                const data = await response.json();
                const flights = data.data || data || [];

                // Renderizar preview (primeros 4 vuelos) y catálogo completo
                renderPreview(previewContainer, flights.slice(0, 4));
                renderCatalog(catalogContainer, flights);
            } catch (error) {
                // SI HAY ERROR → Mostrar mensaje en ambos contenedores
                previewContainer.innerHTML = `<div class="rounded-lg border border-red-500/50 bg-red-500/10 p-4 text-red-200">No se pudieron cargar los vuelos.</div>`;
                catalogContainer.innerHTML = `<div class="rounded-lg border border-red-500/50 bg-red-500/10 p-4 text-red-200">No se pudo cargar el catálogo.</div>`;
                console.error('Error cargando vuelos:', error);
            }
        }

        function renderPreview(container, flights) {
            container.innerHTML = '';

            if (!flights.length) {
                container.innerHTML = '<div class="rounded-lg border border-white/10 bg-white/5 p-4 text-gray-300">No hay vuelos disponibles.</div>';
                return;
            }

            // RENDERIZAR CADA VUELO EN TARJETA
            flights.forEach(flight => {
                const card = document.createElement('div');
                card.className = 'rounded-lg border border-white/10 bg-white/5 p-4 text-gray-200 shadow-sm hover:border-red-400/60 transition';
                const seatsInfo = (flight.economy_seats ?? null) !== null && (flight.business_seats ?? null) !== null
                    ? `Turista: ${flight.economy_seats} | Ejecutivo: ${flight.business_seats}`
                    : (flight.seats ?? '—');
                const economyPrice = Number(flight.economy_price ?? flight.price ?? 0).toFixed(2);
                const businessPrice = Number(flight.business_price ?? flight.price ?? 0).toFixed(2);
                card.innerHTML = `
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-400">${flight.code || 'Vuelo'}</span>
                        <span class="text-xs px-2 py-1 rounded-full bg-red-500/20 text-red-300">${seatsInfo}</span>
                    </div>
                    <div class="text-lg font-semibold">${flight.origin} → ${flight.destination}</div>
                    <div class="text-sm text-gray-400 mt-1">Salida: ${formatDate(flight.departure_time)}</div>
                    <div class="text-sm text-gray-400">Llegada: ${formatDate(flight.arrival_time)}</div>
                    <div class="grid grid-cols-2 gap-3 mt-3">
                        <div class="rounded bg-red-500/10 border border-red-500/20 p-2">
                            <div class="text-xs text-red-200">✈️ Turista</div>
                            <div class="text-lg font-bold text-red-300">$${economyPrice}</div>
                        </div>
                        <div class="rounded bg-green-500/10 border border-green-500/20 p-2">
                            <div class="text-xs text-green-200">👔 Ejecutivo</div>
                            <div class="text-lg font-bold text-green-300">$${businessPrice}</div>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-300" id="weather-preview-${flight.id}">
                        <button class="px-3 py-1 rounded bg-red-600 hover:bg-red-700 text-white text-xs font-semibold" onclick="loadWeather(${flight.id}, 'weather-preview-${flight.id}')">Ver clima destino</button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function renderCatalog(container, flights) {
            container.innerHTML = '';

            if (!flights.length) {
                container.innerHTML = '<div class="rounded-xl bg-white/5 border border-white/10 p-5 text-gray-300">No hay vuelos disponibles.</div>';
                return;
            }

            flights.forEach(flight => {
                const card = document.createElement('div');
                card.className = 'rounded-xl bg_white/5 border border_white/10 p-5 space-y-3 shadow-sm hover_border-red-400/60 transition'.replace(/_/g,'');
                const economyPrice = Number(flight.economy_price ?? flight.price ?? 0).toFixed(2);
                const businessPrice = Number(flight.business_price ?? flight.price ?? 0).toFixed(2);
                card.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="text-lg font-semibold">${flight.origin} → ${flight.destination}</div>
                        <span class="text-xs px-3 py-1 rounded-full bg-red-500/15 text-red-300 border border-red-500/20">${flight.code || 'Vuelo'}</span>
                    </div>
                    <div class="text-sm text-gray-300">Salida: ${formatDate(flight.departure_time)}</div>
                    <div class="text-sm text-gray-300">Llegada: ${formatDate(flight.arrival_time)}</div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded bg-red-500/10 border border-red-500/20 p-3">
                            <div class="text-xs text-red-200">✈️ Turista</div>
                            <div class="text-xl font-bold text-red-300">$${economyPrice}</div>
                        </div>
                        <div class="rounded bg-green-500/10 border border-green-500/20 p-3">
                            <div class="text-xs text-green-200">👔 Ejecutivo</div>
                            <div class="text-xl font-bold text-green-300">$${businessPrice}</div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-300" id="weather-catalog-${flight.id}">
                        <button class="px-3 py-1 rounded bg-red-600 hover:bg-red-700 text-white text-xs font-semibold" onclick="loadWeather(${flight.id}, 'weather-catalog-${flight.id}')">Ver clima destino</button>
                    </div>
                    <button class="w-full px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 font-semibold" onclick="handleReservation(${flight.id})">Reservar</button>
                `;
                container.appendChild(card);
            });
        }

        async function loadWeather(flightId, targetId) {
            const target = document.getElementById(targetId);
            if (!target) return;
            target.textContent = 'Cargando clima...';

            try {
                // GET /api/flights/{id}/weather - Obtener clima del destino
                const response = await fetch(`/api/flights/${flightId}/weather`);
                const data = await response.json();

                if (!response.ok || !data.success) {
                    target.textContent = data.message || 'No se pudo obtener el clima.';
                    return;
                }

                // MOSTRAR CLIMA: temperatura, descripción, humedad
                const w = data.data;
                target.innerHTML = `
                    <span class="font-semibold text-red-200">${w.temp_c ?? '?'}°C</span>
                    · ${w.description || 'Clima no disponible'}
                    · Humedad ${w.humidity ?? '?'}%
                `;
            } catch (error) {
                target.textContent = 'No se pudo obtener el clima.';
                console.error('Error cargando clima:', error);
            }
        }

        function handleReservation(flightId) {
            // SI ESTÁ AUTENTICADO → Redirigir al dashboard
            // SI NO → Redirigir al login
            if (isAuth) {
                window.location.href = `${dashboardUrl}?flight=${flightId}`;
            } else {
                window.location.href = loginUrl;
            }
        }
    </script>
</body>
</html>
