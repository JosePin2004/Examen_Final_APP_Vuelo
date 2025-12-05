<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - App Vuelo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-blue-600 p-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">✈ App Vuelo</h1>
            <div>
                <span id="user-name" class="text-white font-semibold mr-4">Cargando usuario...</span>
                <button onclick="logout()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm font-bold transition">
                    Cerrar Sesión
                </button>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto mt-10 p-6">
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Mis Reservaciones</h2>
            
            <div id="reservas-container" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <p class="text-gray-500">Cargando datos...</p>
            </div>
        </div>

    </div>

    <script>
        // 1. VERIFICAR TOKEN AL INICIAR
        const token = localStorage.getItem('token');
        
        if (!token) {
            // Si no hay token, fuera de aquí
            window.location.href = '/login';
        }

        // Ejecutar carga de datos
        document.addEventListener('DOMContentLoaded', () => {
            loadUser();
            loadReservations();
        });

        // 2. FUNCIÓN PARA OBTENER DATOS DEL USUARIO
        async function loadUser() {
            try {
                const response = await fetch('/api/user', {
                    method: 'GET',
                    headers: {
                        'Authorization': Bearer ${token}, // Enviamos la llave
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const user = await response.json();
                    document.getElementById('user-name').textContent = Hola, ${user.name};
                }
            } catch (error) {
                console.error("Error cargando usuario:", error);
            }
        }

        // 3. FUNCIÓN PARA CARGAR RESERVAS
        async function loadReservations() {
            try {
                // Asegúrate de que esta ruta '/api/reservations' exista en tu api.php
                const response = await fetch('/api/reservations', {
                    method: 'GET',
                    headers: {
                        'Authorization': Bearer ${token},
                        'Accept': 'application/json'
                    }
                });

                if (response.status === 401) {
                    logout(); // Token vencido
                    return;
                }

                const data = await response.json();
                renderReservations(data);

            } catch (error) {
                console.error(error);
                document.getElementById('reservas-container').innerHTML = 
                    '<p class="text-red-500">Error cargando reservaciones. Verifica que la ruta /api/reservations exista.</p>';
            }
        }

        // 4. FUNCIÓN PARA DIBUJAR LAS TARJETAS HTML
        function renderReservations(data) {
            const container = document.getElementById('reservas-container');
            container.innerHTML = ''; // Limpiar mensaje de carga

            // Si la respuesta viene envuelta en 'data', úsalo. Si no, usa 'data' directo.
            const lista = data.data || data; 

            if (lista.length === 0) {
                container.innerHTML = '<p class="text-gray-500">No tienes reservaciones aún.</p>';
                return;
            }

            lista.forEach(reserva => {
                // Personaliza esto con los campos reales de tu DB (ej: vuelo_id, fecha, etc)
                const card = `
                    <div class="border-l-4 border-blue-500 bg-gray-50 p-4 rounded shadow hover:shadow-lg transition">
                        <h3 class="font-bold text-lg text-gray-800">Reserva #${reserva.id}</h3>
                        <p class="text-gray-600 mt-2">Creada: ${new Date(reserva.created_at).toLocaleDateString()}</p>
                        </div>
                `;
                container.innerHTML += card;
            });
        }

        // 5. FUNCIÓN DE LOGOUT
        function logout() {
            // Opcional: Llamar a la API para invalidar el token en el servidor
            fetch('/api/logout', {
                method: 'POST',
                headers: { 'Authorization': Bearer ${token} }
            });

            localStorage.removeItem('token'); // Borrar llave del navegador
            window.location.href = '/login';  // Redirigir
        }
    </script>
</body>
</html>