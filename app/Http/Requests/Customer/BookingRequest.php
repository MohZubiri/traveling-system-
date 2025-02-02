<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'service_type' => 'required|in:bus,car',
            'date' => 'required|date|after:today',
            'time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $dateTime = $this->date . ' ' . $value;
                    if (strtotime($dateTime) < strtotime('+2 hours')) {
                        $fail('يجب أن يكون موعد الحجز بعد ساعتين على الأقل من الوقت الحالي');
                    }
                },
            ],
            'location' => 'required|string|max:255',
            'passengers' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:500',
            'return_trip' => 'boolean',
            'return_date' => 'required_if:return_trip,true|nullable|date|after:date',
            'return_time' => 'required_if:return_trip,true|nullable|date_format:H:i',
        ];

        if ($this->isMethod('PUT')) {
            $booking = $this->route('booking');
            if (!$booking->canModify()) {
                return [];
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'service_type.required' => 'يرجى اختيار نوع الخدمة',
            'service_type.in' => 'نوع الخدمة غير صالح',
            'date.required' => 'يرجى اختيار تاريخ الحجز',
            'date.after' => 'يجب أن يكون تاريخ الحجز بعد اليوم',
            'time.required' => 'يرجى اختيار وقت الحجز',
            'time.date_format' => 'صيغة الوقت غير صحيحة',
            'location.required' => 'يرجى إدخال الموقع',
            'passengers.required' => 'يرجى إدخال عدد الركاب',
            'passengers.min' => 'يجب أن يكون عدد الركاب 1 على الأقل',
            'passengers.max' => 'الحد الأقصى لعدد الركاب هو 10',
            'return_date.after' => 'يجب أن يكون تاريخ العودة بعد تاريخ الذهاب',
        ];
    }
}
