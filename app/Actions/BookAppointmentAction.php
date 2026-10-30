<?php

namespace App\Actions;

use App\Enums\AppointmentStatus;
use App\Enums\AppointmentType;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final class BookAppointmentAction
{
    public function execute(
        int $patientId,
        Carbon|string $date,
        AppointmentType $type,
        float $fee = 0.00
    ): Appointment {
        $formattedDate = Carbon::parse($date)->toDateString();

        return DB::transaction(function () use ($patientId, $formattedDate, $type, $fee) {
            $nextQueue = Appointment::whereDate('appointment_date', $formattedDate)
                ->lockForUpdate()
                ->max('queue_number') + 1;

            return Appointment::create([
                'patient_id' => $patientId,
                'appointment_date' => $formattedDate,
                'queue_number' => $nextQueue,
                'type' => $type,
                'status' => AppointmentStatus::Booked,
                'fee' => $fee,
                'is_paid' => false,
            ]);
        });
    }
}
