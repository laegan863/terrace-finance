<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InvoiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'InvoiceNumber','InvoiceDate','DeliveryDate',
        'ApplicationID','LeadID',
        'Discount','DownPayment','Shipping','Tax',
        'ReturnURL','InvoiceVersion',
        'Items',
        'status',
    ];

    protected $casts = [
        'Items' => 'array',
        'Discount' => 'decimal:2',
        'DownPayment' => 'decimal:2',
        'Shipping' => 'decimal:2',
        'Tax' => 'decimal:2',
    ];

    public function result(): HasOne
    {
        return $this->hasOne(InvoiceResult::class);
    }
}
