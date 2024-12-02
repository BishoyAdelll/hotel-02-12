<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ApplicationComponent extends Model
{
    use HasFactory;
    protected $fillable=[
        'application_id',
        'component_id',
        'type',
        'category',
        'quantity',
        'price',
        'total',
    ];

    public function components(): BelongsToMany
    {
        return $this->belongsToMany(Component::class);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

}
