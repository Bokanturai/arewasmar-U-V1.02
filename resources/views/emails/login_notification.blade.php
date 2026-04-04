<x-mail::message>
# Security Alert: New Login Detected

Hello {{ $details['name'] }},

We detected a login to your account from a new device.

**Login Details:**
- **Browser:** {{ $details['browser'] }}
- **Device:** {{ $details['device'] }}
- **Platform:** {{ $details['platform'] }}
- **IP Address:** {{ $details['ip'] }}
- **Location:** {{ $details['location'] }}
- **Time:** {{ $details['time'] }}

If this was you, you can safely ignore this email.

If you don't recognize this activity, please change your password immediately and contact support.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
