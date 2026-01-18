<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PricingFactorRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'FirstName',
        'LastName',
        'PhoneNumber',
        'Address',
        'City',
        'State',
        'Zip',
        'Email',
        'SSN',
        'DOB',
        'GrossIncome',
        'ProductInformation',
        'Fingerprint',
        'status',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    public function result(): HasOne
    {
        return $this->hasOne(PricingFactorResult::class);
    }
}
