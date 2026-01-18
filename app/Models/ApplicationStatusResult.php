<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationStatusResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_status_request_id',
        'http_status',
        'response',
        'offers',
    ];

    protected $casts = [
        'response' => 'array',
        'offers' => 'array',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(ApplicationStatusRequest::class, 'application_status_request_id');
    }
}
