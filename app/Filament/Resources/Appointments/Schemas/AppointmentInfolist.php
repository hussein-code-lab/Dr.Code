<?php

namespace App\Filament\Resources\Appointments\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppointmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('بيانات الموعد')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('queue_number')
                            ->label('رقم الدور')
                            ->prefix('#')
                            ->badge(),
                        TextEntry::make('source')
                            ->label('مصدر الحجز')
                            ->badge(),
                        TextEntry::make('patient.name')
                            ->label('اسم المريض'),
                        TextEntry::make('patient.phone')
                            ->label('رقم الهاتف'),
                        TextEntry::make('patient.code')
                            ->label('كود المريض'),
                        TextEntry::make('appointment_date')
                            ->label('تاريخ الكشف')
                            ->date('Y-m-d'),
                        TextEntry::make('type')
                            ->label('نوع الكشف')
                            ->badge(),
                        TextEntry::make('status')
                            ->label('حالة الحجز')
                            ->badge(),
                        TextEntry::make('fee')
                            ->label('سعر الكشف')
                            ->money('EGP'),
                        IconEntry::make('is_paid')
                            ->label('تم السداد؟')
                            ->boolean(),
                        TextEntry::make('notes')
                            ->label('ملاحظات المريض')
                            ->placeholder('لا توجد ملاحظات')
                            ->columnSpanFull(),
                        TextEntry::make('created_at')
                            ->label('تاريخ إنشاء الحجز')
                            ->dateTime('Y-m-d H:i')
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
