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
                ✈ App Vuelo
            </h1>
            <div class="flex gap-4 items-center">
                <a href="/admin" id="adminLink" class="hidden bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded text-sm font-bold transition shadow">
                    👨‍✈️ Panel Admin
                </a>
                <button onclick="logout()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm font-bold transition shadow">
                    Cerrar Sesión
                </button>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto mt-8 p-6 grid gap-8 md:grid-cols-3">
        
        <div class="md:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Nueva Reserva</h2>
                <form id="createForm" class="space-y-4">
                    <div>
                        <label class="block text-gray-600 text-sm font-bold mb-2">ID del Vuelo</label>
                        <input type="number" id="flight_id" 
                               class="w-full p-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                               placeholder="Ej: 3" required>
                        <p class="text-xs text-gray-400 mt-1">Ingresa el ID de un vuelo existente (ej: 3)</p>
                    </div>
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition shadow-md flex justify-center items-center gap-2">
                        <span>+</span> Reservar Vuelo
                    </button>
                </form>
            </div>
        </div>

        <div class="md:col-span-2">
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

    <script>
        const token = localStorage.getItem('token');
        if (!token) window.location.href = '/login';

        // CARGAR AL INICIO
        document.addEventListener('DOMContentLoaded', () => {
            loadReservations();
            checkIfAdmin();
        });

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

        // 1. FUNCIÓN CARGAR
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

        // 2. FUNCIÓN CREAR
        document.getElementById('createForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const flightId = document.getElementById('flight_id').value;

            try {
                const response = await fetch('/api/reservations', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ flight_id: flightId })
                });

                const data = await response.json();

                if (response.ok) {
                    alert('✓ ¡Reserva creada exitosamente!');
                    document.getElementById('flight_id').value = ''; // Limpiar campo
                    loadReservations(); // Recargar lista
                } else {
                    alert('❌ Error: ' + (data.message || 'Verifica que el ID del vuelo exista.'));
                }
            } catch (error) {
                alert('❌ Error de conexión');
                console.error(error);
            }
        });

        // 3. FUNCIÓN ELIMINAR
        async function deleteReservation(id) {
            if(!confirm('¿Estás seguro de cancelar esta reserva?')) return;

            try {
                const response = await fetch(`/api/reservations/${id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });

                if (response.ok) {
                    loadReservations(); // Recargar lista
                } else {
                    alert('No se pudo eliminar.');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // RENDERIZAR HTML
        function renderReservations(data) {
            const container = document.getElementById('reservas-container');
            container.innerHTML = '';
            const lista = data.data || data;

            // Filtrar solo las reservas no canceladas
            const activeReservations = lista.filter(r => r.status !== 'cancelled');

            if (activeReservations.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-400 py-10">No tienes reservaciones activas.</div>';
                return;
            }

            activeReservations.forEach(reserva => {
                const statusClass = reserva.status === 'confirmed' ? 'text-green-600' : 'text-yellow-600';
                const statusText = reserva.status === 'confirmed' ? '✓ Confirmada' : '⏳ Pendiente';
                
                const item = `
                    <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center hover:shadow-md transition bg-gray-50">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wide">Reserva #${reserva.id}</p>
                            <p class="text-lg font-bold text-gray-800">Vuelo ID: ${reserva.flight_id}</p>
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

        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
    </script>
</body>
</html>
