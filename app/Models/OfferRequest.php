<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OfferRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'ApplicationID',
        'Offer',
        'BankDetails',
        'status',
    ];

    protected $casts = [
        'BankDetails' => 'array',
    ];

    public function result(): HasOne
    {
        return $this->hasOne(OfferResult::class);
    }
}
