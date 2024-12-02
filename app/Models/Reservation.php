<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable=[
        'appointment_id',
        'hall_id',
        'check_in',
        'check_out',
    ];

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function halls()
    {
        return $this->belongsTo(Hall::class);
    }




}
