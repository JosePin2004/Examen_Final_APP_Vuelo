<?php

namespace App\Services;

use Kreait\Firebase\Factory; // Factory herramienta que te permite configurar y generar las conexiones a los distintos servicios de Firebase
use Exception;

class FirebaseService
{
    protected $storage; 
    protected $bucketName; 

    public function __construct() // Constructor para conectar con Firebase
    {
        try {
            // 1. Cargamos las credenciales y conectamos con Firebase
            $credentialsPath = base_path(env('FIREBASE_CREDENTIALS')); 
            
            if (!file_exists($credentialsPath)) {
                throw new Exception("Firebase credentials file not found at: {$credentialsPath}"); 
            }

            $factory = (new Factory) 
                ->withServiceAccount($credentialsPath);  

            // 2. Iniciamos el servicio de Storage
            $this->storage = $factory->createStorage(); 
            $this->bucketName = env('FIREBASE_STORAGE_BUCKET'); 
        } catch (Exception $e) { 
            \Log::error('Firebase initialization error: ' . $e->getMessage());
            throw $e;
        }
    }

    
    public function uploadImage($file, $folder = 'vuelos') // funcion para subir imagenes
    {
        try {
            // Generamos un nombre único: vuelos/123456789_imagen.jpg
            $fileName = $folder . '/' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $bucket = $this->storage->getBucket($this->bucketName);  //bucket es el contenedor donde se almacenan los archivos en la nube

            // Subimos el archivo
            $object = $bucket->upload(
                fopen($file->getPathname(), 'r'), 
                [
                    'name' => $fileName, 
                    'metadata' => [ 
                        'cacheControl' => 'public, max-age=3600',
                    ]
                ]
            );

            // Hacemos el archivo público
            $object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);

            // Retornamos la URL pública
            $publicUrl = "https://storage.googleapis.com/{$this->bucketName}/{$fileName}";
            \Log::info("Archivo subido exitosamente: {$publicUrl}");
            
            return $publicUrl;
        } catch (Exception $e) {
            \Log::error('Error uploading file to Firebase: ' . $e->getMessage());
            throw new Exception('Error al subir archivo a Firebase: ' . $e->getMessage());
        }
    }

    
     // Elimina un archivo de Firebase Storage
    
    public function deleteImage($filePath)
    {
        try {
            // Extraer la ruta relativa de la URL
            $relativePath = str_replace("https://storage.googleapis.com/{$this->bucketName}/", "", $filePath);
            
            $bucket = $this->storage->getBucket($this->bucketName); 
            $bucket->object($relativePath)->delete(); // Eliminar el archivo
            
            \Log::info("Archivo eliminado exitosamente: {$filePath}");
            return true;
        } catch (Exception $e) {
            \Log::error('Error deleting file from Firebase: ' . $e->getMessage());
            return false;
        }
    }

    
      //Verifica si el archivo de credenciales existe
    
    public static function isConfigured()
    {
        return file_exists(base_path(env('FIREBASE_CREDENTIALS'))) 
            && !empty(env('FIREBASE_STORAGE_BUCKET'));
    }
}
