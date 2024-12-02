<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Structure extends Model
{
    use HasFactory;

    
    protected $fillable=[
        'code',
        'details',
        'image',
    ];

    protected $casts=[
        'image'=>'array'
    ];

    
}
