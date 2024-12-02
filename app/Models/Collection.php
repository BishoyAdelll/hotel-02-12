<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    protected $fillable = [
        'attachment_id',
        'appointment_id',
        'application_id',
        'number',
        'start_time',
        'end_time',
        'booked_at',
    ];


    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }
}
