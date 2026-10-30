<?php

namespace App\Actions;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use DomainException;

final class CheckInPatientAction
{
    public function execute(Appointment $appointment): Appointment
    {
        if ($appointment->status !== AppointmentStatus::Booked) {
            throw new DomainException('لا يمكن تسجيل وصول موعد ليس في حالة محجوز.');
        }

        $appointment->update([
            'status' => AppointmentStatus::Waiting,
        ]);

        return $appointment;
    }
}
