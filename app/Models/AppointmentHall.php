<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AppointmentHall extends Model
{
    use HasFactory;
    protected $table='appointment_hall';
    protected $fillable=[
        'hall_id',
        'appointment_id',
    ];

    public function halls(): BelongsToMany
    {
        return $this->belongsToMany(Hall::class);
    }
}
