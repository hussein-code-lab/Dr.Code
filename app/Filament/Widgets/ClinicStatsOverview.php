<?php

namespace App\Filament\Widgets;

use App\Enums\AppointmentSource;
use App\Enums\AppointmentStatus;
use App\Enums\PaymentMethod;
use App\Models\Appointment;
use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClinicStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayRevenue = Invoice::whereDate('paid_at', today())->sum('amount');

        $todayCash = Invoice::whereDate('paid_at', today())
            ->where('payment_method', PaymentMethod::Cash)
            ->sum('amount');

        $todayElectronic = Invoice::whereDate('paid_at', today())
            ->where('payment_method', '!=', PaymentMethod::Cash)
            ->sum('amount');

        $todayAppointmentsCount = Appointment::whereDate('appointment_date', today())->count();
        $waitingCount = Appointment::whereDate('appointment_date', today())
            ->where('status', AppointmentStatus::Waiting)
            ->count();
        $completedCount = Appointment::whereDate('appointment_date', today())
            ->where('status', AppointmentStatus::Completed)
            ->count();
        $upcomingOnlineCount = Appointment::whereDate('appointment_date', '>=', today())
            ->where('source', AppointmentSource::Online)
            ->where('status', AppointmentStatus::Booked)
            ->count();

        return [
            Stat::make('إيراد الخزينة اليوم', number_format($todayRevenue, 2).' ج.م')
                ->description('كاش: '.number_format($todayCash, 0).' | إلكتروني: '.number_format($todayElectronic, 0))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('حجوزات إلكترونية جديدة', "{$upcomingOnlineCount} حجز")
                ->description('حجوزات أونلاين بانتظار حضور المرضى')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color($upcomingOnlineCount > 0 ? 'info' : 'gray'),

            Stat::make('حالات الانتظار اليوم', "{$waitingCount} مريض")
                ->description("من أصل {$todayAppointmentsCount} حجز اليوم")
                ->descriptionIcon('heroicon-m-clock')
                ->color($waitingCount > 0 ? 'warning' : 'gray'),

            Stat::make('الكشوفات المكتملة', "{$completedCount} مريض")
                ->description('تم الانتهاء وكتابة الروشتة')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),
        ];
    }
}
