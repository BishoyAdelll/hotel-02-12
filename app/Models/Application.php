<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory;
    protected $fillable=[
        'number',
        'customer_id',
        'church_name',
        'meeting_name',
        'responsible_name',
        'application_id',
        'priest_name',
        'status',
        'payment',
        'is_edited',
        'insurance',
        'discount',
        'tax',
        'grand_total',
        'deposit',
        'start_date'
    ];


    public function additions():HasMany
    {
        return $this->hasMany(ApplicationAddition::class);
    }
    public function components():HasMany
    {
        return $this->hasMany(ApplicationComponent::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ApplicationAttachment::class);
    }
    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
