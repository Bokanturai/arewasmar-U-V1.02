<?php

namespace App\Listeners;

use App\Mail\LoginNotification;
use App\Models\KnownDevice;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

class LoginListener
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $request = request();
        
        $userAgent = $request->header('User-Agent');
        $ip = $request->ip();
        
        // Simple fingerprint based on user agent
        $fingerprint = md5($userAgent);
        
        // Detect browser, platform, and device (Basic Implementation)
        $details = $this->parseUserAgent($userAgent);
        $details['ip'] = $ip;
        $details['time'] = now()->format('Y-m-d H:i:s');
        $details['name'] = $user->first_name ?? $user->name ?? 'User';
        $details['location'] = 'Unknown'; // Location detection usually needs external service

        // Check if device is known
        $knownDevice = KnownDevice::where('user_id', $user->id)
            ->where('fingerprint', $fingerprint)
            ->first();

        if (!$knownDevice) {
            // New device! Register and notify
            KnownDevice::create([
                'user_id' => $user->id,
                'fingerprint' => $fingerprint,
                'browser' => $details['browser'],
                'device' => $details['device'],
                'platform' => $details['platform'],
                'ip_address' => $ip,
                'last_login_at' => now(),
            ]);

            // Use Queue system as requested (Mailable implements ShouldQueue)
            try {
                Mail::to($user->email)->send(new LoginNotification($details));
            } catch (\Exception $e) {
                \Log::error('Failed to queue login notification: ' . $e->getMessage());
            }
        } else {
            // Known device, just update last login
            $knownDevice->update([
                'last_login_at' => now(),
                'ip_address' => $ip,
            ]);
        }
    }

    /**
     * Basic User Agent Parser
     */
    private function parseUserAgent($agent): array
    {
        // Platform
        $platform = 'Unknown';
        if (preg_match('/windows|win32/i', $agent)) $platform = 'Windows';
        elseif (preg_match('/macintosh|mac os x/i', $agent)) $platform = 'Mac OS';
        elseif (preg_match('/linux/i', $agent)) $platform = 'Linux';
        elseif (preg_match('/android/i', $agent)) $platform = 'Android';
        elseif (preg_match('/iphone/i', $agent)) $platform = 'iOS';

        // Browser
        $browser = 'Unknown';
        if (preg_match('/msie|trident/i', $agent)) $browser = 'Internet Explorer';
        elseif (preg_match('/firefox/i', $agent)) $browser = 'Firefox';
        elseif (preg_match('/chrome/i', $agent)) $browser = 'Chrome';
        elseif (preg_match('/opera|opr/i', $agent)) $browser = 'Opera';
        elseif (preg_match('/safari/i', $agent)) $browser = 'Safari';

        // Device
        $device = 'Desktop';
        if (preg_match('/mobile|phone|android/i', $agent)) $device = 'Mobile';
        elseif (preg_match('/tablet|ipad/i', $agent)) $device = 'Tablet';

        return [
            'browser' => $browser,
            'platform' => $platform,
            'device' => $device,
        ];
    }
}
