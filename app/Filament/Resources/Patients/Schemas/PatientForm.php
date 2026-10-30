<?php

namespace App\Filament\Resources\Patients\Schemas;

use App\Enums\BloodGroup;
use App\Enums\Gender;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('البيانات الشخصية')
                    ->columns(3)
                    ->schema([
                        TextInput::make('code')
                            ->label('كود المريض')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('يُولّد تلقائياً'),

                        TextInput::make('name')
                            ->label('اسم المريض')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->tel()
                            ->required()
                            ->maxLength(20),

                        TextInput::make('national_id')
                            ->label('الرقم القومي')
                            ->maxLength(14),

                        DatePicker::make('birth_date')
                            ->label('تاريخ الميلاد')
                            ->maxDate(now()),

                        Select::make('gender')
                            ->label('النوع')
                            ->options(Gender::class)
                            ->required(),

                        Select::make('blood_group')
                            ->label('فصيلة الدم')
                            ->options(BloodGroup::class),
                    ]),

                Section::make('التاريخ المرضي المبدئي')
                    ->collapsible()
                    ->schema([
                        TagsInput::make('medical_history.chronic_diseases')
                            ->label('الأمراض المزمنة (إن وجدت)')
                            ->placeholder('أضف مرضاً واضغط Enter (ضغط، سكر...)'),

                        TagsInput::make('medical_history.allergies')
                            ->label('الحساسية الدوائية / الغذائية')
                            ->placeholder('أضف حساسية (بنسلين، سلفا...)'),

                        Textarea::make('notes')
                            ->label('ملاحظات إضافية')
                            ->rows(3),
                    ]),
            ]);
    }
}
