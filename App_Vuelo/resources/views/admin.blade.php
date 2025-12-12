<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Admin - App Vuelo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-red-600 p-4 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold flex items-center gap-2">
                Panel Administrador - App Vuelo
            </h1>
            <div class="flex gap-4 items-center">
                <span class="text-white text-sm">Bienvenido, <strong id="adminName">Admin</strong></span>
                <button onclick="logout()" class="bg-white text-red-600 hover:bg-gray-200 px-4 py-2 rounded text-sm font-bold transition shadow">
                    Cerrar Sesión
                </button>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto mt-8 p-6">

        <div id="admin-check-status" class="mb-4 hidden rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 text-sm"></div>
        
        <!-- TABS -->
        <div class="flex gap-4 mb-8 border-b border-gray-300">
            <button onclick="showTab('flights')" class="tab-btn active px-6 py-3 font-bold text-blue-600 border-b-2 border-blue-600">
                Gestionar Vuelos
            </button>
            <button onclick="showTab('stats')" class="tab-btn px-6 py-3 font-bold text-gray-600 hover:text-blue-600">
                Estadísticas
            </button>
            <button onclick="showTab('reservations')" class="tab-btn px-6 py-3 font-bold text-gray-600 hover:text-blue-600">
                Reservaciones
            </button>
        </div>

        <!-- TAB: VUELOS -->
        <div id="flights-tab" class="tab-content block">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- FORMULARIO CREAR/EDITAR VUELO -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2" id="formTitle">Nuevo Vuelo</h2>
                        
                        <form id="flightForm" onsubmit="return guardarVuelo(event)" class="space-y-4">
                            <input type="hidden" id="flight_id">
                            
                            <div>
                                <label class="block text-gray-600 text-sm font-bold mb-2">Origen</label>
                                <input type="text" id="origin" 
                                       class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                                       placeholder="Ej: Quito (UIO)" required>
                            </div>

                            <div>
                                <label class="block text-gray-600 text-sm font-bold mb-2">Destino</label>
                                <input type="text" id="destination" 
                                       class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                                       placeholder="Ej: Guayaquil (GYE)" required>
                            </div>

                            <div>
                                <label class="block text-gray-600 text-sm font-bold mb-2">Salida</label>
                                <input type="datetime-local" id="departure_time" 
                                       class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                                       required>
                            </div>

                            <div>
                                <label class="block text-gray-600 text-sm font-bold mb-2">Llegada</label>
                                <input type="datetime-local" id="arrival_time" 
                                       class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                                       required>
                            </div>

                            <div>
                                <label class="block text-gray-600 text-sm font-bold mb-2">Precio (USD)</label>
                                <input type="number" id="price" step="0.01" 
                                       class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                                       placeholder="99.99" required>
                            </div>

                            <div>
                                <input type="hidden" name="image_url" id="firebaseUrl">
                                <label class="block text-gray-600 text-sm font-bold mb-2">Imagen Vuelo (Opcional)</label>
                                <div class="flex flex-col gap-2">
                                    <input type="file" id="flight_image" accept="image/*"
                                           class="block w-full text-sm text-gray-500
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-md file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-blue-50 file:text-blue-700
                                                  hover:file:bg-blue-100">
                                    <small class="text-gray-500">Máx: 5MB. Soporta JPEG, PNG, GIF</small>
                                    <div id="image-preview" class="hidden mt-2">
                                        <img id="preview-img" class="w-full h-32 object-cover rounded-lg border border-gray-300">
                                        <button type="button" onclick="clearImagePreview()" class="mt-2 text-sm text-red-600 hover:text-red-800 font-bold">Eliminar imagen</button>
                                    </div>
                                    <button type="button" id="uploadImageBtn" onclick="subirImagen()" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 rounded-lg transition">
                                        🔼 Subir Imagen
                                    </button>
                                    <div id="image-status" class="text-sm text-center"></div>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition shadow-md">
                                💾 Guardar Vuelo
                            </button>
                            <button type="button" onclick="resetForm()" class="w-full bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 rounded-lg transition">
                                Limpiar
                            </button>
                        </form>
                    </div>
                </div>

                <!-- LISTA DE VUELOS -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Vuelos Registrados</h2>
                            <button onclick="loadFlights()" class="text-blue-500 text-sm hover:underline">🔄 Actualizar</button>
                        </div>
                        
                        <div id="flights-container" class="space-y-4">
                            <p class="text-gray-500 text-center py-10">Cargando vuelos...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: ESTADÍSTICAS -->
        <div id="stats-tab" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 rounded-xl shadow p-6">
                    <p class="text-gray-600 text-sm font-bold">Total Vuelos</p>
                    <p class="text-3xl font-bold text-blue-600" id="stat-flights">0</p>
                </div>
                <div class="bg-green-50 rounded-xl shadow p-6">
                    <p class="text-gray-600 text-sm font-bold">Total Usuarios</p>
                    <p class="text-3xl font-bold text-green-600" id="stat-users">0</p>
                </div>
                <div class="bg-purple-50 rounded-xl shadow p-6">
                    <p class="text-gray-600 text-sm font-bold">Total Reservas</p>
                    <p class="text-3xl font-bold text-purple-600" id="stat-reservations">0</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Buscar Estadísticas de Vuelo</h2>
                <div class="flex gap-4 items-end">
                    <div class="flex-1">
                        <label class="block text-gray-600 text-sm font-bold mb-2">ID del Vuelo</label>
                        <input type="number" id="search-flight-id" 
                               class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                               placeholder="Ej: 1">
                    </div>
                    <button onclick="searchFlightStats()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                        Buscar
                    </button>
                </div>

                <div id="flight-stats-result" class="mt-6 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-gray-600 text-sm font-bold">Asientos Disponibles - Turista</p>
                            <p class="text-3xl font-bold text-blue-600" id="stats-economy-available">0</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <p class="text-gray-600 text-sm font-bold">Asientos Disponibles - Ejecutivo</p>
                            <p class="text-3xl font-bold text-yellow-600" id="stats-business-available">0</p>
                        </div>
                    </div>
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm font-bold mb-2">Detalles del Vuelo</p>
                        <div id="flight-details" class="text-gray-700 space-y-1">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Últimas Reservas</h2>
                <div id="stats-reservations-list" class="space-y-4">
                    <p class="text-gray-500 text-center py-10">Cargando reservas...</p>
                </div>
            </div>
        </div>

        <!-- TAB: RESERVACIONES -->
        <div id="reservations-tab" class="tab-content hidden">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Todas las Reservaciones</h2>
                    <button onclick="loadReservations()" class="text-blue-500 text-sm hover:underline">🔄 Actualizar</button>
                </div>
                
                <div id="all-reservations-list" class="space-y-4">
                    <p class="text-gray-500 text-center py-10">Cargando reservaciones...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('token');
        
        // Verificar token inmediatamente antes de cargar contenido
        if (!token) {
            window.location.href = '/login';
        }

        // INICIALIZAR
        document.addEventListener('DOMContentLoaded', async () => {
            await verifyAdminAccess();
            loadFlights();
            loadStats();
            loadReservations();
            loadUserName();
        });

        // VERIFICAR ACCESO DE ADMIN
        async function verifyAdminAccess() {
            const statusEl = document.getElementById('admin-check-status');
            const token = localStorage.getItem('token');
            
            if (!token) {
                statusEl.textContent = 'Sin token en localStorage. Redirigiendo a login...';
                statusEl.classList.remove('hidden');
                window.location.href = '/login';
                return;
            }

            try {
                const response = await fetch('/api/me', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (response.status === 401) {
                    statusEl.textContent = 'Token inválido/expirado (401). Borrando token y redirigiendo a login.';
                    statusEl.classList.remove('hidden');
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                    return;
                }

                const data = await response.json();
                console.log('DEBUG /api/me ->', data);
                statusEl.textContent = `Usuario: ${data?.user?.email || 'desconocido'} · Rol: ${data?.user?.role || 'sin rol'}`;
                statusEl.classList.remove('hidden');
                statusEl.classList.remove('bg-yellow-50','text-yellow-800','border-yellow-200');
                statusEl.classList.add('bg-green-50','text-green-800','border-green-200');
                
                if (data.user.role !== 'admin') {
                    statusEl.textContent += ' · Rol no admin, redirigiendo a dashboard';
                    window.location.href = '/dashboard';
                    return;
                }
            } catch (error) {
                console.error('Error al verificar acceso:', error);
                statusEl.textContent = 'Error al llamar /api/me. Revisa consola.';
                statusEl.classList.remove('hidden');
                window.location.href = '/login';
            }
        }

        // CARGAR NOMBRE DEL ADMIN
        async function loadUserName() {
            try {
                const response = await fetch('/api/me', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await response.json();
                document.getElementById('adminName').textContent = data.user.name;
            } catch (e) {
                console.error(e);
            }
        }

        // MOSTRAR TAB
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                el.classList.add('text-gray-600');
            });
            
            document.getElementById(tabName + '-tab').classList.remove('hidden');
            event.target.classList.remove('text-gray-600');
            event.target.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');

            if (tabName === 'stats') loadStats();
            if (tabName === 'reservations') loadReservations();
        }

        // CARGAR VUELOS
        async function loadFlights() {
            try {
                const response = await fetch('/api/flights', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await response.json();
                renderFlights(data.data || data);
            } catch (error) {
                console.error(error);
            }
        }

        // RENDERIZAR VUELOS
        function renderFlights(flights) {
            const container = document.getElementById('flights-container');
            container.innerHTML = '';

            if (flights.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-400 py-10">No hay vuelos registrados.</div>';
                return;
            }

            flights.forEach(flight => {
                const item = `
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 uppercase font-bold">Vuelo #${flight.id}</p>
                                <p class="text-lg font-bold text-gray-800">${flight.origin} → ${flight.destination}</p>
                                <p class="text-sm text-gray-600 mt-2">
                                    📅 ${new Date(flight.departure_time).toLocaleDateString()} 
                                    ⏰ ${new Date(flight.departure_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                                </p>
                                <p class="text-sm text-blue-600 font-bold mt-2">💰 $${parseFloat(flight.price).toFixed(2)}</p>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="editFlight(${flight.id})" class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-2 rounded text-sm font-bold">
                                    ✏️ Editar
                                </button>
                                <button onclick="deleteFlight(${flight.id})" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm font-bold">
                                    🗑️ Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += item;
            });
        }

        // GUARDAR VUELO
        async function guardarVuelo(event) {
            event.preventDefault();
            
            const flightId = document.getElementById('flight_id').value;
            const isEdit = !!flightId;

            // Recolectar datos del formulario
            const datos = {
                origin: document.getElementById('origin').value,
                destination: document.getElementById('destination').value,
                departure_time: document.getElementById('departure_time').value,
                arrival_time: document.getElementById('arrival_time').value,
                price: document.getElementById('price').value,
                image_url: document.getElementById('firebaseUrl').value || null
            };

            try {
                const url = isEdit ? `/api/flights/${flightId}` : '/api/flights';
                const method = isEdit ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(datos)
                });

                const result = await response.json();

                if (response.ok) {
                    alert(isEdit ? '✓ Vuelo actualizado exitosamente' : '✓ Vuelo creado exitosamente');
                    resetForm();
                    loadFlights();
                } else {
                    alert('❌ Error: ' + (result.message || 'Intenta de nuevo'));
                    console.error('Response error:', result);
                }
            } catch (error) {
                alert('❌ Error de conexión: ' + error.message);
                console.error('Fetch error:', error);
            }
            
            return false;
        }

        // PREVIEW DE IMAGEN
        document.getElementById('flight_image').addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    const preview = document.getElementById('image-preview');
                    const previewImg = document.getElementById('preview-img');
                    previewImg.src = event.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // LIMPIAR PREVIEW DE IMAGEN
        function clearImagePreview() {
            document.getElementById('flight_image').value = '';
            document.getElementById('image-preview').classList.add('hidden');
        }

        // EDITAR VUELO
        async function editFlight(id) {
            try {
                const response = await fetch(`/api/flights/${id}`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await response.json();
                const flight = data.data || data;

                document.getElementById('flight_id').value = flight.id;
                document.getElementById('origin').value = flight.origin;
                document.getElementById('destination').value = flight.destination;
                document.getElementById('departure_time').value = flight.departure_time.slice(0, 16);
                document.getElementById('arrival_time').value = flight.arrival_time.slice(0, 16);
                document.getElementById('price').value = flight.price;
                clearImagePreview();
                document.getElementById('formTitle').textContent = `Editar Vuelo #${flight.id}`;

                window.scrollTo({ top: 0, behavior: 'smooth' });
            } catch (error) {
                alert('Error al cargar vuelo');
                console.error(error);
            }
        }

        // ELIMINAR VUELO
        async function deleteFlight(id) {
            if (!confirm('¿Estás seguro de eliminar este vuelo? Se cancelarán todas sus reservas.')) return;

            try {
                const response = await fetch(`/api/flights/${id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (response.ok) {
                    alert('Vuelo eliminado');
                    loadFlights();
                } else {
                    alert('No se pudo eliminar');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // LIMPIAR FORMULARIO
        function resetForm() {
            document.getElementById('flightForm').reset();
            document.getElementById('flight_id').value = '';
            document.getElementById('formTitle').textContent = 'Nuevo Vuelo';
            document.getElementById('firebaseUrl').value = '';
            document.getElementById('image-status').textContent = '';
            clearImagePreview();
        }

        // CARGAR ESTADÍSTICAS
        async function loadStats() {
            try {
                const flightsRes = await fetch('/api/flights', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const flightsData = await flightsRes.json();
                const flights = flightsData.data || flightsData;

                const usersRes = await fetch('/api/users/count', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const userData = await usersRes.json();

                const reservationsRes = await fetch('/api/admin/reservations', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const reservationsData = await reservationsRes.json();
                const reservations = reservationsData.data || [];

                document.getElementById('stat-flights').textContent = flights.length;
                document.getElementById('stat-users').textContent = userData.count || 0;
                document.getElementById('stat-reservations').textContent = reservations.length;

                renderReservationsInStats(reservations.slice(0, 10));
            } catch (error) {
                console.error(error);
            }
        }

        // BUSCAR ESTADÍSTICAS DE UN VUELO ESPECÍFICO
        async function searchFlightStats() {
            const flightId = document.getElementById('search-flight-id').value;

            if (!flightId) {
                alert('Por favor ingresa un ID de vuelo');
                return;
            }

            try {
                // Obtener datos del vuelo
                const flightRes = await fetch(`/api/flights/${flightId}`);
                const flightData = await flightRes.json();

                if (!flightRes.ok || !flightData.data) {
                    alert('Vuelo no encontrado');
                    return;
                }

                const flight = flightData.data;

                // Obtener asientos reservados
                const seatsRes = await fetch(`/api/flights/${flightId}/reserved-seats`);
                const seatsData = await seatsRes.json();
                const reservedSeats = seatsData.reserved_seats || [];

                // Contar asientos reservados por clase
                const economyReserved = reservedSeats.filter(s => s.startsWith('E')).length;
                const businessReserved = reservedSeats.filter(s => s.startsWith('B')).length;

                // Calcular disponibles
                const economyAvailable = flight.economy_seats - economyReserved;
                const businessAvailable = flight.business_seats - businessReserved;

                // Mostrar resultados
                document.getElementById('stats-economy-available').textContent = economyAvailable;
                document.getElementById('stats-business-available').textContent = businessAvailable;

                const detailsHtml = `
                    <p><strong>Vuelo ID:</strong> ${flight.id}</p>
                    <p><strong>Ruta:</strong> ${flight.origin} → ${flight.destination}</p>
                    <p><strong>Salida:</strong> ${new Date(flight.departure_time).toLocaleString('es-ES')}</p>
                    <p><strong>Llegada:</strong> ${new Date(flight.arrival_time).toLocaleString('es-ES')}</p>
                    <p><strong>Turista:</strong> ${economyReserved}/${flight.economy_seats} reservados (${economyAvailable} disponibles)</p>
                    <p><strong>Ejecutivo:</strong> ${businessReserved}/${flight.business_seats} reservados (${businessAvailable} disponibles)</p>
                    <p><strong>Precio Turista:</strong> $${parseFloat(flight.economy_price).toFixed(2)}</p>
                    <p><strong>Precio Ejecutivo:</strong> $${parseFloat(flight.business_price).toFixed(2)}</p>
                `;

                document.getElementById('flight-details').innerHTML = detailsHtml;
                document.getElementById('flight-stats-result').classList.remove('hidden');
            } catch (error) {
                console.error(error);
                alert('Error obteniendo datos del vuelo');
            }
        }

        // CARGAR TODAS LAS RESERVACIONES
        async function loadReservations() {
            try {
                const response = await fetch('/api/admin/reservations', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await response.json();
                renderAllReservations(data.data || []);
            } catch (error) {
                console.error(error);
            }
        }

        // RENDERIZAR RESERVAS EN ESTADÍSTICAS
        function renderReservationsInStats(reservations) {
            const container = document.getElementById('stats-reservations-list');
            container.innerHTML = '';

            if (reservations.length === 0) {
                container.innerHTML = '<p class="text-gray-400 text-center py-10">Sin reservas</p>';
                return;
            }

            reservations.forEach(res => {
                const statusColor = res.status === 'approved' ? 'green' : res.status === 'pending' ? 'yellow' : 'red';
                const statusText = res.status === 'approved' ? 'Aprobada' : res.status === 'pending' ? 'Pendiente' : 'Rechazada';

                const item = `
                    <div class="border border-gray-200 rounded p-4 bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold">Reserva #${res.id}</p>
                                <p class="text-sm text-gray-600">Usuario ID: ${res.user_id} | Vuelo: ${res.flight_id}</p>
                                <p class="text-sm text-${statusColor}-600 font-bold">Estado: ${statusText}</p>
                            </div>
                            <p class="text-xs text-gray-400">${new Date(res.created_at).toLocaleDateString()}</p>
                        </div>
                    </div>
                `;
                container.innerHTML += item;
            });
        }

        // RENDERIZAR TODAS LAS RESERVACIONES CON ACCIONES
        function renderAllReservations(reservations) {
            const container = document.getElementById('all-reservations-list');
            container.innerHTML = '';

            if (reservations.length === 0) {
                container.innerHTML = '<p class="text-gray-400 text-center py-10">Sin reservaciones</p>';
                return;
            }

            reservations.forEach(res => {
                const statusColor = res.status === 'approved' ? 'green' : res.status === 'pending' ? 'yellow' : 'red';
                const statusText = res.status === 'approved' ? 'Aprobada' : res.status === 'pending' ? 'Pendiente' : 'Rechazada';
                const statusBg = res.status === 'approved' ? 'bg-green-50' : res.status === 'pending' ? 'bg-yellow-50' : 'bg-red-50';

                const item = `
                    <div class="border border-gray-200 rounded-lg p-4 ${statusBg}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="font-bold">Reserva #${res.id}</p>
                                <p class="text-sm text-gray-600">Usuario: ${res.user?.name || 'Desconocido'} (${res.user?.email || '-'})</p>
                                <p class="text-sm text-gray-600">Vuelo ID: ${res.flight_id}</p>
                                <p class="text-sm text-${statusColor}-600 font-bold mt-2">Estado: ${statusText}</p>
                            </div>
                            <div class="flex gap-2">
                                ${res.status === 'pending' ? `
                                    <button onclick="updateReservationStatus(${res.id}, 'approved')" class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm font-bold">✓ Aprobar</button>
                                    <button onclick="updateReservationStatus(${res.id}, 'rejected')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm font-bold">✗ Rechazar</button>
                                ` : ''}
                                <button onclick="deleteReservation(${res.id})" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-sm font-bold">🗑️ Eliminar</button>
                            </div>
                        </div>
                    </div>
                `;
                container.innerHTML += item;
            });
        }

        // ACTUALIZAR ESTADO DE RESERVACIÓN
        async function updateReservationStatus(id, newStatus) {
            if (!confirm(`¿Estás seguro de que quieres cambiar el estado a: ${newStatus}?`)) {
                return;
            }

            try {
                const response = await fetch(`/api/reservations/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                if (response.ok) {
                    alert('Estado actualizado');
                    loadReservations();
                    loadStats();
                } else {
                    alert('Error al actualizar');
                }
            } catch (error) {
                alert('Error de conexión');
                console.error(error);
            }
        }

        // ELIMINAR RESERVACIÓN
        async function deleteReservation(id) {
            if (!confirm('¿Estás seguro de que quieres eliminar esta reservación?')) {
                return;
            }

            try {
                const response = await fetch(`/api/reservations/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    alert('Reservación eliminada');
                    loadReservations();
                    loadStats();
                } else {
                    alert('Error al eliminar');
                }
            } catch (error) {
                alert('Error de conexión');
                console.error(error);
            }
        }

        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
    </script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-storage-compat.js"></script>

<script>
    // 1. CONFIGURACIÓN (Toma las llaves de tu archivo .env)
    const firebaseConfig = {
        apiKey: "{{ env('FIREBASE_API_KEY') }}",
        authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
        projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
        storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
        messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
        appId: "{{ env('FIREBASE_APP_ID') }}"
    };

    // Inicializar Firebase
    const app = firebase.initializeApp(firebaseConfig);
    const storage = firebase.storage();

    // FUNCIÓN PARA SUBIR IMAGEN (botón separado)
    async function subirImagen() {
        const fileInput = document.getElementById('flight_image');
        const file = fileInput.files[0];
        const btn = document.getElementById('uploadImageBtn');
        const statusDiv = document.getElementById('image-status');

        if (!file) {
            statusDiv.textContent = '❌ Selecciona una imagen primero';
            statusDiv.className = 'text-sm text-center text-red-600';
            return;
        }

        // Mostrar estado
        btn.disabled = true;
        btn.innerText = '🔄 Subiendo...';
        statusDiv.textContent = 'Subiendo imagen...';
        statusDiv.className = 'text-sm text-center text-blue-600';

        // Crear FormData
        const formData = new FormData();
        formData.append('image', file);

        try {
            const response = await fetch('/api/flights/upload-image', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Guardar URL en el campo oculto
                document.getElementById('firebaseUrl').value = data.image_url;
                statusDiv.textContent = '✓ Imagen subida exitosamente';
                statusDiv.className = 'text-sm text-center text-green-600';
                console.log('URL de imagen: ' + data.image_url);
            } else {
                statusDiv.textContent = '❌ Error: ' + data.message;
                statusDiv.className = 'text-sm text-center text-red-600';
            }
        } catch (error) {
            statusDiv.textContent = '❌ Error de conexión';
            statusDiv.className = 'text-sm text-center text-red-600';
            console.error(error);
        } finally {
            btn.disabled = false;
            btn.innerText = '🔼 Subir Imagen';
        }
    }

</script>
</body>
</html>
