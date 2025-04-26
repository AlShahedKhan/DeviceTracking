<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\DeviceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DeviceTrackingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $userAgent = $request->header('User-Agent');

        // Detect device type, browser, and platform (same logic as before)
        if (preg_match('/mobile/i', $userAgent)) {
            $deviceType = 'Mobile';
        } elseif (preg_match('/tablet/i', $userAgent)) {
            $deviceType = 'Tablet';
        } else {
            $deviceType = 'Desktop';
        }

        if (preg_match('/chrome/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        } else {
            $browser = 'Other';
        }

        if (preg_match('/windows/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/macintosh/i', $userAgent)) {
            $platform = 'Mac OS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/iphone/i', $userAgent)) {
            $platform = 'iOS';
        } else {
            $platform = 'Other';
        }

        // Check if this device information has already been logged in the database
        $existingDeviceLog = DeviceLog::where('device_type', $deviceType)
            ->where('browser', $browser)
            ->where('platform', $platform)
            ->first();

        // If not, store it in the database
        if (!$existingDeviceLog) {
            DeviceLog::create([
                'device_type' => $deviceType,
                'browser' => $browser,
                'platform' => $platform,
            ]);
        }

        // Proceed with the request
        return $next($request);
    }
}
