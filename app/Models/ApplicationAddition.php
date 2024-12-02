<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ApplicationAddition extends Model
{
    use HasFactory;
    protected $fillable=[
        'application_id',
        'addition_id',
        'price',
        'quantity',
        'total',
    ];

    public function additions(): BelongsToMany
    {
        return $this->belongsToMany(Addition::class);
    }

    public function addition()
    {
        return $this->belongsTo(Addition::class);
    }
}
