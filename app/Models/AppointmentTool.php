<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentTool extends Model
{
    use HasFactory;
    protected $table = 'appointment_tools';
    protected $fillable = [
        'appointment_id',
        'tool_id',
        'price_male',
        'date_time',
        'quantity',
        'total',
    ];


    public function  tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
