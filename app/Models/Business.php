<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'seating_count',
        'ice_cream_flavors_count',
        'has_additional_products',
        'ice_cream_price',
        'business_hours'
    ];

    protected $casts = [
        'has_additional_products' => 'boolean',
        'ice_cream_price' => 'decimal:2',
        'business_hours' => 'array'
    ];

    public function records(): HasMany
    {
        return $this->hasMany(BusinessRecord::class);
    }
}