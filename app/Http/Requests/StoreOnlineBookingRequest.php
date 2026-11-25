<?php

namespace App\Http\Requests;

use App\Enums\AppointmentType;
use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOnlineBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'appointment_date' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:'.now()->addDays(60)->toDateString(),
            ],
            'type' => ['required', Rule::enum(AppointmentType::class)],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'يرجى إدخال اسم المريض بالكامل.',
            'name.min' => 'يجب أن يكون الاسم 3 أحرف على الأقل.',
            'phone.required' => 'يرجى إدخال رقم الهاتف للتواصل وتأكيد الحجز.',
            'phone.regex' => 'صيغة رقم الهاتف غير صحيحة.',
            'gender.required' => 'يرجى اختيار النوع.',
            'birth_date.before_or_equal' => 'تاريخ الميلاد يجب أن يكون في الماضي.',
            'appointment_date.required' => 'يرجى اختيار تاريخ الحجز.',
            'appointment_date.after_or_equal' => 'لا يمكن الحجز في تاريخ سابق لليوم.',
            'appointment_date.before_or_equal' => 'يمكن الحجز خلال 60 يوماً قادماً كحد أقصى.',
            'type.required' => 'يرجى اختيار نوع الكشف.',
            'notes.max' => 'الملاحظات يجب ألا تتجاوز 500 حرف.',
        ];
    }
}
