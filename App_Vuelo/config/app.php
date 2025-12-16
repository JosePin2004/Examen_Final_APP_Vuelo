<?php

return [ //configuracion principal de la aplicacion 

    

    'name' => env('APP_NAME', 'Laravel'), //nombre de la aplicacion


    'env' => env('APP_ENV', 'production'),//entorno de la aplicacion


    'debug' => (bool) env('APP_DEBUG', false),//modo debug que es para desarrollo de la aplicacion


    'url' => env('APP_URL', 'http://localhost'),//url de la aplicacion

    'timezone' => env('APP_TIMEZONE', 'UTC'),//zona horaria de la aplicacion


    'locale' => env('APP_LOCALE', 'en'),//idioma de la aplicacion

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),//idioma por defecto de la aplicacion

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),//idioma para datos de prueba

    'cipher' => 'AES-256-CBC',//tipo de cifrado

    'key' => env('APP_KEY'),//clave de cifrado

    'previous_keys' => [//claves anteriores para descifrado
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    

    'maintenance' => [ //configuracion del modo mantenimiento
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
