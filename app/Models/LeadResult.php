<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'http_status',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];

    /**
     * Get the lead that owns the result.
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
