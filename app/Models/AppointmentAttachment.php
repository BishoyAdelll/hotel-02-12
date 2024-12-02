<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AppointmentAttachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id',
        'attachment_id',
        'start_time',
        'end_time',
        'booked_at',
    ];
    public function attachments(): BelongsToMany
    {
        return $this->belongsToMany(Attachment::class,'attachments','attachment_id');
    }
    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }
}
