# 🏗️ Visualización del Proyecto App Vuelo

## Estructura del Proyecto Actual

```
App_Vuelo (raíz)
│
├── 📂 app/ (código fuente)
│   ├── 📂 Http/
│   │   ├── 📂 Controllers/Api/
│   │   │   └── 🔧 FlightController.php ⭐ (FIREBASE INTEGRATION)
│   │   ├── 📂 Middleware/
│   │   └── Requests/
│   │
│   ├── 📂 Models/
│   │   ├── 👤 User.php
│   │   ├── ✈️ Flight.php  ← tiene image_url field
│   │   └── 🎫 Reservation.php
│   │
│   ├── 📂 Services/
│   │   └── 🔥 FirebaseService.php ⭐ (IMPROVED)
│   │
│   └── 📂 Providers/
│       └── AppServiceProvider.php
│
├── 📂 database/
│   ├── 📂 migrations/ (7 migraciones)
│   │   ├── *_create_users_table.php
│   │   ├── *_create_flights_table.php (con image_url)
│   │   ├── *_create_reservations_table.php
│   │   ├── *_add_role_to_users_table.php
│   │   ├── *_create_personal_access_tokens_table.php
│   │   └── ...
│   │
│   ├── 📂 factories/
│   │   └── UserFactory.php
│   │
│   └── 📂 seeders/
│       └── DatabaseSeeder.php
│
├── 📂 resources/ (Frontend)
│   ├── 📂 css/
│   │   └── 🎨 app.css ⭐ (Tailwind directives)
│   │       └─ @tailwind base; components; utilities;
│   │
│   ├── 📂 js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   │
│   └── 📂 views/ (Blade templates)
│       ├── 🌐 welcome.blade.php ⭐ (@vite + catálogo)
│       ├── 📊 dashboard.blade.php ⭐ (@vite + reservas)
│       ├── ⚙️ admin.blade.php ⭐ (@vite + upload images)
│       ├── 🎟️ reservas.blade.php ⭐ (@vite)
│       │
│       ├── 📂 auth/
│       │   ├── login.blade.php ⭐ (@vite)
│       │   └── register.blade.php ⭐ (@vite)
│       │
│       └── 📂 errors/
│           └── 403.blade.php ⭐ (@vite)
│
├── 📂 routes/
│   ├── 🔗 api.php (22+ endpoints REST)
│   │   ├── POST   /api/flights
│   │   ├── GET    /api/flights
│   │   ├── GET    /api/flights/{id}
│   │   ├── PUT    /api/flights/{id}
│   │   ├── DELETE /api/flights/{id}
│   │   ├── POST   /api/reservations
│   │   ├── GET    /api/reservations
│   │   ├── PUT    /api/reservations/{id}
│   │   ├── DELETE /api/reservations/{id}
│   │   ├── GET    /api/admin/reservations
│   │   └── ... más
│   │
│   ├── 🔗 web.php (Blade routes)
│   │   ├── GET  / (welcome)
│   │   ├── GET  /dashboard (user)
│   │   ├── GET  /admin (admin only)
│   │   ├── GET  /reservas (user)
│   │   └── GET  /login, /register (auth routes)
│   │
│   └── 🔗 console.php (CLI commands)
│
├── 📂 public/
│   ├── index.php (punto de entrada)
│   ├── robots.txt
│   │
│   └── 📂 build/ ✅ (GENERADO por Vite)
│       ├── manifest.json
│       └── 📂 assets/
│           ├── app-*.css (9.76 KB, 2.20 KB gzip)
│           └── app-*.js (36.35 KB, 14.71 KB gzip)
│
├── 📂 storage/ 📁 (IMPORTANTE)
│   ├── ❌ appvuelo-firebase.json (FALTA - necesaria)
│   ├── 📂 app/public/ (uploads locales)
│   ├── 📂 framework/ (cache, sessions)
│   └── 📂 logs/
│       └── laravel.log (debugging)
│
├── 📂 config/ (Configuración)
│   ├── app.php (APP_NAME, APP_DEBUG, etc)
│   ├── database.php (DB_CONNECTION, DB_HOST, etc)
│   ├── cache.php
│   ├── auth.php
│   ├── filesystems.php
│   ├── mail.php
│   ├── session.php
│   └── ...
│
├── 📂 bootstrap/ (Inicialización)
│   ├── app.php (Service container)
│   ├── providers.php
│   └── 📂 cache/
│
├── 📂 vendor/ 📦 (Dependencias - NO editar)
│   ├── laravel/* (Framework)
│   ├── symfony/* (Utilities)
│   ├── guzzlehttp/* (HTTP client)
│   └── ... (88+ librerías)
│
├── 📂 node_modules/ 📦 (Node dependencies - NO editar)
│   ├── tailwindcss/
│   ├── vite/
│   ├── postcss/
│   ├── @tailwindcss/postcss (para Tailwind v4)
│   └── ... (130+ paquetes)
│
├── 📂 tests/ (Testing)
│   ├── TestCase.php
│   ├── 📂 Feature/
│   │   └── ExampleTest.php
│   └── 📂 Unit/
│       └── ExampleTest.php
│
├── 📄 .env.example (template, NO usar directamente)
├── 📄 .env ⚙️ (CONFIG LOCAL - NO en git)
│   ├── APP_NAME=App Vuelo
│   ├── APP_DEBUG=true
│   ├── DB_CONNECTION=sqlsrv (o sqlite)
│   ├── FIREBASE_CREDENTIALS=storage/appvuelo-firebase.json
│   └── FIREBASE_STORAGE_BUCKET=appvuelo-8221a.firebasestorage.app
│
├── 📄 .gitignore (qué NO versionear)
│   ├── /node_modules
│   ├── /vendor
│   ├── .env
│   ├── storage/appvuelo-firebase.json (secreto)
│   └── ...
│
├── 📄 package.json 📦 (Scripts y deps Node)
│   └─ "build": "vite build"
│   └─ "dev": "vite"
│
├── 📄 composer.json 📦 (Scripts y deps PHP)
│   └─ "scripts": { "post-install-cmd": [...] }
│
├── 📄 tailwind.config.js ✅
│   └─ content: ["./resources/**/*.blade.php"]
│
├── 📄 postcss.config.js ✅
│   └─ plugins: ["@tailwindcss/postcss"]
│
├── 📄 vite.config.js ✅
│   └─ plugins: [laravel(['resources/css/app.css'])]
│
├── 📄 phpunit.xml (configuración testing)
├── 📄 artisan (CLI de Laravel)
├── 📄 README.md
│
└── 📄 DOCUMENTACIÓN NUEVA:
    ├── 📋 FIREBASE_SETUP.md ⭐
    ├── 📋 CAMBIOS_FIREBASE_V2.md ⭐
    ├── 📋 PROXIMOS_PASOS.md ⭐
    ├── 📋 ESTADO_IMPLEMENTACION.md ⭐
    └── 📋 RESUMEN_FINAL.md ⭐
```

