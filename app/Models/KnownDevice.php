<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnownDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fingerprint',
        'browser',
        'device',
        'platform',
        'ip_address',
        'location',
        'last_login_at',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the user that owns the device.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
