<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'day',
        'weather',
        'temperature',
        'group_size',
        'ice_cream_purchases',
        'arrived_by_car',
        'seating_status',
        'visitor_type',
        'additional_purchases',
        'notes'
    ];

    protected $casts = [
        'day' => 'date',
        'arrived_by_car' => 'boolean',
        'additional_purchases' => 'array',
        'temperature' => 'decimal:1'
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}