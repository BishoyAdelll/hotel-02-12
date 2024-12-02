<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Nette\Schema\Elements\Structure;

class Hall extends Model
{
    use HasFactory;

    protected $table='halls';
    protected $fillable=[
        'structure_id',
        'capacity_id',
        'number',
        'floor',
        'beds',
        'room_price',
        'image',
    ];

    protected $casts=[
        'image'=>'array'
    ];

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }
    public function capacity()
    {
        return $this->belongsTo(capacity::class);
    }
    public function appointments()
    {
        return $this->belongsToMany(Appointment::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
