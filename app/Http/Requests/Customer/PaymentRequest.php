<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'payment_method' => 'required|in:credit_card,bank_transfer,cash',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|in:SAR,USD,EUR',
            'card_number' => 'required_if:payment_method,credit_card|nullable|string|size:16',
            'card_expiry' => [
                'required_if:payment_method,credit_card',
                'nullable',
                'string',
                'size:5',
                'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
                function ($attribute, $value, $fail) {
                    if ($this->payment_method === 'credit_card') {
                        list($month, $year) = explode('/', $value);
                        $expiry = \Carbon\Carbon::createFromDate('20'.$year, $month, 1)->endOfMonth();
                        if ($expiry->isPast()) {
                            $fail('البطاقة منتهية الصلاحية');
                        }
                    }
                },
            ],
            'card_cvv' => 'required_if:payment_method,credit_card|nullable|string|size:3',
            'bank_receipt' => 'required_if:payment_method,bank_transfer|nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string|max:500',
        ];

        if ($this->payment_method === 'credit_card') {
            $rules['card_holder'] = 'required|string|max:255';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'payment_method.required' => 'يرجى اختيار طريقة الدفع',
            'amount.required' => 'يرجى إدخال المبلغ',
            'amount.min' => 'المبلغ يجب أن يكون أكبر من صفر',
            'card_number.required_if' => 'يرجى إدخال رقم البطاقة',
            'card_number.size' => 'رقم البطاقة يجب أن يتكون من 16 رقم',
            'card_expiry.required_if' => 'يرجى إدخال تاريخ انتهاء البطاقة',
            'card_expiry.regex' => 'صيغة تاريخ الانتهاء غير صحيحة (MM/YY)',
            'card_cvv.required_if' => 'يرجى إدخال رمز الأمان CVV',
            'bank_receipt.required_if' => 'يرجى إرفاق إيصال التحويل البنكي',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->payment_method === 'credit_card') {
            $this->merge([
                'card_number' => str_replace(' ', '', $this->card_number),
            ]);
        }
    }
}
