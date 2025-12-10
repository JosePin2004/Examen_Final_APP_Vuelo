<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - App Vuelo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen py-10">

    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-96 border border-gray-700">
        <h2 class="text-3xl font-bold text-white text-center mb-6">Crear Cuenta</h2>
        
        <form id="registerForm" class="space-y-4">
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Nombre Completo</label>
                <input type="text" id="name" 
                    class="w-full p-3 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-blue-500" 
                    placeholder="Juan Pérez" required>
            </div>

            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Correo Electrónico</label>
                <input type="email" id="email" 
                    class="w-full p-3 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-blue-500" 
                    placeholder="correo@ejemplo.com" required>
            </div>
            
            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Contraseña</label>
                <input type="password" id="password" 
                    class="w-full p-3 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-blue-500" 
                    placeholder="••••••••" minlength="8" required>
                <p class="text-xs text-gray-400 mt-1">Mínimo 8 caracteres</p>
            </div>

            <div>
                <label class="block text-gray-300 text-sm font-bold mb-2">Confirmar Contraseña</label>
                <input type="password" id="password_confirmation" 
                    class="w-full p-3 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-blue-500" 
                    placeholder="••••••••" minlength="8" required>
            </div>
            
            <button type="submit" 
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded transition duration-200">
                Registrarse
            </button>
        </form>

        <p id="mensaje" class="text-red-400 text-center mt-4 text-sm font-semibold hidden"></p>
        
        <div class="mt-6 text-center">
            <p class="text-gray-400 text-sm">¿Ya tienes cuenta? 
                <a href="/login" class="text-blue-400 hover:text-blue-300 font-bold">Inicia sesión aquí</a>
            </p>
        </div>
    </div>

    <script>
        const form = document.getElementById('registerForm');
        const mensaje = document.getElementById('mensaje');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            mensaje.classList.add('hidden');

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;

            // Validar que las contraseñas coincidan
            if (password !== password_confirmation) {
                mensaje.textContent = 'Las contraseñas no coinciden.';
                mensaje.classList.remove('hidden');
                return;
            }

            if (password.length < 8) {
                mensaje.textContent = 'La contraseña debe tener mínimo 8 caracteres.';
                mensaje.classList.remove('hidden');
                return;
            }

            mensaje.textContent = 'Registrando...';
            mensaje.classList.remove('hidden');

            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        name, 
                        email, 
                        password,
                        password_confirmation
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    alert("Registro exitoso! Iniciando sesión...");
                    localStorage.setItem('token', data.access_token);
                    window.location.href = '/dashboard#reservations';
                } else {
                    mensaje.textContent = (data.message || 'Error al registrarse. Intenta otro correo.');
                    mensaje.classList.remove('hidden');
                }
            } catch (error) {
                console.error(error);
                mensaje.textContent = 'Error de conexión. Intenta más tarde.';
                mensaje.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
