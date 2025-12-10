# 🚀 Próximos Pasos - App Vuelo con Firebase

## 1. OBTENER CREDENCIALES FIREBASE (Necesario)

### Opción A: Si ya tienes proyecto en Firebase
1. Ve a https://console.firebase.google.com/
2. Selecciona proyecto: **appvuelo-8221a**
3. Configuración (⚙️) → Cuentas de servicio
4. Haz clic en "Generar clave privada nueva"
5. Se descargará `appvuelo-xxxxx.json`

### Opción B: Si es un proyecto nuevo
1. Crea proyecto en: https://firebase.google.com/
2. Nombre: appvuelo
3. Region: Sudamérica (para mejor latencia)
4. Activa Firebase Storage
5. Genera clave como en Opción A

### Colocar el archivo:
```
c:\Users\José\Desktop\Aplicaciones Web\Pagina web\Examen_Final_APP_Vuelo\App_Vuelo\storage\appvuelo-firebase.json
```

**Importante:** Este archivo NO debe subirse a git (ya está en .gitignore)

---

## 2. COMPILAR Y PROBAR

```bash
# En la carpeta del proyecto
cd "c:\Users\José\Desktop\Aplicaciones Web\Pagina web\Examen_Final_APP_Vuelo\App_Vuelo"

# Compilar CSS (ya hecho, pero hacerlo nuevamente no daña)
npm run build

# Iniciar servidor Laravel (en una terminal)
php artisan serve

# En OTRA terminal, iniciar Vite dev (para hot reload)
npm run dev
```

**Resultado esperado:**
- Laravel corriendo en http://localhost:8000
- Vite en http://localhost:5173
- CSS compilado en `/public/build/assets/`

---

## 3. PROBAR EL FLUJO COMPLETO

### Acceder a Admin
1. Ve a: http://localhost:8000/admin
2. Email: `admin@example.com`
3. Contraseña: `password123`

### Crear Vuelo con Imagen
1. En "Gestionar Vuelos" → formulario de la izquierda
2. Llenar datos:
   - Origen: "Quito (UIO)"
   - Destino: "Guayaquil (GYE)"
   - Salida: Mañana a las 10:00 AM
   - Llegada: Mañana a las 12:00 PM
   - Precio: 99.99
   - **Imagen:** Seleccionar foto de avión/paisaje

3. Haz click en "Guardar Vuelo"

**Si Firebase está configurado:**
- ✅ Imagen se sube a Firebase Storage
- ✅ URL se guarda en base de datos
- ✅ Vuelo aparece en la lista

**Si Firebase no está configurado:**
- ⚠️ Vuelo se crea pero SIN imagen
- ⚠️ Verás logs en `storage/logs/laravel.log`

---

## 4. VERIFICAR FIREBASE

### En la consola de Laravel
```bash
php artisan tinker

>>> \App\Services\FirebaseService::isConfigured()
=> true  // Si todo está bien
=> false // Si faltan credenciales

>>> exit  // Para salir
```

### En Firebase Console
1. Ve a: https://console.firebase.google.com/project/appvuelo-8221a/storage/
2. Folder: `vuelos/` debe contener tus imágenes
3. Puedes descargarlas o eliminarlas desde ahí

---

## 5. FLUJOS DISPONIBLES

### ✅ Crear Vuelo
```
[Formulario] → FormData con imagen → API /api/flights → Firebase
Resultado: Vuelo con imagen en BD
```

### ✅ Editar Vuelo
```
[Cargar vuelo] → [Nueva imagen?] 
  SÍ: Elimina anterior de Firebase → Sube nueva → Actualiza URL
  NO: Mantiene imagen anterior
```

### ✅ Eliminar Vuelo
```
[Confirmación] → Elimina imagen de Firebase → Cancela reservas → Elimina vuelo
```

