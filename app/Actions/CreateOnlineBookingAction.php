<?php

namespace App\Actions;

use App\Enums\AppointmentSource;
use App\Enums\AppointmentStatus;
use App\Enums\AppointmentType;
use App\Enums\Gender;
use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

final class CreateOnlineBookingAction
{
    public function __construct(
        public BookAppointmentAction $bookAppointmentAction,
    ) {}

    /**
     * @param  array{
     *     name: string,
     *     phone: string,
     *     gender: Gender|string,
     *     birth_date?: string|null,
     *     appointment_date: string,
     *     type: AppointmentType|string,
     *     notes?: string|null,
     * }  $data
     */
    public function execute(array $data): Appointment
    {
        $normalizedPhone = $this->normalizePhone($data['phone']);
        $gender = $data['gender'] instanceof Gender ? $data['gender'] : Gender::from($data['gender']);
        $type = $data['type'] instanceof AppointmentType ? $data['type'] : AppointmentType::from($data['type']);
        $formattedDate = Carbon::parse($data['appointment_date'])->toDateString();

        $patient = Patient::where('phone', $normalizedPhone)->first();

        if (! $patient) {
            $patient = Patient::create([
                'name' => trim($data['name']),
                'phone' => $normalizedPhone,
                'gender' => $gender,
                'birth_date' => $data['birth_date'] ?? null,
            ]);
        } elseif (blank($patient->birth_date) && filled($data['birth_date'] ?? null)) {
            $patient->update([
                'birth_date' => $data['birth_date'],
            ]);
        }

        $hasActiveBooking = Appointment::where('patient_id', $patient->id)
            ->whereDate('appointment_date', $formattedDate)
            ->whereIn('status', [
                AppointmentStatus::Booked,
                AppointmentStatus::Waiting,
                AppointmentStatus::InConsultation,
            ])
            ->exists();

        if ($hasActiveBooking) {
            throw ValidationException::withMessages([
                'appointment_date' => 'لديك حجز قائم بالفعل في هذا التاريخ. يمكنك متابعة الحجز برقم هاتفك.',
            ]);
        }

        $appointment = $this->bookAppointmentAction->execute(
            patientId: $patient->id,
            date: $formattedDate,
            type: $type,
            fee: $type->defaultFee(),
            source: AppointmentSource::Online,
            notes: filled($data['notes'] ?? null) ? trim((string) $data['notes']) : null,
        );

        $appointment->load('patient');

        $this->notifyAdmins($appointment);

        return $appointment;
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/[\s\-]+/', '', trim($phone)) ?? trim($phone);
    }

    private function notifyAdmins(Appointment $appointment): void
    {
        $admins = Admin::all();

        if ($admins->isEmpty()) {
            return;
        }

        $patientName = $appointment->patient?->name ?? 'مريض';
        $patientPhone = $appointment->patient?->phone ?? '';

        Notification::make()
            ->title('حجز إلكتروني جديد')
            ->body("المريض {$patientName} ({$patientPhone}) — {$appointment->type->getLabel()} بتاريخ {$appointment->appointment_date->format('Y-m-d')} (دور #{$appointment->queue_number})")
            ->icon('heroicon-o-calendar-days')
            ->info()
            ->actions([
                Action::make('view')
                    ->label('عرض الموعد')
                    ->url(AppointmentResource::getUrl('view', ['record' => $appointment], panel: 'admin'))
                    ->markAsRead(),
            ])
            ->sendToDatabase($admins);
    }
}
