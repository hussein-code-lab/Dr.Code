<?php

namespace App\Filament\Widgets;

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
        // إجمالي إيراد اليوم
        $todayRevenue = Invoice::whereDate('paid_at', today())->sum('amount');

        // إيراد الكاش اليوم
        $todayCash = Invoice::whereDate('paid_at', today())
            ->where('payment_method', PaymentMethod::Cash)
            ->sum('amount');

        // إيراد الإلكتروني (فيزا / محفظة)
        $todayElectronic = Invoice::whereDate('paid_at', today())
            ->where('payment_method', '!=', PaymentMethod::Cash)
            ->sum('amount');

        // تحديد اسم عمود التاريخ الفعلي في جدول appointments
        $dateColumn = (new Appointment)->getConnection()->getSchemaBuilder()->hasColumn('appointments', 'appointment_date')
            ? 'appointment_date'
            : ((new Appointment)->getConnection()->getSchemaBuilder()->hasColumn('appointments', 'scheduled_at') ? 'scheduled_at' : 'created_at');

        // كشوفات اليوم
        $todayAppointmentsCount = Appointment::whereDate($dateColumn, today())->count();
        $waitingCount = Appointment::whereDate($dateColumn, today())
            ->where('status', AppointmentStatus::Waiting)
            ->count();
        $completedCount = Appointment::whereDate($dateColumn, today())
            ->where('status', AppointmentStatus::Completed)
            ->count();

        return [
            Stat::make('إيراد الخزينة اليوم', number_format($todayRevenue, 2).' ج.م')
                ->description('كاش: '.number_format($todayCash, 0).' | إلكتروني: '.number_format($todayElectronic, 0))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

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
