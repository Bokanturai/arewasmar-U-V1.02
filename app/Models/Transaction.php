<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model


{
    protected $table = 'transactions'; 
    
    protected $fillable = [
        'transaction_ref', 
        'payer_name',
        'referenceId',
        'user_id', 
        'amount', 
        'fee',
        'net_amount',
        'description',
        'type', 
        'status', 
        'metadata', 
        'performed_by',
        'approved_by',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the standardized status name.
     */
    public function getNormalizedStatusAttribute()
    {
        return \App\Helpers\StatusHelper::normalize($this->status);
    }

    /**
     * Get the Bootstrap color for the status.
     */
    public function getStatusColorAttribute()
    {
        return \App\Helpers\StatusHelper::color($this->status);
    }
}