---

## 🔄 Flujo de Ejecución

### 1️⃣ Usuario accede a http://localhost:8000

```
Client Browser
       │
       ├─→ GET / (web.php routes)
       │     │
       │     └─→ welcome.blade.php
       │           │
       │           ├─→ @vite() carga CSS/JS
       │           │     └─→ /public/build/assets/app-*.{css,js}
       │           │
       │           └─→ JavaScript carga /api/flights
       │                 │
       │                 └─→ FlightController@index
       │                       │
       │                       └─→ BD: SELECT * FROM flights
       │                             │
       │                             └─→ JSON response a cliente
       │
       └─→ Renderiza catálogo de vuelos
```

### 2️⃣ Usuario intenta reservar (no autenticado)

```
Cliente clickea "Reservar"
       │
       └─→ JavaScript: handleReservation(flightId)
             │
             └─→ if (!isAuth) → window.location = '/login'
                   │
                   └─→ Redirige a LOGIN form
                         │
                         └─→ POST /login (validación)
                               │
                               └─→ Sanctum genera Bearer token
                                     │
                                     └─→ localStorage.setItem('auth_token', token)
                                           │
                                           └─→ Redirige a /dashboard
                                                 │
                                                 └─→ Dashboard con vuelo preseleccionado
```

### 3️⃣ Admin sube vuelo con imagen

