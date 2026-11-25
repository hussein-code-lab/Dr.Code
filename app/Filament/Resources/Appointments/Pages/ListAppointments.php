<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Enums\AppointmentSource;
use App\Enums\AppointmentStatus;
use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\Appointment;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('الكل')
                ->badge(Appointment::count()),

            'today' => Tab::make('مواعيد اليوم')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereDate('appointment_date', today()))
                ->badge(Appointment::whereDate('appointment_date', today())->count())
                ->badgeColor('primary'),

            'online' => Tab::make('الحجوزات الإلكترونية')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('source', AppointmentSource::Online))
                ->badge(
                    Appointment::where('source', AppointmentSource::Online)
                        ->whereDate('appointment_date', '>=', today())
                        ->where('status', AppointmentStatus::Booked)
                        ->count()
                )
                ->badgeColor('info'),

            'upcoming' => Tab::make('القادمة')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query
                    ->whereDate('appointment_date', '>=', today())
                    ->whereIn('status', [AppointmentStatus::Booked, AppointmentStatus::Waiting]))
                ->badge(
                    Appointment::whereDate('appointment_date', '>=', today())
                        ->whereIn('status', [AppointmentStatus::Booked, AppointmentStatus::Waiting])
                        ->count()
                )
                ->badgeColor('warning'),
        ];
    }
}
