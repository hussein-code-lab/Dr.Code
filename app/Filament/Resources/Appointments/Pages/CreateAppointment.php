<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Actions\BookAppointmentAction;
use App\Enums\AppointmentSource;
use App\Enums\AppointmentType;
use App\Filament\Resources\Appointments\AppointmentResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $type = $data['type'] instanceof AppointmentType
            ? $data['type']
            : AppointmentType::from($data['type']);

        $source = isset($data['source'])
            ? ($data['source'] instanceof AppointmentSource ? $data['source'] : AppointmentSource::from($data['source']))
            : AppointmentSource::Reception;

        return app(BookAppointmentAction::class)->execute(
            patientId: (int) $data['patient_id'],
            date: $data['appointment_date'],
            type: $type,
            fee: (float) ($data['fee'] ?? $type->defaultFee()),
            source: $source,
            notes: $data['notes'] ?? null,
        );
    }
}
