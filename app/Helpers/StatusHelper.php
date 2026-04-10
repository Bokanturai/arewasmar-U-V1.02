<?php

namespace App\Helpers;

class StatusHelper
{
    /**
     * Normalize a status string into a standard set: successful, processing, failed.
     *
     * @param string|null $status
     * @return string
     */
    public static function normalize(?string $status): string
    {
        if (!$status) {
            return 'processing';
        }

        $status = strtolower(trim($status));

        // Successful Set
        $successful = ['successful', 'success', 'resolved', 'in_progress', 'approved', 'completed'];
        
        // Processing Set
        $processing = ['processing', 'pending', 'submitted', 'new'];
        
        // Failed Set
        $failed = ['failed', 'rejected', 'error', 'declined', 'invalid', 'no record'];

        if (in_array($status, $successful)) {
            return 'successful';
        }

        if (in_array($status, $processing)) {
            return 'processing';
        }

        if (in_array($status, $failed)) {
            return 'failed';
        }

        return $status; // Return original if not matched, or default to failed/processing? 
    }

    /**
     * Map a standardized status to a Bootstrap color class.
     */
    public static function color(string $status): string
    {
        $normalized = self::normalize($status);
        return match ($normalized) {
            'successful' => 'success',
            'processing' => 'warning',
            'failed'     => 'danger',
            default      => 'secondary',
        };
    }
}
