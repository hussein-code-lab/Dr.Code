<?php

namespace App\Actions;

use App\Enums\AppointmentStatus;
use App\Models\Consultation;
use App\Models\Prescription;
use Illuminate\Support\Facades\DB;

final class CompleteConsultationAction
{
    /**
     * @param array{
     *     vitals?: array,
     *     chief_complaint: string,
     *     diagnosis: string,
     *     internal_notes?: string|null,
     *     general_advice?: string|null,
     *     prescription_items?: array<int, array{medicine_name: string, dosage: string, frequency: string, duration: string, instructions?: string|null}>
     * } $data
     */
    public function execute(Consultation $consultation, array $data): Consultation
    {
        return DB::transaction(function () use ($consultation, $data) {
            $consultation->update([
                'vitals' => $data['vitals'] ?? null,
                'chief_complaint' => $data['chief_complaint'],
                'diagnosis' => $data['diagnosis'],
                'internal_notes' => $data['internal_notes'] ?? null,
            ]);

            // إنشاء الروشتة إذا تم إدخال أدوية أو نصائح عامة
            if (! empty($data['prescription_items']) || ! empty($data['general_advice'])) {
                /** @var Prescription $prescription */
                $prescription = $consultation->prescription()->updateOrCreate(
                    ['patient_id' => $consultation->patient_id],
                    ['general_advice' => $data['general_advice'] ?? null]
                );

                if (! empty($data['prescription_items'])) {
                    $prescription->items()->delete();
                    $prescription->items()->createMany($data['prescription_items']);
                }
            }

            // تحديث حالة الموعد إلى مكتمل
            $consultation->appointment->update([
                'status' => AppointmentStatus::Completed,
            ]);

            return $consultation->fresh(['prescription.items']);
        });
    }
}
