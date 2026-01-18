<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingFactorResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'pricing_factor_request_id',
        'http_status',
        'response',
        'offers',
    ];

    protected $casts = [
        'response' => 'array',
        'offers' => 'array',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(PricingFactorRequest::class, 'pricing_factor_request_id');
    }
}
