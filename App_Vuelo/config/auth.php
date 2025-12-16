<?php

return [ //configuracion de la autenticacion

    

    'defaults' => [ //configuracion por defecto de la autenticacion
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

   

    'guards' => [ //configuracion de los guardias de autenticacion
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    

    'providers' => [ //configuracion de los proveedores de usuarios
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

       
    ],

    

    'passwords' => [ //configuracion de los restablecimientos de contraseñas
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],



    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800), //tiempo de espera para la confirmacion de la contraseña en segundos

];
