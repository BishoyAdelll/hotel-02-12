<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentPost extends Model
{
    use HasFactory;
    protected $fillable =[
        'appointment_id',
        'post_id',
        'price_male',
        'date_time',
        'quantity',
        'total',
    ] ;
}
