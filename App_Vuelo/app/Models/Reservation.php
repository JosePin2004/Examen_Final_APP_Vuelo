<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'flight_id',
        'status',   // pending, accepted, rejected
        'comments'
    ];

    // Relación: Una reserva pertenece a un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Una reserva pertenece a un Vuelo
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
}
