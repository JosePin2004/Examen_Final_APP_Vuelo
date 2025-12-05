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
        
        <form id="loginForm" class="space-y-4">
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Correo Electrónico</label>
                <input type="email" id="email" 
                    class="w-full p-3 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-blue-500" 
                    placeholder="admin@ejemplo.com" required>
            </div>
            
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
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const mensaje = document.getElementById('mensaje');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            mensaje.classList.add('hidden');
            mensaje.textContent = 'Procesando...';
            mensaje.classList.remove('hidden');

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                // Petición a la API
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok) {
                    // ÉXITO
                    alert("¡Conexión Exitosa! Guardando llave y entrando...");
                    localStorage.setItem('token', data.access_token); 
                    window.location.href = '/dashboard'; 
                } else {
                    // ERROR CONOCIDO (Contraseña mal, etc)
                    alert("Error del Servidor: " + (data.message || 'Desconocido'));
                    mensaje.textContent = data.message || 'Credenciales incorrectas.';
                }
            } catch (error) {
                // ERROR DE RED O CÓDIGO
                console.error(error);
                alert("Error Crítico: Revisa la consola (F12) o asegura que la base de datos esté bien.");
                mensaje.textContent = 'Error de conexión.';
            }
        });
    </script>
</body>
</html>