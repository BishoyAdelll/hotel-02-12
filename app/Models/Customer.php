<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable=[
            'full_name',
            'address',
            'phone',
            'whatsapp',
            'image',
            'church_name',
            'meeting_name',
            'responsible_name',
        ];
    protected $casts=[
      'image' => 'array'
    ];
}
