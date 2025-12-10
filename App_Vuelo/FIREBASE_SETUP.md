# Configuración de Firebase para App Vuelo

## Estado Actual
✅ Archivo `FirebaseService.php` está listo y adaptado
❌ Archivo de credenciales `storage/appvuelo-firebase.json` **NO EXISTE**

## Pasos para Obtener las Credenciales

### 1. Acceder a Google Cloud Console
1. Ve a: https://console.cloud.google.com/
2. Inicia sesión con tu cuenta Google
3. Selecciona el proyecto **"appvuelo-8221a"** (o crea uno nuevo si no existe)

### 2. Generar Clave de Servicio
1. En la barra lateral izquierda, ve a: **APIs & Services** → **Credentials**
2. Haz clic en **Create Credentials** → **Service Account**
3. Completa los campos:
   - **Service account name:** appvuelo-storage
   - **Service account ID:** appvuelo-storage (se genera automáticamente)
4. Haz clic en **Create and Continue**
5. En la siguiente pantalla, haz clic en **Continue** (sin asignar roles por ahora)
6. Haz clic en **Done**

### 3. Crear y Descargar Clave JSON
1. En la lista de cuentas de servicio, haz clic en la que acabas de crear
2. Ve a la pestaña **Keys**
3. Haz clic en **Add Key** → **Create new key**
4. Elige formato **JSON** y haz clic en **Create**
5. El archivo JSON se descargará automáticamente

### 4. Colocar el Archivo en el Proyecto
1. Guarda el archivo descargado con el nombre: `appvuelo-firebase.json`
2. Colócalo en la carpeta: `storage/appvuelo-firebase.json`
   - Ruta completa: `c:\Users\José\Desktop\Aplicaciones Web\Pagina web\Examen_Final_APP_Vuelo\App_Vuelo\storage\appvuelo-firebase.json`

### 5. Configurar Permisos en Firebase Storage
1. Ve a: **Storage** → **Rules**
2. Reemplaza las reglas con esto (para desarrollo):
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
3. Haz clic en **Publish**

## Variables de Entorno (Ya Configuradas)
```
FIREBASE_CREDENTIALS=storage/appvuelo-firebase.json
FIREBASE_STORAGE_BUCKET=appvuelo-8221a.firebasestorage.app
```

## Verificar la Configuración
Desde la terminal del proyecto, ejecuta:
```bash
php artisan tinker
>>> \App\Services\FirebaseService::isConfigured()
=> true  // Si todo está bien
```

## Métodos Disponibles
```php
// Subir imagen
$service = new \App\Services\FirebaseService();
$url = $service->uploadImage($file, 'vuelos');

// Eliminar imagen
$service->deleteImage($imageUrl);

// Verificar configuración
\App\Services\FirebaseService::isConfigured();
```

## Próximos Pasos
Una vez que las credenciales estén en lugar, necesitamos:
1. ✅ Actualizar `FlightController.php` para guardar imágenes en Firebase
2. ✅ Modificar el formulario de vuelos para aceptar uploads
3. ✅ Mostrar imágenes en el catálogo de la página principal

---
**Documentación:**
- Firebase Storage: https://firebase.google.com/docs/storage
- Cloud Console: https://console.cloud.google.com/
