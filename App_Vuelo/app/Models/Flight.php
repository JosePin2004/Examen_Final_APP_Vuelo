<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [ // Campos que se llean
        'origin',
        'destination',
        'departure_time',
        'arrival_time',
        'price',
        'image_url',
        'economy_seats',
        'business_seats',
        'economy_price',
        'business_price',
    ];

    protected function casts(): array // Tipos de datos
    {
        return [
            'departure_time' => 'datetime',
            'arrival_time' => 'datetime',
            'price' => 'decimal:2',
            'economy_price' => 'decimal:2',
            'business_price' => 'decimal:2',
        ];
    }

    public function reservations() // Relación con Reservas
    {
        return $this->hasMany(Reservation::class); //1-m
    }
}