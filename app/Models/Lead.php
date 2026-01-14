<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lead extends Model
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
        'Fingerprint',
        'ProductInformation',
        'status',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * Get the result associated with the lead.
     */
    public function result(): HasOne
    {
        return $this->hasOne(LeadResult::class);
    }
}
