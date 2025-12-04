<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - App Vuelo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 flex items-center justify-center h-screen">

    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-96 border border-gray-700">
        <h2 class="text-2xl font-bold text-white text-center mb-6">Iniciar Sesión</h2>
        
        <form id="loginForm">
            <div class="mb-4">
                <label class="block text-gray-300 text-sm font-bold mb-2">Correo Electrónico</label>
                <input type="email" id="email" 
                       class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-blue-500" 
                       placeholder="admin@ejemplo.com"
                       required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-300 text-sm font-bold mb-2">Contraseña</label>
                <input type="password" id="password" 
                       class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600 focus:outline-none focus:border-blue-500" 
                       placeholder="********"
                       required>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                Ingresar
            </button>
        </form>

        <p id="mensaje" class="text-red-500 text-center mt-4 text-sm hidden"></p>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const mensaje = document.getElementById('mensaje');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            mensaje.classList.add('hidden'); // Ocultar mensaje previo

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                // Hacemos la petición a TU API de Login
                // Asegúrate que tu ruta en api.php sea '/login' o ajusta esta url
                const response = await fetch