<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AppointmentAddition extends Model
{
    use HasFactory;
    protected $table='appointment_additions';
    protected $fillable=
        [
            'appointment_id',
            'addition_id',
            'booked_at',
            'price',

        ];


    public function additions(): BelongsToMany
    {
        return $this->belongsToMany(Addition::class,'additions','addition_id');
    }
    public function addition(): BelongsTo
    {
        return $this->belongsTo(Addition::class);
    }
}
