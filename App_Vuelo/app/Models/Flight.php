<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    // Campos que permitimos llenar masivamente
    protected $fillable = [
        'origin',
        'destination',
        'departure_time',
        'arrival_time',
        'price',
        'image_url',
    ];

    // Relación: Un vuelo tiene muchas reservas
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}