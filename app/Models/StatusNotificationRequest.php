<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StatusNotificationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'token_header',
        'authorization_header',
        'ApplicationID',
        'LeadID',
        'InvoiceNumber',
        'InvoiceID',
        'ApprovalAmount',
        'FundedAmount',
        'ApplicationStatus',
        'LenderName',
        'Offer',
        'raw_payload',
        'status',
    ];

    protected $casts = [
        'Offer' => 'array',
        'raw_payload' => 'array',
        'ApprovalAmount' => 'decimal:2',
        'FundedAmount' => 'decimal:2',
    ];

    public function result(): HasOne
    {
        return $this->hasOne(StatusNotificationResult::class);
    }
}
