<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model 
{
    use HasFactory;

    protected $fillable = [ // Campos que se llenan
        'user_id',
        'flight_id',
        'status',
        'comments',
        'seat_class',
        'seat_number'
    ];

    protected function casts(): array // tipos de datos
    {
        return [
            'status' => 'string',
        ];
    }

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class); //1-1
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class); // 1-1
    }
}