<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class VisaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|in:tourist,business,student,work',
            'passport_number' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9]+$/',
            ],
            'passport_expiry' => 'required|date|after:6 months',
            'duration' => 'required|integer|min:1|max:365',
            'entry_type' => 'required|in:single,multiple',
            'documents' => 'required|array|min:1',
            'documents.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'purpose' => 'required|string|max:500',
            'previous_visa' => 'boolean',
            'previous_visa_number' => 'required_if:previous_visa,true|nullable|string|max:20',
            'emergency_contact' => 'required|array',
            'emergency_contact.name' => 'required|string|max:255',
            'emergency_contact.phone' => 'required|string|max:20',
            'emergency_contact.relation' => 'required|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'يرجى اختيار نوع التأشيرة',
            'passport_number.required' => 'يرجى إدخال رقم الجواز',
            'passport_number.regex' => 'رقم الجواز يجب أن يحتوي على أحرف وأرقام فقط',
            'passport_expiry.after' => 'يجب أن تكون صلاحية الجواز أكثر من 6 أشهر',
            'documents.required' => 'يرجى إرفاق المستندات المطلوبة',
            'documents.*.mimes' => 'صيغة الملف غير مدعومة. الصيغ المدعومة: PDF, JPG, PNG',
            'documents.*.max' => 'حجم الملف يجب أن لا يتجاوز 5 ميجابايت',
            'emergency_contact.required' => 'يرجى إدخال بيانات جهة الاتصال في حالات الطوارئ',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->hasFile('documents')) {
            foreach ($this->file('documents') as $document) {
                if ($document->getSize() > 5120 * 1024) {
                    throw new \Illuminate\Validation\ValidationException(validator([], [], [
                        'documents' => 'حجم الملف يتجاوز الحد المسموح به'
                    ]));
                }
            }
        }
    }
}
