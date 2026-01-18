<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_request_id',
        'http_status',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(InvoiceRequest::class, 'invoice_request_id');
    }
}
