# ⚡ Comandos Rápidos - App Vuelo

## 🚀 Inicio Rápido (3 comandos)

```bash
# Terminal 1: Laravel
cd "c:\Users\José\Desktop\Aplicaciones Web\Pagina web\Examen_Final_APP_Vuelo\App_Vuelo"
php artisan serve

# Terminal 2: Vite (en el mismo directorio)
npm run dev

# Terminal 3: Ir a http://localhost:8000
```

---

## 📦 Instalación (primera vez)

```bash
# Clonar credenciales de ejemplo
cp .env.example .env

# Instalar dependencias PHP
composer install

# Instalar dependencias Node
npm install

# Generar clave de app
php artisan key:generate

# Migrar base de datos
php artisan migrate

# Compilar CSS/JS
npm run build
```

---

## 🏃 Desarrollo Rápido

```bash
# Compilar CSS
npm run build

# Watch mode (hot reload)
npm run dev

# Iniciar servidor
php artisan serve

# Ver logs en vivo
tail -f storage/logs/laravel.log

# Base de datos interactiva
php artisan tinker

# Cache clear
php artisan cache:clear
```

---

## 🧪 Testing

```bash
# Ejecutar tests
php artisan test

# Tests específico
php artisan test --filter=FlightTest

# PHPUnit
./vendor/bin/phpunit

# PHPUnit archivo
./vendor/bin/phpunit tests/Feature/ExampleTest.php
```

---

## 🔄 Database

```bash
# Migrar
php artisan migrate

# Rollback último batch
php artisan migrate:rollback

# Rollback todo
php artisan migrate:refresh

# Rollback + seed
php artisan migrate:refresh --seed

# Ver status
php artisan migrate:status
```

---

## 🔐 Autenticación

```bash
# Ver usuario autenticado (en tinker)
>>> Auth::user()

# Logout todos los dispositivos
>>> Auth::user()->tokens()->delete()

# Crear nuevo token
>>> Auth::user()->createToken('api-token')->plainTextToken
```

---

## 🔥 Firebase

```bash
# Verificar si está configurado
php artisan tinker
>>> \App\Services\FirebaseService::isConfigured()
=> true  // o false

# Ver credenciales path
>>> getenv('FIREBASE_CREDENTIALS')
=> "storage/appvuelo-firebase.json"

# Ver bucket
>>> getenv('FIREBASE_STORAGE_BUCKET')
=> "appvuelo-8221a.firebasestorage.app"

# Exit tinker
>>> exit
```

---

## 📝 Logs y Debugging

```bash
# Ver último log
tail -f storage/logs/laravel.log

# Ver últimas 50 líneas
tail -50 storage/logs/laravel.log

# Búsqueda en logs
grep "error\|Error" storage/logs/laravel.log

# Limpiar logs
rm storage/logs/laravel.log

# Ver logs en tiempo real
tail -f storage/logs/laravel.log | grep "Firebase"
```

---

## 🎨 CSS/Frontend

```bash
# Compilar CSS (producción)
npm run build

# Watch CSS (desarrollo)
npm run dev

# Limpiar build anterior
rm -rf public/build

# Verificar CSS tamaño
wc -c public/build/assets/app-*.css

# Minify check
npm list --depth=0
```

---

## 🔍 API Testing

### Con cURL

```bash
# Login y obtener token
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password123"}'

# Obtener vuelos (sin autenticación)
curl http://localhost:8000/api/flights

# Crear vuelo (requiere token)
curl -X POST http://localhost:8000/api/flights \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "origin":"Quito",
    "destination":"Guayaquil",
    "departure_time":"2025-12-15T10:00:00",
    "arrival_time":"2025-12-15T12:00:00",
    "price":99.99
  }'

# Actualizar vuelo
curl -X PUT http://localhost:8000/api/flights/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"origin":"Cuenca"}'

# Eliminar vuelo
curl -X DELETE http://localhost:8000/api/flights/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Con Thunder Client / Postman

```
BASE_URL: http://localhost:8000/api

Endpoints:
  GET    /flights                    (público)
  GET    /flights/{id}               (público)
  POST   /flights                    (admin)
  PUT    /flights/{id}               (admin)
  DELETE /flights/{id}               (admin)
  
  POST   /reservations               (usuario)
  GET    /reservations               (usuario)
  PUT    /reservations/{id}          (usuario)
  DELETE /reservations/{id}          (usuario)
  
  GET    /admin/reservations         (admin)
  
  POST   /login                      (público)
  POST   /register                   (público)
  POST   /logout                     (autenticado)
  GET    /me                         (autenticado)
```

---

## 🛠️ Maintenance

```bash
# Limpiar todo cache
php artisan cache:clear
php artisan route:cache --clear
php artisan config:clear
php artisan view:clear

# Caché config (producción)
php artisan config:cache
php artisan route:cache