```
Admin en /admin panel
       │
       ├─→ Rellena formulario
       │     ├─ Origen, destino, horarios
       │     ├─ Precio
       │     └─ Selecciona IMAGEN
       │           │
       │           └─→ JavaScript muestra PREVIEW
       │
       └─→ Click "Guardar Vuelo"
             │
             └─→ JavaScript crea FormData
                   │
                   ├─ Agrega campos de texto
                   └─ Agrega archivo de imagen
                         │
                         └─→ POST /api/flights
                               │
                               └─→ FlightController@store
                                     │
                                     ├─→ Valida datos (required, types, size)
                                     │
                                     ├─→ if (hasFile && FirebaseConfigured)
                                     │     │
                                     │     └─→ FirebaseService->uploadImage()
                                     │           │
                                     │           ├─→ Conecta a Firebase
                                     │           ├─→ Sube archivo a storage/vuelos/
                                     │           └─→ Retorna URL pública
                                     │
                                     ├─→ Flight::create() en BD
                                     │     └─ image_url = Firebase URL (o NULL)
                                     │
                                     └─→ JSON response (201 Created)
                                           │
                                           └─→ JavaScript lista vuelos actualizados
```

### 4️⃣ Admin edita vuelo y cambia imagen

```
Admin: editar(flightId)
       │
       ├─→ Carga datos actuales
       ├─→ Selecciona NUEVA imagen
       └─→ Click "Guardar"
             │
             └─→ PUT /api/flights/{id}
                   │
                   └─→ FlightController@update
                         │
                         ├─→ if (hasNewImage && OldImage exists)
                         │     │
                         │     └─→ FirebaseService->deleteImage(oldURL)
                         │           │
                         │           └─→ Elimina de Firebase Storage
                         │
                         ├─→ if (hasNewImage)
                         │     │
                         │     └─→ FirebaseService->uploadImage(newFile)
                         │           │
                         │           └─→ Nueva URL de Firebase
                         │
                         └─→ Flight::update() con nueva URL
                               │
                               └─→ JSON response
```

### 5️⃣ Admin elimina vuelo

```
Admin: deleteFlight(id)
       │
       ├─→ Confirmación: ¿Estás seguro?
       │
       └─→ DELETE /api/flights/{id}
             │
             └─→ FlightController@destroy
                   │
                   ├─→ if (flight.image_url exists)
                   │     │
                   │     └─→ FirebaseService->deleteImage(imageURL)
                   │           │
                   │           └─→ Elimina de Firebase Storage
                   │
                   ├─→ Reservation::where(flight_id = id)
                   │     └─→ update(status = 'cancelled')
                   │
                   └─→ Flight::delete()
                         │
                         └─→ JSON response
```

---

## 🗄️ Base de Datos - Diagrama Relacional

```
┌─────────────────────┐
│       USERS         │
├─────────────────────┤
│ id (PK)             │
│ name                │
│ email (UNIQUE)      │◄─────┐
│ password (hash)     │      │
│ role (admin/user)   │      │ FK
│ email_verified_at   │      │
│ created_at          │      │
│ updated_at          │      │
└─────────────────────┘      │
                             │
                    ┌────────┴──────────┐
                    │                   │
        ┌───────────────────┐  ┌─────────────────────┐
        │    FLIGHTS        │  │   RESERVATIONS      │
        ├───────────────────┤  ├─────────────────────┤
        │ id (PK)           │  │ id (PK)             │
        │ code              │  │ user_id (FK→Users)  │
        │ origin            │◄─│ flight_id (FK)      │
        │ destination       │  │ status              │
        │ departure_time    │  │ notes               │
        │ arrival_time      │  │ created_at          │
        │ price             │  │ updated_at          │
        │ image_url ⭐      │  └─────────────────────┘
        │ created_at        │
        │ updated_at        │
        └───────────────────┘

Relaciones:
  - User hasMany Reservations
  - Flight hasMany Reservations
  - Reservation belongsTo User, Flight
```

---

## 🔐 JWT/Sanctum Token Flow

```
Login Request
│
├─→ User::where('email', ...)->first()
├─→ Hash::check(password, user.password)
│
└─→ if (valid) → user->createToken('api-token')
                   │
                   └─→ Retorna: { 'access_token': 'xxxxx' }
                         │
                         └─→ Cliente: localStorage.setItem('auth_token', 'xxxxx')
                               │
                               └─→ Cada request lleva:
                                     Header: Authorization: Bearer xxxxx
                                           │
                                           └─→ Middleware verifica token
                                                 │
                                                 ├─→ Si válido → Auth::user() disponible
                                                 └─→ Si inválido → 401 Unauthorized
```

---

## 📱 Responsive Breakpoints (Tailwind)

