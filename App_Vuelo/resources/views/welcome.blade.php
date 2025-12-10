<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>App Vuelos - Catálogo de Vuelos</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-black dark:text-white/50">
        <div class="min-h-screen">
            <!-- Header -->
            <header class="bg-white dark:bg-zinc-900 shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex justify-between items-center">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">✈️ Catálogo de Vuelos</h1>
                        <nav class="flex gap-4">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Dashboard</a>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Logout</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Iniciar sesión</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Registrarse</a>
                                    @endif
                                @endauth
                            @endif
                        </nav>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div id="flights-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Los vuelos se cargarán aquí -->
                </div>
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                loadFlightsCatalog();
            });

            function loadFlightsCatalog() {
                fetch('/api/flights')
                    .then(response => response.json())
                    .then(data => {
                        console.log('📋 Vuelos cargados:', data);
                        const flights = data.data || data || [];
                        renderFlightsCatalog(flights);
                    })
                    .catch(error => console.error('❌ Error cargando vuelos:', error));
            }

            function renderFlightsCatalog(flights) {
                const container = document.getElementById('flights-container');
                container.innerHTML = '';

                if (flights.length === 0) {
                    container.innerHTML = '<p class="col-span-full text-center text-gray-500">No hay vuelos disponibles</p>';
                    return;
                }

                flights.forEach(flight => {
                    const departureTime = new Date(flight.departure_time).toLocaleString('es-ES');
                    const arrivalTime = new Date(flight.arrival_time).toLocaleString('es-ES');
                    
                    const card = document.createElement('div');
                    card.className = 'bg-white dark:bg-zinc-900 rounded-lg shadow-lg hover:shadow-xl transition overflow-hidden';
                    card.innerHTML = `
                        <div class="relative h-48 bg-gradient-to-br from-blue-400 to-blue-600 overflow-hidden">
                            ${flight.image_url ? `<img src="${flight.image_url}" alt="${flight.origin}" class="w-full h-full object-cover">` : ''}
                            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-end">
                                <div class="w-full p-4 text-white">
                                    <div class="flex justify-between items-center text-2xl font-bold">
                                        <span>${flight.origin}</span>
                                        <span>→</span>
                                        <span>${flight.destination}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3 mb-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Salida</p>
                                    <p class="text-gray-900 dark:text-white font-semibold">${departureTime}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Llegada</p>
                                    <p class="text-gray-900 dark:text-white font-semibold">${arrivalTime}</p>
                                </div>
                                <div class="border-t pt-3">
                                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">$${parseFloat(flight.price).toFixed(2)}</p>
                                </div>
                            </div>
                            <button 
                                onclick="handleReservation(${flight.id})"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition"
                            >
                                Seleccionar Vuelo
                            </button>
                        </div>
                    `;
                    container.appendChild(card);
                });
            }

            function handleReservation(flightId) {
                const token = localStorage.getItem('auth_token');
                
                if (!token) {
                    // No está autenticado - redirigir a login
                    window.location.href = '{{ route("login") }}';
                    return;
                }

                // Si está autenticado, ir al dashboard para hacer la reserva
                window.location.href = '{{ url("/dashboard") }}?flight=' + flightId;
            }
        </script>
    </body>
</html>
