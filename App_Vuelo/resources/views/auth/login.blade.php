<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - App Vuelo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">

    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-96 border border-gray-700">
        <h2 class="text-3xl font-bold text-white text-center mb-6">Iniciar Sesión</h2>
        
        <!-- FORMULARIO: id="loginForm" para capturarlo en JavaScript -->
        <form id="loginForm" class="space-y-4">
            <!-- Email: id="email" para obtener el valor con JavaScript -->
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Correo Electrónico</label>
                <input type="email" id="email" 
                    class="w-full p-3 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-blue-500" 
                    placeholder="admin@ejemplo.com" required>
            </div>
            
            <!-- Contraseña: id="password" para obtener el valor con JavaScript -->
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Contraseña</label>
                <input type="password" id="password" 
                    class="w-full p-3 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-blue-500" 
                    placeholder="********" required>
            </div>
            
            <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition duration-200">
                Ingresar
            </button>
        </form>

        <p id="mensaje" class="text-red-400 text-center mt-4 text-sm font-semibold hidden"></p>
        
        <div class="mt-6 text-center">
            <p class="text-gray-400 text-sm">¿No tienes cuenta? 
                <a href="/register" class="text-blue-400 hover:text-blue-300 font-bold">Regístrate aquí</a>
            </p>
        </div>
    </div>

    <script>
        // OBTENER ELEMENTOS DEL FORMULARIO
        const form = document.getElementById('loginForm');
        const mensaje = document.getElementById('mensaje');

        // ESCUCHAR CUANDO EL USUARIO ENVÍA EL FORMULARIO
        form.addEventListener('submit', async (e) => {
            e.preventDefault();  // Prevenir recarga de página
            mensaje.classList.add('hidden');
            mensaje.textContent = 'Procesando...';
            mensaje.classList.remove('hidden');

            // OBTENER VALORES DEL FORMULARIO
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                // ENVIAR PETICIÓN POST A /api/login (Backend)
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                // OBTENER RESPUESTA DEL BACKEND
                const data = await response.json();

                // VERIFICAR SI LOGIN FUE EXITOSO
                if (response.ok) {
                    //  ÉXITO: Guardar token en localStorage y redirigir
                    alert("¡Conexión Exitosa! Guardando llave y entrando...");
                    localStorage.setItem('token', data.access_token);  // Token para peticiones futuras
                    window.location.href = '/dashboard#reservations'; 
                } else {
                    //  ERROR: Mostrar mensaje de error
                    alert("Error del Servidor: " + (data.message || 'Desconocido'));
                    mensaje.textContent = data.message || 'Credenciales incorrectas.';
                }
            } catch (error) {
                // ERROR DE RED O JAVASCRIPT
                console.error(error);
                alert("Error Crítico: Revisa la consola (F12) o asegura que la base de datos esté bien.");
                mensaje.textContent = 'Error de conexión.';
            }
        });
    </script>
</body>
</html>