<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ApplicationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'FirstName','LastName',
        'CellNumber','CellValidation',
        'Address','Address2','City','State','Zip',
        'Email','Fingerprint',
        'Consent',
        'SSN',
        'DOB','LastPayDate','NextPayDate',
        'GrossIncome','NetIncome',
        'PayFrequency',
        'ProductInformation',
        'IdentificationDocumentID',
        'BestEstimate',
        'status',
    ];

    protected $casts = [
        'CellValidation' => 'boolean',
        'Consent' => 'boolean',
        'GrossIncome' => 'decimal:2',
        'NetIncome' => 'decimal:2',
        'BestEstimate' => 'decimal:2',
    ];

    public function result(): HasOne
    {
        return $this->hasOne(ApplicationResult::class);
    }
}
