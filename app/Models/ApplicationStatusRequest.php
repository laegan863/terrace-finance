<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ApplicationStatusRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'ApplicationID',
        'scenario',
        'status',
    ];

    public function result(): HasOne
    {
        return $this->hasOne(ApplicationStatusResult::class);
    }
}
