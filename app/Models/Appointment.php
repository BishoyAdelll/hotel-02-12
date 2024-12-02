<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    use HasFactory;
    protected $table='appointments';

    protected $fillable=[
        'number',
        'customer_id',
        'check_in_date',
        'check_out_date',
        'the_numbers_of_days',
        'capacity_id',
        'structure_id',
        'hall_price',
        'discount',
        'tax',
        'grand_total',
        'paid',
        'status',
        'payment',
        'is_edited',
        'insurance',
        'receipt_number',
        'receipt_images',
        'capacity',
        'person_price',
        'total_person_price',
        'program'
    ];
    protected $casts=[
        'ID_image'=>'array',
        'receipt_images'=>'array',
        // 'hall_id' => 'array',
    ];

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }
    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }
    public function additions():HasMany
    {
        return $this->hasMany(AppointmentAddition::class);
    }
    public function attachments():HasMany
    {
        return $this->hasMany(AppointmentAttachment::class);
    }
    public function tools():HasMany
    {
        return $this->hasMany(AppointmentTool::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function capacity()
    {
        return $this->belongsTo(capacity::class);
    }


    public function Halls()
    {
        return $this->belongsToMany(Hall::class);
    }
}



