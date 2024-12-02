<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DayUseAddition extends Model
{
    use HasFactory;
    protected $table='day_use_addition';
    protected $fillable=
        [
            'day_use',
            'addition_id',
            'price',
            'description',

        ];


    public function additions(): BelongsToMany
    {
        return $this->belongsToMany(Addition::class);
    }
}
