<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- ¡ESTA LÍNEA FALTA!
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin',
        'destination',
        'departure_time',
        'arrival_time',
        'price',
        'image_url',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}