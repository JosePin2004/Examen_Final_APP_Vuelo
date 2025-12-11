<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Vuelos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-blue-600 p-4 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold flex items-center gap-2">
                App Vuelo
            </h1>
            <div class="flex gap-4 items-center">
                <a href="/admin" id="adminLink" class="hidden bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded text-sm font-bold transition shadow">
                    Panel Admin
                </a>
                <button onclick="logout()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm font-bold transition shadow">
                    Cerrar Sesión
                </button>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto mt-8 p-6">
        
        <!-- TABS -->
        <div class="flex gap-4 mb-8 border-b border-gray-300">
            <button onclick="showTab('catalog')" class="tab-btn active px-6 py-3 font-bold text-blue-600 border-b-2 border-blue-600">
                Catálogo de Vuelos
            </button>
            <button onclick="showTab('reservations')" class="tab-btn px-6 py-3 font-bold text-gray-600 hover:text-blue-600">
                Mis Reservaciones
            </button>
        </div>

        <!-- TAB: CATÁLOGO -->
        <div id="catalog-tab" class="tab-content block">
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Catálogo de Vuelos Disponibles</h2>
                <div id="flights-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="text-center text-gray-500 py-10 col-span-3">Cargando vuelos...</div>
                </div>
            </div>
        </div>

        <!-- TAB: RESERVACIONES -->
        <div id="reservations-tab" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- FORMULARIO CREAR RESERVA -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Nueva Reserva</h2>
                        <form id="createForm" class="space-y-4">
                            <div>
                                <label class="block text-gray-600 text-sm font-bold mb-2">ID del Vuelo</label>
                                <input type="number" id="flight_id" 
                                       class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                                       placeholder="Ej: 1" required>
                                <p class="text-xs text-gray-400 mt-1">Ingresa el ID de un vuelo existente</p>
                            </div>

                            <div>
                                <label class="block text-gray-600 text-sm font-bold mb-2">Clase de Vuelo</label>
                                <select id="seat_class" 
                                        class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                                        required onchange="updateSeatOptions()">
                                    <option value="">Selecciona una clase</option>
                                    <option value="economy">✈️ Turista</option>
                                    <option value="business">👑 Ejecutivo</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-600 text-sm font-bold mb-2">Número de Asiento</label>
                                <select id="seat_number" 
                                        class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                                        required disabled>
                                    <option value="">Selecciona clase primero</option>
                                </select>
                            </div>

                            <div id="price-display" class="bg-blue-50 rounded-lg p-3 text-center">
                                <p class="text-sm text-gray-600">Precio:</p>
                                <p class="text-2xl font-bold text-blue-600">-</p>
                            </div>

                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition shadow-md flex justify-center items-center gap-2">
                                <span>+</span> Reservar Vuelo
                            </button>
                        </form>
                    </div>
                </div>

                <!-- MIS RESERVACIONES -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Mis Reservaciones</h2>
                            <button onclick="loadReservations()" class="text-blue-500 text-sm hover:underline">🔄 Actualizar</button>
                        </div>
                        
                        <div id="reservas-container" class="space-y-4">
                            <p class="text-gray-500 text-center py-10">Cargando...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('token');
        if (!token) window.location.href = '/login';

        // CARGAR AL INICIO
        document.addEventListener('DOMContentLoaded', () => {
            checkIfAdmin();
            loadFlights();
            loadReservations();

            // Verificar si hay hash para mostrar pestaña de reservaciones
            if (window.location.hash === '#reservations') {
                showTabByName('reservations');
            }
        });

        // CAMBIAR TABS
        function showTab(tabName) {
            showTabByName(tabName);
        }

        function showTabByName(tabName) {
            // Ocultar todos los tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Desactivar todos los botones
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'border-b-2', 'border-blue-600', 'text-blue-600');
                btn.classList.add('text-gray-600', 'hover:text-blue-600');
            });

            // Mostrar el tab seleccionado
            document.getElementById(tabName + '-tab').classList.remove('hidden');
            
            // Activar el botón correcto
            const button = Array.from(document.querySelectorAll('.tab-btn')).find(btn => 
                btn.onclick.toString().includes(tabName)
            );
            if (button) {
                button.classList.add('active', 'border-b-2', 'border-blue-600', 'text-blue-600');
                button.classList.remove('text-gray-600', 'hover:text-blue-600');
            }
        }

        // VERIFICAR SI ES ADMIN
        async function checkIfAdmin() {
            try {
                const response = await fetch('/api/me', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await response.json();
                if (data.user.role === 'admin') {
                    document.getElementById('adminLink').classList.remove('hidden');
                }
            } catch (e) {
                console.error(e);
            }
        }

        // CARGAR VUELOS PARA EL CATÁLOGO
        async function loadFlights() {
            try {
                const response = await fetch('/api/flights');
                const data = await response.json();
                const flights = data.data || data;
                window.flightsData = {};
                flights.forEach(f => window.flightsData[f.id] = f);
                renderFlights(flights);
            } catch (error) {
                console.error(error);
                document.getElementById('flights-grid').innerHTML = '<div class="text-center text-red-500">Error cargando vuelos</div>';
            }
        }

        // RENDERIZAR VUELOS
        function renderFlights(flights) {
            const container = document.getElementById('flights-grid');
            container.innerHTML = '';

            if (!flights || flights.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-10 col-span-3">No hay vuelos disponibles</div>';
                return;
            }

            flights.forEach(flight => {
                const item = `
                    <div class="border border-gray-200 rounded-lg p-5 bg-white hover:shadow-lg transition">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-gray-800">${flight.origin} → ${flight.destination}</h3>
                            <p class="text-sm text-gray-500">Vuelo ID: <strong>#${flight.id}</strong></p>
                        </div>
                        <div class="space-y-2 mb-4 text-sm">
                            <p><span class="text-gray-600 font-semibold">Salida:</span> ${new Date(flight.departure_time).toLocaleString('es-ES')}</p>
                            <p><span class="text-gray-600 font-semibold">Llegada:</span> ${new Date(flight.arrival_time).toLocaleString('es-ES')}</p>
                        </div>
                        
                        <!-- CLASE TURISTA -->
                        <div class="bg-blue-50 rounded-lg p-3 mb-3">
                            <p class="font-bold text-blue-700 text-sm mb-2">✈️ Clase Turista</p>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Asientos disponibles: <strong>${flight.economy_seats}</strong></span>
                                <span class="text-blue-600 font-bold">$${parseFloat(flight.economy_price).toFixed(2)}</span>
                            </div>
                        </div>

                        <!-- CLASE EJECUTIVO -->
                        <div class="bg-yellow-50 rounded-lg p-3 mb-4">
                            <p class="font-bold text-yellow-700 text-sm mb-2">👑 Clase Ejecutivo</p>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Asientos disponibles: <strong>${flight.business_seats}</strong></span>
                                <span class="text-yellow-600 font-bold">$${parseFloat(flight.business_price).toFixed(2)}</span>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg p-3 mb-4">
                            <p class="font-bold text-orange-700 text-sm mb-2">🌤️ Clima del Destino</p>
                            <div id="weather-${flight.id}" class="text-sm text-gray-600">
                                <button onclick="loadWeatherDashboard(${flight.id})" class="w-full bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-xs font-bold transition">
                                    Ver Clima
                                </button>
                            </div>
                        </div>

                        <button onclick="goToReservation(${flight.id})" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 rounded-lg transition">
                            Reservar
                        </button>
                    </div>
                `;
                container.innerHTML += item;
            });
        }

        // IR A RESERVAR UN VUELO
        function goToReservation(flightId) {
            document.getElementById('flight_id').value = flightId;
            document.getElementById('seat_class').value = '';
            document.getElementById('seat_number').value = '';
            document.getElementById('seat_number').disabled = true;
            document.getElementById('price-display').innerHTML = '<p class="text-sm text-gray-600">Precio:</p><p class="text-2xl font-bold text-blue-600">-</p>';
            showTabByName('reservations');
            document.getElementById('flight_id').focus();
        }

        // ACTUALIZAR OPCIONES DE ASIENTOS
        async function updateSeatOptions() {
            const flightId = document.getElementById('flight_id').value;
            const seatClass = document.getElementById('seat_class').value;
            const seatNumberSelect = document.getElementById('seat_number');
            const priceDisplay = document.getElementById('price-display');

            if (!flightId || !seatClass) {
                seatNumberSelect.disabled = true;
                seatNumberSelect.innerHTML = '<option value="">Selecciona clase primero</option>';
                return;
            }

            const flight = window.flightsData[flightId];
            if (!flight) {
                alert('Vuelo no encontrado. Recarga la página.');
                return;
            }

            // Obtener asientos reservados
            try {
                const response = await fetch(`/api/flights/${flightId}/reserved-seats`);
                const data = await response.json();
                window.reservedSeats = data.reserved_seats || [];
            } catch (error) {
                console.error('Error obteniendo asientos reservados:', error);
                window.reservedSeats = [];
            }

            let maxSeats = seatClass === 'economy' ? flight.economy_seats : flight.business_seats;
            let price = seatClass === 'economy' ? flight.economy_price : flight.business_price;
            let seatPrefix = seatClass === 'economy' ? 'E' : 'B';

            seatNumberSelect.innerHTML = '<option value="">Selecciona un asiento</option>';
            
            for (let i = 1; i <= maxSeats; i++) {
                const seatNum = seatPrefix + i;
                const isReserved = window.reservedSeats.includes(seatNum);
                
                const option = document.createElement('option');
                option.value = seatNum;
                option.textContent = `${seatNum} ${isReserved ? '(Reservado)' : '(Disponible)'}`;
                option.disabled = isReserved;
                option.style.color = isReserved ? 'red' : 'green';
                option.style.fontWeight = 'bold';
                seatNumberSelect.appendChild(option);
            }

            seatNumberSelect.disabled = false;

            // Mostrar precio
            priceDisplay.innerHTML = `<p class="text-sm text-gray-600">Precio:</p><p class="text-2xl font-bold text-blue-600">$${parseFloat(price).toFixed(2)}</p>`;

            // Actualizar precio cuando selecciones asiento
            seatNumberSelect.onchange = () => {
                if (seatNumberSelect.value) {
                    priceDisplay.innerHTML = `<p class="text-sm text-gray-600">Asiento ${seatNumberSelect.value}</p><p class="text-2xl font-bold text-green-600">$${parseFloat(price).toFixed(2)}</p>`;
                }
            };
        }

        // CARGAR RESERVACIONES
        async function loadReservations() {
            try {
                const response = await fetch('/api/reservations', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });

                if (response.status === 401) return logout();
                const data = await response.json();
                renderReservations(data);
            } catch (error) {
                console.error(error);
            }
        }

        // CREAR RESERVA
        document.getElementById('createForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const flightId = document.getElementById('flight_id').value;
            const seatClass = document.getElementById('seat_class').value;
            const seatNumber = document.getElementById('seat_number').value;

            if (!flightId || !seatClass || !seatNumber) {
                alert('Por favor completa todos los campos');
                return;
            }

            try {
                const response = await fetch('/api/reservations', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        flight_id: flightId,
                        seat_class: seatClass,
                        seat_number: seatNumber
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    alert('✓ ¡Reserva creada exitosamente!\nAsiento: ' + seatNumber);
                    document.getElementById('flight_id').value = '';
                    document.getElementById('seat_class').value = '';
                    document.getElementById('seat_number').value = '';
                    loadReservations();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo crear la reserva'));
                }
            } catch (error) {
                alert('Error de conexión');
                console.error(error);
            }
        });

        // CANCELAR RESERVA
        async function deleteReservation(id) {
            if(!confirm('¿Estás seguro de cancelar esta reserva?')) return;

            try {
                const response = await fetch(`/api/reservations/${id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });

                if (response.ok) {
                    loadReservations();
                } else {
                    alert('No se pudo cancelar.');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // RENDERIZAR RESERVACIONES
        function renderReservations(data) {
            const container = document.getElementById('reservas-container');
            container.innerHTML = '';
            const lista = data.data || data;

            const activeReservations = lista.filter(r => r.status !== 'cancelled');

            if (activeReservations.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-400 py-10">No tienes reservaciones activas.</div>';
                return;
            }

            activeReservations.forEach(reserva => {
                let statusClass = 'text-yellow-600';
                let statusText = '⏳ Pendiente';

                if (reserva.status === 'approved') {
                    statusClass = 'text-green-600';
                    statusText = '✓ Aprobada';
                } else if (reserva.status === 'rejected') {
                    statusClass = 'text-red-600';
                    statusText = '✗ Rechazada';
                } else if (reserva.status === 'confirmed') {
                    statusClass = 'text-green-600';
                    statusText = '✓ Confirmada';
                }
                
                const item = `
                    <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center hover:shadow-md transition bg-gray-50">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Reserva #${reserva.id}</p>
                            <p class="text-lg font-bold text-gray-800">Vuelo ID: ${reserva.flight_id}</p>
                            <p class="text-sm text-gray-600">Asiento: ${reserva.seat_number || 'N/A'}</p>
                            <p class="text-sm ${statusClass} font-medium">Estado: ${statusText}</p>
                            <p class="text-xs text-gray-400 mt-1">📅 ${new Date(reserva.created_at).toLocaleDateString()}</p>
                        </div>
                        <button onclick="deleteReservation(${reserva.id})" 
                                class="bg-white text-red-500 border border-red-200 hover:bg-red-50 px-3 py-2 rounded-lg text-sm font-bold transition">
                            Cancelar
                        </button>
                    </div>
                `;
                container.innerHTML += item;
            });
        }

        // CARGAR CLIMA EN DASHBOARD
        async function loadWeatherDashboard(flightId) {
            const target = document.getElementById(`weather-${flightId}`);
            if (!target) return;
            target.innerHTML = '<p class="text-sm text-gray-600">Cargando clima...</p>';

            try {
                const response = await fetch(`/api/flights/${flightId}/weather`);
                const data = await response.json();

                if (!response.ok || !data.success) {
                    target.innerHTML = `<p class="text-sm text-red-600">${data.message || 'No disponible'}</p>`;
                    return;
                }

                const w = data.data;
                target.innerHTML = `
                    <div class="space-y-1">
                        <p class="text-sm font-semibold text-orange-700">${w.temp_c}°C · ${w.description}</p>
                        <p class="text-xs text-gray-600">💧 Humedad: ${w.humidity}%</p>
                        <p class="text-xs text-gray-600">🌬️ Viento: ${w.wind_speed_ms} m/s</p>
                    </div>
                `;
            } catch (error) {
                target.innerHTML = '<p class="text-sm text-red-600">Error cargando clima</p>';
                console.error('Error:', error);
            }
        }

        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
    </script>
</body>
</html>
