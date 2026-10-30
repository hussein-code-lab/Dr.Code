<?php

namespace App\Filament\Resources\Medicines\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MedicineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('بيانات الدواء الأساسية')
                    ->columns(3)
                    ->schema([
                        TextInput::make('trade_name')
                            ->label('الاسم التجاري للدواء')
                            ->placeholder('مثال: Panadol Extra')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('active_ingredient')
                            ->label('المادة الفعالة (اختياري)')
                            ->placeholder('مثال: Paracetamol + Caffeine')
                            ->maxLength(255),

                        Select::make('dosage_form')
                            ->label('الشكل الدوائي')
                            ->options([
                                'قرص' => 'أقراص (Tablets)',
                                'كبسول' => 'كبسولات (Capsules)',
                                'شراب' => 'شراب (Syrup)',
                                'حقن عضل' => 'حقن عضل (IM Injection)',
                                'حقن وريد' => 'حقن وريد (IV Injection)',
                                'مرهم / كريم' => 'مرهم / كريم (Ointment/Cream)',
                                'نقط' => 'نقط (Drops)',
                                'فوار' => 'فوار (Effervescent)',
                                'بخاخ' => 'بخاخ (Inhaler/Spray)',
                            ])
                            ->default('قرص')
                            ->required(),
                    ]),

                Section::make('الجرعة والتعليمات الافتراضية (للملء التلقائي في الروشتة)')
                    ->columns(2)
                    ->schema([
                        TextInput::make('default_dosage')
                            ->label('الجرعة الافتراضية')
                            ->placeholder('مثال: قرص واحد كل 8 ساعات')
                            ->maxLength(255),

                        TextInput::make('default_instructions')
                            ->label('تعليمات الاستخدام الافتراضية')
                            ->placeholder('مثال: بعد الأكل مباشرة مع كوب ماء كبير')
                            ->maxLength(255),
                    ]),
            ]);
    }
}
