<?php

namespace App\Actions;

use App\Enums\PaymentMethod;
use App\Models\Appointment;
use App\Models\Invoice;
use DomainException;
use Illuminate\Support\Facades\DB;

final class CollectAppointmentFeeAction
{
    public function execute(
        Appointment $appointment,
        PaymentMethod $paymentMethod,
        ?int $collectedBy = null
    ): Invoice {
        if ($appointment->is_paid) {
            throw new DomainException('تم سداد رسوم هذا الموعد مسبقاً.');
        }

        return DB::transaction(function () use ($appointment, $paymentMethod, $collectedBy) {
            $invoice = Invoice::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'collected_by' => $collectedBy,
                'amount' => $appointment->fee,
                'payment_method' => $paymentMethod,
                'paid_at' => now(),
            ]);

            $appointment->update([
                'is_paid' => true,
            ]);

            return $invoice;
        });
    }
}
