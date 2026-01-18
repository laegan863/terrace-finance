<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_request_id',
        'http_status',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(OfferRequest::class, 'offer_request_id');
    }
}