# Ver espacio de disco
du -sh ./*

# Limpiar node_modules (si es necesario reinstalar)
rm -rf node_modules
npm install
```

---

## 🚨 Troubleshooting Rápido

```bash
# Error de permisos
chmod -R 755 storage/

# Error de base de datos
php artisan migrate:refresh

# Error de CSS/JS
npm run build

# Token inválido
# → Limpiar localStorage en navegador (F12 → Application → localStorage)

# Puerto 8000 ocupado
php artisan serve --port=8001

# Error de Vite
npm install
npm run dev
```

---

## 📊 Información del Proyecto

```bash
# Ver versión Laravel
php artisan --version

# Ver versión PHP
php --version

# Ver versión Node
node --version

# Ver versión npm
npm --version

# Ver rutas
php artisan route:list

# Ver providers
php artisan provider:list

# Ver configuración app
php artisan config:show app
```

---

## 🔐 Credenciales Temporales

### Usuario Admin
```
Email: admin@example.com
Contraseña: password123
Rol: admin
```

### Usuario Regular
```
Email: user@example.com
Contraseña: password123
Rol: user
```

---

## 📝 Crear Nuevo Vuelo (SQL)

```sql
INSERT INTO flights 
(code, origin, destination, departure_time, arrival_time, price, image_url, created_at, updated_at) 
VALUES 
('UAL100', 'Quito', 'Guayaquil', '2025-12-15 10:00:00', '2025-12-15 12:00:00', 99.99, NULL, NOW(), NOW());
```

---

## 🗑️ Limpiar Base de Datos

```bash
# Opción 1: Migrar de nuevo (destructivo)
php artisan migrate:refresh --seed

# Opción 2: Eliminar vuelo específico (en tinker)
php artisan tinker
>>> \App\Models\Flight::find(1)->delete()

# Opción 3: Eliminar todos los vuelos
>>> \App\Models\Flight::truncate()

# Salir de tinker
>>> exit
```

---

## 📚 Rutas Útiles en la App

```
http://localhost:8000/              (Welcome page)
http://localhost:8000/dashboard     (User dashboard)
http://localhost:8000/admin         (Admin panel)
http://localhost:8000/login         (Login form)
http://localhost:8000/register      (Register form)

API:
http://localhost:8000/api/flights   (GET all flights)
http://localhost:8000/api/me        (GET current user)
```

---

## ⚙️ Variables de Entorno Clave

```bash
# En .env file:

# Base de datos
DB_CONNECTION=sqlsrv
DB_HOST=localhost
DB_DATABASE=app_vuelo

# Firebase
FIREBASE_CREDENTIALS=storage/appvuelo-firebase.json
FIREBASE_STORAGE_BUCKET=appvuelo-8221a.firebasestorage.app

# App
APP_NAME="App Vuelo"
APP_DEBUG=true
APP_ENV=local

# Auth
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000

# Session
SESSION_DRIVER=file
CACHE_DRIVER=file
```

---

## 🎯 Flujo de Trabajo Típico

```bash
# 1. Empezar el día
cd "App_Vuelo"
git pull origin main        # O Jose si trabajas en esa rama

# 2. Iniciar servidores (2 terminales)
php artisan serve
npm run dev

# 3. Desarrollar
# → Edita archivos
# → Hot reload automático con npm run dev
# → Abre http://localhost:8000

# 4. Ver cambios
# → F5 para refrescar
# → npm run build para compilar CSS final

# 5. Commit
git add .
git commit -m "descripción de cambios"
git push origin mi-rama

# 6. Terminar
php artisan cache:clear
git status
```

---

## 🔗 Útiles Links

```
Documentación:
- Laravel: https://laravel.com/docs
- Tailwind: https://tailwindcss.com
- Vite: https://vitejs.dev

Logs:
- storage/logs/laravel.log

Configuración:
- .env (variables de entorno)
- config/ (configuración)

Código:
- app/ (lógica)
- resources/ (vistas)
- routes/ (rutas)
- database/ (migraciones)
```

---

## 💾 Commit Rápido

```bash
# Ver qué cambió
git status

# Agregar todo
git add -A

# Commit con mensaje
git commit -m "descripción breve y clara"

# Push
git push origin nombre-rama

# Ver historial
git log --oneline -10
```

---

## 🚀 Deploy Quick

```bash
# 1. Build production
npm run build

# 2. Caché config
php artisan config:cache
php artisan route:cache

# 3. Migrar (si hay cambios de BD)
php artisan migrate --force

# 4. Limpiar
php artisan cache:clear

# 5. Verificar logs
tail -f storage/logs/laravel.log

# 6. Ir en vivo
# → Servidor debe servir desde /public
```

---

**Uso:** Copiar y pegar los comandos que necesites
**Contexto:** Siempre ejecutar desde raíz del proyecto
**Referencia:** Guardar este archivo para consultas rápidas

Última actualización: Diciembre 2025

