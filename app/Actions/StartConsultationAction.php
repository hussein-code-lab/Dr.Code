<?php

namespace App\Actions;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Consultation;
use DomainException;
use Illuminate\Support\Facades\DB;

final class StartConsultationAction
{
    public function execute(Appointment $appointment): Consultation
    {
        if ($appointment->status === AppointmentStatus::Completed) {
            throw new DomainException('هذا الكشف مكتمل بالفعل.');
        }

        return DB::transaction(function () use ($appointment) {
            $appointment->update([
                'status' => AppointmentStatus::InConsultation,
            ]);

            return Consultation::firstOrCreate(
                ['appointment_id' => $appointment->id],
                [
                    'patient_id' => $appointment->patient_id,
                    'chief_complaint' => '',
                    'diagnosis' => '',
                ]
            );
        });
    }
}
