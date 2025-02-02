<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PreventConcurrentPayments
{
    public function handle(Request $request, Closure $next)
    {
        $customerId = auth('customer')->id();
        $lockKey = "payment_lock_{$customerId}";

        if (Cache::has($lockKey)) {
            return response()->json([
                'message' => 'هناك عملية دفع جارية حالياً. يرجى الانتظار حتى اكتمالها.'
            ], 429);
        }

        Cache::put($lockKey, true, now()->addMinutes(15));

        $response = $next($request);

        Cache::forget($lockKey);

        return $response;
    }
}