### ✅ Ver Catálogo
```
[Welcome page] → API /api/flights → Muestra lista de vuelos
[Click Reservar] → Si no autenticado → Redirige a login
              → Si autenticado → Abre dashboard con vuelo preseleccionado
```

---

## 6. POSIBLES ERRORES Y SOLUCIONES

### Error: "Firebase credentials file not found"
**Causa:** Archivo `appvuelo-firebase.json` no está en `storage/`
**Solución:** Descargar clave de Firebase Console y colocar en carpeta

### Error: "403 Forbidden - Storage bucket not accessible"
**Causa:** Permisos incorrectos en Firebase Storage Rules
**Solución:** 
1. Firebase Console → Storage → Rules
2. Reemplazar con estas reglas:
```
rules_version = '2';
service firebase.storage {
  match /b/{bucket}/o {
    match /vuelos/{allPaths=**} {
      allow read: if request.auth != null;
      allow write: if request.auth != null;
      allow delete: if request.auth != null;
    }
  }
}
```

### Imagen se crea pero no se sube a Firebase
**Causa:** Firebase no está configurado, pero la app continúa
**Solución:** Verificar `storage/logs/laravel.log` para error específico

### CDN Tailwind aparece en lugar de estilos compilados
**Causa:** Vite no está compilando CSS
**Solución:** Ejecutar `npm run build` nuevamente

---

## 7. ARCHIVOS CLAVE

| Archivo | Propósito |
|---------|-----------|
| `app/Services/FirebaseService.php` | Maneja uploads/deletes en Firebase |
| `app/Http/Controllers/Api/FlightController.php` | API endpoints para vuelos |
| `resources/views/admin.blade.php` | Panel de administración |
| `resources/views/welcome.blade.php` | Página principal con catálogo |
| `storage/appvuelo-firebase.json` | **NECESARIA** - Credenciales (no versionada) |
| `.env` | Contiene rutas a credenciales |

---

## 8. COMANDOS RÁPIDOS

```bash
# Compilar CSS production
npm run build

# Watch mode para desarrollo
npm run dev

# Iniciar servidor Laravel
php artisan serve

# Ver logs en tiempo real
tail -f storage/logs/laravel.json

# Limpiar caché Laravel
php artisan cache:clear

# Resetear base de datos (destructivo)
php artisan migrate:refresh --seed

# Ejecutar SQL interactivo
php artisan tinker
```

---

## 9. CHECKLIST FINAL

- [ ] Archivo `appvuelo-firebase.json` colocado en `storage/`
- [ ] Variables de entorno en `.env` configuradas
- [ ] `npm run build` ejecutado exitosamente
- [ ] Servidor Laravel iniciado (`php artisan serve`)
- [ ] Vite dev server iniciado (`npm run dev`)
- [ ] Acceso a admin correctamente autenticado
- [ ] Formulario de vuelo muestra campo de imagen
- [ ] Prueba crear vuelo sin imagen → ✅ Funciona
- [ ] Prueba crear vuelo CON imagen → ✅ Se sube a Firebase
- [ ] Verificar imagen en Firebase Console Storage
- [ ] Editar vuelo y cambiar imagen → ✅ Actualiza correctamente
- [ ] Eliminar vuelo → Imagen se elimina de Firebase

---

## 10. SOPORTE Y DEBUGGING

### Ver logs detallados
```bash
tail -f storage/logs/laravel.log
```

### Debugging JavaScript en navegador
- F12 → Console
- Buscar mensajes: `Error cargando`, `Firebase`
- Network tab → Ver respuestas de `/api/flights`

### Debugging PHP con Tinker
```php
>>> $flight = \App\Models\Flight::find(1);
>>> dd($flight->image_url);  // Ver URL de imagen
>>> \App\Services\FirebaseService::isConfigured();  // Verificar Firebase
```

---

**¡Listo para comenzar! Cualquier duda sobre Firebase o los cambios, pregunta sin problema.**

*Última actualización: Dic 2025*
