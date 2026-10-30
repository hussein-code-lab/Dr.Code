<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Enums\AppointmentType;
use App\Enums\Gender;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('تفاصيل الموعد')
                    ->columns(2)
                    ->schema([
                        Select::make('patient_id')
                            ->label('المريض')
                            ->relationship('patient', 'name')
                            ->searchable(['name', 'phone', 'code'])
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('اسم المريض')
                                    ->required(),
                                TextInput::make('phone')
                                    ->label('رقم الهاتف')
                                    ->required(),
                                Select::make('gender')
                                    ->label('النوع')
                                    ->options(Gender::class)
                                    ->required(),
                            ]),

                        DatePicker::make('appointment_date')
                            ->label('تاريخ الكشف')
                            ->default(today())
                            ->required(),

                        Select::make('type')
                            ->label('نوع الموعد')
                            ->options(AppointmentType::class)
                            ->default(AppointmentType::Examination)
                            ->required(),

                        TextInput::make('fee')
                            ->label('سعر الكشف')
                            ->numeric()
                            ->prefix('EGP')
                            ->default(0.00),

                        Toggle::make('is_paid')
                            ->label('تم السداد؟')
                            ->default(false),
                    ]),
            ]);
    }
}
