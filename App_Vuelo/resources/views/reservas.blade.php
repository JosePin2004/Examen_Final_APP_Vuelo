<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Reservas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-blue-600">Mis Reservas (Desde la API)</h1>

        <div id="lista-reservas" class="space-y-4">
            <p class="text-gray-500">Cargando datos...</p>
        </div>
    </div>

    <script>
        // Esta función se ejecuta al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Llamamos a tu API (Asegúrate que la ruta sea correcta)
            fetch('/api/reservations', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    // Si tienes autenticación, aquí deberías enviar el token
                    // 'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(response => response.json())
            .then(data => {
                const contenedor = document.getElementById('lista-reservas');
                contenedor.innerHTML = ''; // Limpiar el "Cargando..."

                // 2. Recorremos los datos recibidos
                // (Ajusta 'data' según si tu API devuelve un array directo o un objeto con 'data')
                data.forEach(reserva => {
                    // Creamos el HTML para cada item
                    const item = `
                        <div class="border-b border-gray-200 pb-2">
                            <p class="font-bold text-lg">Reserva ID: ${reserva.id}</p>
                            <p class="text-gray-600">Fecha: ${reserva.created_at}</p>
                            </div>
                    `;
                    contenedor.innerHTML += item;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('lista-reservas').innerHTML = 
                    '<p class="text-red-500">Error al cargar datos. Revisa la consola (F12).</p>';
            });
        });
    </script>
</body>
</html>