```
Mobile (< 640px)        Tablet (640px-1024px)    Desktop (> 1024px)
┌──────────────────┐   ┌─────────────────────┐  ┌──────────────────────┐
│  Welcome Page    │   │  Welcome Page       │  │  Welcome Page        │
│  - Full width    │   │  - 2 columns        │  │  - Hero + Grid       │
│  - Stacked       │   │  - Centered         │  │  - Sidebar optional  │
│                  │   │                     │  │                      │
├──────────────────┤   ├─────────────────────┤  ├──────────────────────┤
│  Admin Panel     │   │  Admin Panel        │  │  Admin Panel         │
│  - Tabs apilados │   │  - 3 col layout     │  │  - 3 col optimizado  │
│  - Mobile menu   │   │  - Sticky form      │  │  - Sticky sidebar    │
└──────────────────┘   └─────────────────────┘  └──────────────────────┘

Grid classes:
  grid-cols-1         (mobile)
  md:grid-cols-2      (tablet)
  lg:grid-cols-3      (desktop)
```

---

## 🎯 Estado de cada Vista

### welcome.blade.php
```
┌─────────────────────────────────────────────┐
│  HEADER (sticky)                            │
│  Logo | [Login] [Register] [Dashboard]      │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│  HERO SECTION                               │
│  "Encuentra tu próximo vuelo"               │
│  [Ver Catálogo] [Login]                     │
│  Quick Preview: 4 vuelos                    │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│  CATALOG SECTION                            │
│  Grid de TODOS los vuelos                   │
│  Cada card: origen → destino, precio, botón│
│  Filter/Search (si implementado)            │
└─────────────────────────────────────────────┘

┌─────────────────────────────────────────────┐
│  FOOTER                                     │
│  Laravel v11 | PHP v8.2                     │
└─────────────────────────────────────────────┘
```

### admin.blade.php
```
┌────────────────────────────────────────────────┐
│  NAV (sticky)                                  │
│  Logo | Usuario: admin@example.com | Logout  │
└────────────────────────────────────────────────┘

┌────────────────────────────────────────────────┐
│  TABS: [Gestionar Vuelos] [Reservaciones]    │
└────────────────────────────────────────────────┘

TAB: Gestionar Vuelos
┌──────────────────┬─────────────────────────────┐
│  FORMULARIO      │  LISTA DE VUELOS            │
│  (izquierda)     │  (derecha)                  │
│                  │                             │
│ ☐ Origen         │  📌 Vuelo #1                │
│ ☐ Destino        │  Quito → Guayaquil          │
│ ☐ Salida         │  $ 99.99  [Editar] [Borrar]│
│ ☐ Llegada        │                             │
│ ☐ Precio         │  📌 Vuelo #2                │
│ 📤 Imagen        │  ...                        │
│                  │                             │
│ [Guardar]        │                             │
└──────────────────┴─────────────────────────────┘

TAB: Reservaciones
┌────────────────────────────────────────────────┐
│  LISTA DE RESERVAS                             │
│                                                │
│  👤 usuario@email.com → ✈️ UAL100             │
│  Status: pending  [Aprobar] [Rechazar] [X]    │
│                                                │
│  👤 otro@email.com → ✈️ LAT200                │
│  Status: pending  [Aprobar] [Rechazar] [X]    │
│                                                │
│  ...                                           │
└────────────────────────────────────────────────┘
```

---

## 🚀 Flujo de Deploy a Producción

```
1. Compilar CSS/JS
   └─→ npm run build

2. Instalar dependencias PHP
   └─→ composer install --no-dev

3. Preparar .env producción
   ├─ APP_DEBUG=false
   ├─ APP_ENV=production
   ├─ DB_CONNECTION=production
   └─ FIREBASE_CREDENTIALS=/path/to/firebase.json

4. Migraciones
   └─→ php artisan migrate --env=production

5. Caché config
   └─→ php artisan config:cache
   └─→ php artisan route:cache

6. Servir con Nginx/Apache
   └─→ DocumentRoot → /public/

7. SSL/TLS
   └─→ Let's Encrypt certificate
```

---

**Esta estructura permite:**
✅ Escalabilidad (agregar más features fácilmente)
✅ Mantenibilidad (código organizado y documentado)
✅ Seguridad (validaciones en múltiples niveles)
✅ Performance (compilación Vite + caché Laravel)
✅ Testabilidad (modelos, controllers separados)

