<?php

namespace App\Filament\Resources\Consultations\Schemas;

use App\Models\Medicine;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ConsultationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('تفاصيل الكشف الطبي والروشتة')
                    ->tabs([
                        Tab::make('الفحص السريري')
                            ->icon('heroicon-m-heart')
                            ->schema([
                                Section::make('العلامات الحيوية (Vitals)')
                                    ->columns(4)
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('vitals.blood_pressure')
                                            ->label('ضغط الدم')
                                            ->placeholder('120/80'),

                                        TextInput::make('vitals.pulse')
                                            ->label('النبض (bpm)')
                                            ->numeric()
                                            ->placeholder('75'),

                                        TextInput::make('vitals.temperature')
                                            ->label('الحرارة (°C)')
                                            ->numeric()
                                            ->placeholder('37.0'),

                                        TextInput::make('vitals.weight')
                                            ->label('الوزن (kg)')
                                            ->numeric()
                                            ->placeholder('70'),
                                    ]),

                                Textarea::make('chief_complaint')
                                    ->label('شكوى المريض والأعراض')
                                    ->required()
                                    ->rows(3),

                                Textarea::make('diagnosis')
                                    ->label('التشخيص الطبي')
                                    ->required()
                                    ->rows(3),

                                Textarea::make('internal_notes')
                                    ->label('ملاحظات الطبيب السرية')
                                    ->rows(2),
                            ]),

                        Tab::make('الروشتة الطبية (Rx)')
                            ->icon('heroicon-m-document-text')
                            ->schema([
                                Group::make()
                                    ->relationship('prescription')
                                    ->schema([
                                        Textarea::make('general_advice')
                                            ->label('نصائح وإرشادات عامة للمريض')
                                            ->rows(2)
                                            ->placeholder('مثال: راحة، الإكثار من السوائل...'),

                                        Repeater::make('items')
                                            ->label('قائمة الأدوية')
                                            ->relationship('items')
                                            ->columns(12)
                                            ->addActionLabel('إضافة دواء للروشتة')
                                            ->schema([
                                                Select::make('medicine_id')
                                                    ->label('اختيار من الأدوية السريعة')
                                                    ->options(fn () => class_exists(Medicine::class) ? Medicine::pluck('trade_name', 'id') : [])
                                                    ->searchable()
                                                    ->live()
                                                    ->columnSpan(4)
                                                    ->afterStateUpdated(function (Get $get, Set $set, ?int $state) {
                                                        if (! $state) {
                                                            return;
                                                        }
                                                        $medicine = Medicine::find($state);
                                                        if ($medicine) {
                                                            $set('medicine_name', $medicine->trade_name);
                                                            $set('dosage', $medicine->default_dosage ?? 'قرص');
                                                            $set('instructions', $medicine->default_instructions);
                                                        }
                                                    }),

                                                TextInput::make('medicine_name')
                                                    ->label('اسم الدواء')
                                                    ->required()
                                                    ->columnSpan(4),

                                                TextInput::make('dosage')
                                                    ->label('الجرعة')
                                                    ->placeholder('قرص / 5 مل')
                                                    ->required()
                                                    ->columnSpan(2),

                                                TextInput::make('frequency')
                                                    ->label('التكرار')
                                                    ->placeholder('كل 8 ساعات')
                                                    ->required()
                                                    ->columnSpan(2),

                                                TextInput::make('duration')
                                                    ->label('المدة')
                                                    ->placeholder('5 أيام')
                                                    ->columnSpan(3),

                                                TextInput::make('instructions')
                                                    ->label('تعليمات التناول')
                                                    ->placeholder('بعد الأكل')
                                                    ->columnSpan(9),
                                            ])
                                            ->defaultItems(1),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);

    }
}
