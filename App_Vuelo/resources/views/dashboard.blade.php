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
                <button onclick="goToProfile()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm font-bold transition shadow">
                    👤 Perfil
                </button>
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
            <button onclick="showTab('profile')" class="tab-btn px-6 py-3 font-bold text-gray-600 hover:text-blue-600">
                👤 Mi Perfil
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

        <!-- TAB: PERFIL -->
        <div id="profile-tab" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Mi Perfil</h2>
                        
                        <!-- Edit Mode Toggle -->
                        <div class="mb-6 flex gap-3">
                            <button onclick="toggleEditMode()" id="edit-toggle-btn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition">
                                ✏️ Editar Perfil
                            </button>
                            <button onclick="cancelEdit()" id="cancel-edit-btn" class="hidden bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                                ✕ Cancelar
                            </button>
                        </div>

                        <!-- User Info Section (View Mode) -->
                        <div id="profile-view-mode" class="bg-blue-50 rounded-lg p-6 mb-6 border-l-4 border-blue-500">
                            <div class="mb-6">
                                <p class="text-sm text-gray-600 font-bold mb-2">Nombre</p>
                                <p class="text-xl font-bold text-gray-800" id="profile-name">-</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 font-bold mb-2">Email</p>
                                <p class="text-xl text-gray-800" id="profile-email">-</p>
                            </div>
                        </div>

                        <!-- Edit Form (Hidden by default) -->
                        <div id="profile-edit-mode" class="hidden">
                            <form onsubmit="saveProfile(event)" class="space-y-4 bg-blue-50 rounded-lg p-6 mb-6 border-l-4 border-blue-500">
                                <div>
                                    <label class="block text-sm text-gray-600 font-bold mb-2">Nombre</label>
                                    <input type="text" id="edit-name" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm text-gray-600 font-bold mb-2">Email</label>
                                    <input type="email" id="edit-email" class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                </div>

                                <div class="pt-4 flex gap-3">
                                    <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition">
                                        💾 Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Save Status Message -->
                        <div id="save-status" class="hidden rounded-lg p-4 mb-6 font-semibold text-center"></div>
                        
                        <!-- Danger Zone -->
                        <div class="border-t-2 border-red-200 pt-6">
                            <h3 class="text-lg font-bold text-red-600 mb-4">⚠️ Zona de Peligro</h3>
                            <p class="text-sm text-gray-600 mb-4">Eliminar tu cuenta es permanente e irreversible. Se eliminarán todas tus reservaciones.</p>
                            <button onclick="deleteAccount()" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                                🗑️ Eliminar Mi Cuenta
                            </button>
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

            // Cargar datos específicos al entrar en cada tab
            if (tabName === 'catalog') {
                loadFlights();
            } else if (tabName === 'reservations') {
                loadReservations();
            } else if (tabName === 'profile') {
                loadProfileInfo();
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
                
                // Extraer el array de vuelos (puede venir en data.data o directamente)
                let flights = Array.isArray(data) ? data : (data.data || []);

                // Guardar vuelos en window para uso posterior
                window.flightsData = {};
                flights.forEach(f => window.flightsData[f.id] = f);

                // 1. Apuntamos al contenedor correcto (Revisa que tu HTML tenga este ID)
                const container = document.getElementById('flights-grid'); 
                container.innerHTML = ''; // Limpiamos el "Cargando..."

                if (!Array.isArray(flights) || flights.length === 0) {
                    container.innerHTML = '<p class="col-span-3 text-center text-gray-500">No hay vuelos disponibles.</p>';
                    return;
                }

                flights.forEach(flight => {
                    const economyPrice = Number(flight.economy_price ?? flight.price ?? 0).toFixed(2);
                    const businessPrice = Number(flight.business_price ?? flight.price ?? 0).toFixed(2);
                    const imagenSrc = flight.image_url ? flight.image_url : 'https://placehold.co/600x400?text=Vuelo+Disponible';

                    // Formato de fecha/hora sin conversión de zona horaria
                    let salidaTexto = 'Por confirmar';
                    if (flight.departure_time) {
                        const [datePart, timePart] = String(flight.departure_time).split('T');
                        const [y,m,d] = datePart.split('-');
                        const hhmm = (timePart || '').substring(0,5);
                        salidaTexto = `${d}/${m}/${y} ${hhmm}`;
                    }

                    const card = `
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition border border-gray-100 group">
                            <div class="h-48 overflow-hidden relative">
                                <img src="${imagenSrc}" alt="Vuelo a ${flight.destination}"
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-3 py-1 m-2 rounded-full">
                                    Vuelo #${flight.id}
                                </div>
                            </div>

                            <div class="p-5">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="text-lg font-bold text-gray-800">${flight.origin} ✈️ ${flight.destination}</h3>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                    <p class="text-xs text-gray-500 font-bold mb-1">📅 Salida</p>
                                    <p class="text-sm text-gray-800 font-bold">${salidaTexto}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div class="bg-blue-50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-blue-700 mb-1">✈️ Turista</p>
                                        <p class="text-blue-600 font-bold text-lg">$${economyPrice}</p>
                                    </div>
                                    <div class="bg-green-50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-green-700 mb-1">👔 Ejecutivo</p>
                                        <p class="text-green-600 font-bold text-lg">$${businessPrice}</p>
                                    </div>
                                </div>

                                <button onclick="goToReservation(${flight.id})"
                                    class="w-full bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white font-bold py-2 px-4 rounded transition">
                                    Reservar Este Vuelo
                                </button>
                            </div>
                        </div>
                    `;
                    container.innerHTML += card;
                });

                // También actualizamos el SELECT del formulario de reserva (opcional)
                updateSelectOptions(flights);

            } catch (error) {
                console.error("Error cargando vuelos", error);
                document.getElementById('flights-grid').innerHTML = '<p class="text-red-500">Error al cargar el catálogo.</p>';
            }
        }

        // Función auxiliar para llenar el select (si lo tienes separado)
        function updateSelectOptions(flights) {
            const select = document.getElementById('flight_id');
            if(select) {
                select.innerHTML = '<option value="">-- Elige un destino --</option>';
                flights.forEach(f => {
                    select.innerHTML += `<option value="${f.id}">${f.origin} -> ${f.destination}</option>`;
                });
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
                const economyPrice = flight.economy_price || flight.price || 0;
                const businessPrice = flight.business_price || flight.price || 0;
                
                // Formatear fechas sin conversión de zona horaria
                const departureDateStr = flight.departure_time.split('T')[0].split('-').reverse().join('/');
                const departureTimeStr = flight.departure_time.split('T')[1].substring(0, 5);
                
                const item = `
                    <div class="border border-gray-200 rounded-lg p-5 bg-white hover:shadow-lg transition">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-gray-800">${flight.origin} ✈️ ${flight.destination}</h3>
                            <p class="text-sm text-gray-500">Vuelo #${flight.id}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                            <p class="text-xs text-gray-500 font-bold mb-1">📅 Salida</p>
                            <p class="text-sm text-gray-800 font-bold">${departureDateStr} ${departureTimeStr}</p>
                        </div>
                        
                        <!-- CLASE TURISTA -->
                        <div class="bg-blue-50 rounded-lg p-3 mb-2">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-blue-700 text-sm">✈️ Turista</span>
                                <span class="text-blue-600 font-bold text-lg">$${parseFloat(economyPrice).toFixed(2)}</span>
                            </div>
                        </div>

                        <!-- CLASE EJECUTIVO -->
                        <div class="bg-green-50 rounded-lg p-3 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-green-700 text-sm">👔 Ejecutivo</span>
                                <span class="text-green-600 font-bold text-lg">$${parseFloat(businessPrice).toFixed(2)}</span>
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
            let price = seatClass === 'economy'
                ? (flight.economy_price ?? flight.price ?? 0)
                : (flight.business_price ?? flight.price ?? 0);
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
                
                // Cargar información de vuelos para mostrar origen y destino
                const reservations = data.data || data;
                if (reservations.length > 0) {
                    const flightsResponse = await fetch('/api/flights', {
                        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                    });
                    const flightsData = await flightsResponse.json();
                    const flights = flightsData.data || flightsData;
                    
                    // Crear un mapa de vuelos para búsqueda rápida
                    window.flightsMap = {};
                    flights.forEach(f => window.flightsMap[f.id] = f);
                }
                
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
                
                // Obtener información del vuelo
                const flight = window.flightsMap ? window.flightsMap[reserva.flight_id] : null;
                const originText = flight ? (flight.origin_code ? `${flight.origin} (${flight.origin_code})` : flight.origin) : 'Origen';
                const destText   = flight ? (flight.destination_code ? `${flight.destination} (${flight.destination_code})` : flight.destination) : 'Destino';
                const flightRoute = flight ? `${originText} ✈️ ${destText}` : 'Vuelo no disponible';
                
                // Calcular el precio según la clase de asiento
                let price = 0;
                if (flight) {
                    const base = reserva.seat_class === 'economy' ? (flight.economy_price ?? flight.price ?? 0) : (flight.business_price ?? flight.price ?? 0);
                    price = Number(base);
                }
                
                // Formatear clase de asiento
                const seatClassText = reserva.seat_class === 'economy' ? '✈️ Turista' : '👔 Ejecutiva';
                
                const createdAt = new Date(reserva.created_at);
                const createdDate = !isNaN(createdAt.getTime()) ? createdAt.toLocaleDateString('es-ES') : '';

                const item = `
                    <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-start hover:shadow-md transition bg-white">
                        <div class="space-y-1">
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">RESERVA #${reserva.id}</p>
                            <p class="text-lg font-bold text-gray-800">Vuelo ID: ${reserva.flight_id}</p>
                            <p class="text-sm text-blue-600 font-semibold">${flightRoute}</p>
                            <p class="text-sm text-gray-600">Asiento: ${reserva.seat_number || 'N/A'}</p>
                            <p class="text-sm text-gray-600">Estado: <span class="${statusClass} font-semibold">${statusText}</span></p>
                            <p class="text-xs text-gray-400">📅 ${createdDate}</p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <p class="text-lg font-bold text-green-600">$${price.toFixed(2)}</p>
                            <button onclick="deleteReservation(${reserva.id})" 
                                    class="bg-white text-red-500 border border-red-200 hover:bg-red-50 px-3 py-2 rounded-lg text-sm font-bold transition">
                                Cancelar
                            </button>
                        </div>
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

        // IR AL PERFIL
        async function goToProfile() {
            showTabByName('profile');
            loadProfileInfo();
        }

        // CARGAR INFORMACIÓN DEL PERFIL
        async function loadProfileInfo() {
            try {
                const response = await fetch('/api/me', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await response.json();
                
                if (data.user) {
                    document.getElementById('profile-name').textContent = data.user.name || '-';
                    document.getElementById('profile-email').textContent = data.user.email || '-';
                    
                    // Cargar datos en el formulario de edición
                    document.getElementById('edit-name').value = data.user.name || '';
                    document.getElementById('edit-email').value = data.user.email || '';
                }
            } catch (error) {
                console.error('Error cargando perfil:', error);
                alert('Error al cargar tu perfil');
            }
        }

        // TOGGLE EDIT MODE
        function toggleEditMode() {
            const viewMode = document.getElementById('profile-view-mode');
            const editMode = document.getElementById('profile-edit-mode');
            const editBtn = document.getElementById('edit-toggle-btn');
            const cancelBtn = document.getElementById('cancel-edit-btn');

            viewMode.classList.add('hidden');
            editMode.classList.remove('hidden');
            editBtn.classList.add('hidden');
            cancelBtn.classList.remove('hidden');
        }

        // CANCEL EDIT MODE
        function cancelEdit() {
            const viewMode = document.getElementById('profile-view-mode');
            const editMode = document.getElementById('profile-edit-mode');
            const editBtn = document.getElementById('edit-toggle-btn');
            const cancelBtn = document.getElementById('cancel-edit-btn');

            viewMode.classList.remove('hidden');
            editMode.classList.add('hidden');
            editBtn.classList.remove('hidden');
            cancelBtn.classList.add('hidden');
        }

        // SAVE PROFILE CHANGES
        async function saveProfile(event) {
            event.preventDefault();

            const name = document.getElementById('edit-name').value.trim();
            const email = document.getElementById('edit-email').value.trim();
            const statusDiv = document.getElementById('save-status');

            if (!name || !email) {
                statusDiv.textContent = '❌ Por favor completa todos los campos';
                statusDiv.className = 'bg-red-100 border border-red-300 text-red-800 hidden';
                statusDiv.classList.remove('hidden');
                return;
            }

            try {
                const response = await fetch('/api/users/me', {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Actualizar vista
                    document.getElementById('profile-name').textContent = name;
                    document.getElementById('profile-email').textContent = email;

                    // Mostrar mensaje de éxito
                    statusDiv.textContent = '✓ Perfil actualizado exitosamente';
                    statusDiv.className = 'bg-green-100 border border-green-300 text-green-800';
                    statusDiv.classList.remove('hidden');

                    // Volver a modo visualización después de 2 segundos
                    setTimeout(() => {
                        cancelEdit();
                        statusDiv.classList.add('hidden');
                    }, 2000);
                } else {
                    statusDiv.textContent = '❌ Error: ' + (data.message || 'No se pudo actualizar el perfil');
                    statusDiv.className = 'bg-red-100 border border-red-300 text-red-800';
                    statusDiv.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error actualizando perfil:', error);
                statusDiv.textContent = '❌ Error de conexión. Intenta de nuevo.';
                statusDiv.className = 'bg-red-100 border border-red-300 text-red-800';
                statusDiv.classList.remove('hidden');
            }
        }


        // ELIMINAR CUENTA
        async function deleteAccount() {
            const confirmed = confirm('⚠️ ¿Estás seguro que deseas eliminar tu cuenta? Esta acción es permanente e irreversible. Se eliminarán todas tus reservaciones.');
            
            if (!confirmed) {
                return;
            }

            try {
                const response = await fetch('/api/users/me', {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alert('Tu cuenta ha sido eliminada exitosamente');
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                } else {
                    alert('Error: ' + (data.message || 'No se pudo eliminar la cuenta'));
                }
            } catch (error) {
                console.error('Error eliminando cuenta:', error);
                alert('Error al eliminar la cuenta. Intenta de nuevo.');
            }
        }

        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
    </script>
</body>
</html>
