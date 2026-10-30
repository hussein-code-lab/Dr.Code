<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Actions\BookAppointmentAction;
use App\Filament\Resources\Appointments\AppointmentResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(BookAppointmentAction::class)->execute(
            patientId: (int) $data['patient_id'],
            date: $data['appointment_date'],
            type: $data['type'],
            fee: (float) ($data['fee'] ?? 0.00)
        );
    }
}
