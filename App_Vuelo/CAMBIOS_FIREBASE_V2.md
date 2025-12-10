# App Vuelo - Actualización Firebase e Imágenes

## Resumen de Cambios Realizados

### 1. ✅ Actualizado `FlightController.php`
- **Importaciones:** Agregadas `FirebaseService` y `Log`
- **`store()` method:** Ahora acepta `image` (archivo) en lugar de `image_url` (URL)
  - Valida: `image|mimes:jpeg,png,jpg,gif|max:5120` (5MB)
  - Llama `FirebaseService->uploadImage()` si Firebase está configurado
  - Continúa sin imagen si Firebase falla
- **`update()` method:** Soporta actualizar imagen
  - Elimina imagen anterior de Firebase si existe
  - Sube nueva imagen si se proporciona
- **`destroy()` method:** Elimina imagen de Firebase al eliminar vuelo

### 2. ✅ Actualizado `admin.blade.php`
- **Campo de imagen:** Cambió de URL input a file input
  - Soporta drag-and-drop de archivos
  - Preview en tiempo real de imagen seleccionada
  - Botón para limpiar selección
- **Migración Tailwind:** Cambió de CDN a `@vite()`
- **FormData:** JavaScript ahora usa FormData para enviar archivos
- **Funciones agregadas:**
  - `clearImagePreview()`: Limpia vista de imagen

### 3. ✅ Actualizado `welcome.blade.php`
- **Migración Tailwind:** Cambió de CDN a `@vite()`
- El catálogo ya estaba funcionando correctamente

### 4. ✅ Mejorado `FirebaseService.php`
- **Error handling:** Try-catch en todas las operaciones
- **Logging:** Log::info() y Log::error() para debugging
- **Nuevo método:** `deleteImage($filePath)` para eliminar archivos
- **Nuevo método:** `isConfigured()` para verificar configuración
- **Carpeta default:** 'vuelos' en lugar de 'juegos'
- **Headers:** Cache control para imágenes

### 5. ✅ Creado `FIREBASE_SETUP.md`
- Guía paso a paso para obtener credenciales
- Instrucciones para descargar clave JSON
- Configuración de Rules en Firebase Storage
- Verificación de configuración

## Variables de Entorno Requeridas
```
FIREBASE_CREDENTIALS=storage/appvuelo-firebase.json
FIREBASE_STORAGE_BUCKET=appvuelo-8221a.firebasestorage.app
```

## Flujo de Subida de Imagen

### Crear Vuelo con Imagen
```
1. Admin selecciona archivo en formulario
2. JavaScript muestra preview
3. Al guardar: FormData envía archivo + datos del vuelo
4. FlightController valida imagen
5. FirebaseService sube a Firebase Storage
6. URL retornada se guarda en DB (image_url)
```

### Editar Vuelo con Nueva Imagen
```
1. Cargar vuelo existente
2. Si hay imagen anterior: FirebaseService la elimina
3. Subir nueva imagen a Firebase
4. Actualizar URL en BD
```

### Eliminar Vuelo
```
1. Si tiene imagen_url: Eliminar de Firebase
2. Cancelar reservas asociadas
3. Eliminar registro de vuelo
```

## Testing del Sistema

### Verificar Firebase configurado
```bash
php artisan tinker
>>> \App\Services\FirebaseService::isConfigured()
=> true
```

### Crear vuelo con imagen (curl)
```bash
curl -X POST http://localhost:8000/api/flights \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "origin=Quito" \
  -F "destination=Guayaquil" \
  -F "departure_time=2025-12-15T10:00" \
  -F "arrival_time=2025-12-15T12:00" \
  -F "price=99.99" \
  -F "image=@/path/to/image.jpg"
```

## Próximos Pasos (Opcionales)

### 1. Mostrar imágenes en catálogo
- Actualizar welcome.blade.php para mostrar `flight.image_url`
- Agregar estilos para imágenes responsivas

### 2. Validar imágenes antes de subir
- JavaScript side: Verificar dimensiones y tamaño
- Server side: ImageInterventionlib para validar contenido

### 3. Implementar crop/resize
- Redimensionar imágenes antes de subir
- Generar thumbnails para listado

### 4. Caché de imágenes
- Agregar cache headers a Fire base URLs
- Implementar CDN si es necesario

## Archivos Modificados

```
✅ app/Http/Controllers/Api/FlightController.php
✅ resources/views/admin.blade.php
✅ resources/views/welcome.blade.php
✅ app/Services/FirebaseService.php (ya estaba mejorado)
✅ FIREBASE_SETUP.md (creado)
```

## Estado Actual

| Componente | Estado | Notas |
|-----------|--------|-------|
| Firebase Service | ✅ Listo | Error handling + deleteImage() |
| FlightController | ✅ Listo | Integrado con Firebase |
| Admin Panel | ✅ Listo | File upload + preview |
| Welcome Page | ✅ Listo | @vite() Tailwind |
| Credenciales | ❌ Necesaria | Descargar de Google Cloud |

## Instrucciones para Probar

1. **Obtener credenciales Firebase:** Ver FIREBASE_SETUP.md
2. **Colocar archivo:** `storage/appvuelo-firebase.json`
3. **Compilar CSS:** `npm run build`
4. **Iniciar servidor:** `php artisan serve`
5. **Vite dev:** `npm run dev` (en otra terminal)
6. **Ir a admin:** http://localhost:8000/admin
7. **Crear vuelo:** Usar formulario con imagen
8. **Verificar:** Imagen debe estar en Firebase Storage

---

**Última actualización:** Dic 2025
**Versión:** 2.0 (Firebase Integration)
