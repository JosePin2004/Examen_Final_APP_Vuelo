<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable; 

    
    protected $fillable = [ // Campos que se llenan
        'name',
        'email',
        'password', 
        'role',
    ];

    
    protected $hidden = [ // Campos ocultos
        'password',
        'remember_token',
    ];

    
    protected function casts(): array // tipos de datos
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación: Un usuario puede tener muchas reservas
    public function reservations()
    {
        return $this->hasMany(Reservation::class); // 1-m
    }
    
    // Función  para saber si es admin 
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
