<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DayUse extends Model
{
    use HasFactory;
    protected $table = 'day_uses';
    protected $fillable=[
        'number',
        'customer_id',
        'status',
        'payment',
        'is_edited',
        'insurance',
        'discount',
        'tax',
        'grand_total',
        'paid',
    ];

    public function additions():HasMany
    {
        return $this->hasMany(DayUseAddition::class);
    }
}
