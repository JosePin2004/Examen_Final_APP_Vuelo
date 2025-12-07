<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Admin - App Vuelo</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        </div>

        <!-- TAB: VUELOS -->
        <div id="flights-tab" class="tab-content block">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- FORMULARIO CREAR/EDITAR VUELO -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2" id="formTitle">Nuevo Vuelo</h2>
                        
                        <form id="flightForm" class="space-y-4">
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
                                <label class="block text-gray-600 text-sm font-bold mb-2">URL Imagen (Opcional)</label>
                                <input type="url" id="image_url" 
                                       class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                                       placeholder="https://ejemplo.com/imagen.jpg">
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition shadow-md">
                                Guardar Vuelo
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
                <div class="bg-orange-50 rounded-xl shadow p-6">
                    <p class="text-gray-600 text-sm font-bold">Ingresos (USD)</p>
                    <p class="text-3xl font-bold text-orange-600" id="stat-revenue">$0</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Últimas Reservas</h2>
                <div id="reservations-list" class="space-y-4">
                    <p class="text-gray-500 text-center py-10">Cargando reservas...</p>
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

        // FORMULARIO CREAR/EDITAR
        document.getElementById('flightForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const flightId = document.getElementById('flight_id').value;
            const isEdit = !!flightId;

            const data = {
                origin: document.getElementById('origin').value,
                destination: document.getElementById('destination').value,
                departure_time: document.getElementById('departure_time').value,
                arrival_time: document.getElementById('arrival_time').value,
                price: document.getElementById('price').value,
                image_url: document.getElementById('image_url').value || null,
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
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    alert(isEdit ? 'Vuelo actualizado' : 'Vuelo creado exitosamente');
                    resetForm();
                    loadFlights();
                } else {
                    alert('Error: ' + (result.message || 'Intenta de nuevo'));
                }
            } catch (error) {
                alert('Error de conexión');
                console.error(error);
            }
        });

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
                document.getElementById('image_url').value = flight.image_url || '';
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

                // Calcular ingresos
                let revenue = 0;
                reservations.forEach(res => {
                    const flight = flights.find(f => f.id === res.flight_id);
                    if (flight && res.status === 'confirmed') {
                        revenue += parseFloat(flight.price);
                    }
                });

                document.getElementById('stat-flights').textContent = flights.length;
                document.getElementById('stat-users').textContent = userData.count || 0;
                document.getElementById('stat-reservations').textContent = reservations.length;
                document.getElementById('stat-revenue').textContent = '$' + revenue.toFixed(2);

                renderReservations(reservations.slice(0, 10));
            } catch (error) {
                console.error(error);
            }
        }

        // RENDERIZAR RESERVAS
        function renderReservations(reservations) {
            const container = document.getElementById('reservations-list');
            container.innerHTML = '';

            if (reservations.length === 0) {
                container.innerHTML = '<p class="text-gray-400 text-center py-10">Sin reservas</p>';
                return;
            }

            reservations.forEach(res => {
                const statusColor = res.status === 'confirmed' ? 'green' : res.status === 'pending' ? 'yellow' : 'red';
                const statusText = res.status === 'confirmed' ? 'Confirmada' : res.status === 'pending' ? 'Pendiente' : 'Cancelada';

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

        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
    </script>
</body>
</html>
