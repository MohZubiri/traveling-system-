<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProfileCompletion
{
    public function handle(Request $request, Closure $next)
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $requiredFields = [
            'name',
            'email',
            'phone',
            'nationality',
            'passport_number'
        ];

        foreach ($requiredFields as $field) {
            if (empty($customer->$field)) {
                return redirect()->route('customer.profile.edit')
                    ->with('warning', 'يرجى إكمال بيانات الملف الشخصي أولاً');
            }
        }

        return $next($request);
    }
}
