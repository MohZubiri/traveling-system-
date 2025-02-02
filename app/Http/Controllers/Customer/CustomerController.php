<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\Visa;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $customer = auth('customer')->user();
        
        $visaCount = Visa::where('customer_id', $customer->id)->count();
        $bookingCount = Booking::where('customer_id', $customer->id)->count();
        $transactionCount = Transaction::where('customer_id', $customer->id)->count();
        
        $recentTransactions = Transaction::where('customer_id', $customer->id)
            ->latest()
            ->take(5)
            ->get();
            
        $notifications = $customer->notifications()
            ->latest()
            ->take(5)
            ->get();
            
        return view('customer.dashboard', compact(
            'customer',
            'visaCount',
            'bookingCount',
            'transactionCount',
            'recentTransactions',
            'notifications'
        ));
    }

    public function profile()
    {
        $customer = auth('customer')->user();
        
        $visaCount = Visa::where('customer_id', $customer->id)->count();
        $bookingCount = Booking::where('customer_id', $customer->id)->count();
        $transactionCount = Transaction::where('customer_id', $customer->id)->count();
        
        return view('customer.profile', compact(
            'customer',
            'visaCount',
            'bookingCount',
            'transactionCount'
        ));
    }

    public function updateProfile(Request $request)
    {
        $customer = auth('customer')->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)],
            'phone' => ['required', 'string', 'max:20'],
            'nationality' => ['required', 'string', 'max:100'],
            'passport_number' => ['nullable', 'string', 'max:50'],
        ]);

        $customer->update($validated);

        return redirect()->route('customer.profile')
            ->with('success', 'تم تحديث المعلومات الشخصية بنجاح');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer = auth('customer')->user();

        if (!Hash::check($validated['current_password'], $customer->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        $customer->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('customer.profile')
            ->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:1024'], // max 1MB
        ]);

        $customer = auth('customer')->user();

        if ($customer->photo) {
            Storage::disk('public')->delete($customer->photo);
        }

        $path = $request->file('photo')->store('customer-photos', 'public');
        
        $customer->update([
            'photo' => $path,
        ]);

        return redirect()->route('customer.profile')
            ->with('success', 'تم تحديث الصورة الشخصية بنجاح');
    }

    public function updateNotificationPreferences(Request $request)
    {
        $customer = auth('customer')->user();
        
        $customer->update([
            'email_notifications' => $request->boolean('email_notifications'),
            'sms_notifications' => $request->boolean('sms_notifications'),
        ]);

        return redirect()->route('customer.profile')
            ->with('success', 'تم تحديث إعدادات التنبيهات بنجاح');
    }
}
