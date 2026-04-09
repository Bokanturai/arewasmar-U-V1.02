<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_hash',
        'code_token_encrypted',
        'amount',
        'title',
        'title_color',
        'message',
        'style',
        'text_color',
        'image_path',
        'status',
        'created_by',
        'used_by',
        'used_at',
        'expires_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user who created the gift card.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the decrypted code token safely.
     * Only returns the code if the card is unused.
     */
    public function getSafeTokenAttribute()
    {
        if ($this->status === 'used') {
            return 'CLAIMED';
        }

        try {
            return decrypt($this->code_token_encrypted);
        } catch (\Exception $e) {
            return 'ERROR';
        }
    }

    /**
     * Get the user who redeemed the gift card.
     */
    public function redeemer()
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}
