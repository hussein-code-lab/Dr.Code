<?php

namespace App\Filament\Resources\Medicines\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MedicinesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('trade_name', 'asc')
            ->columns([
                TextColumn::make('trade_name')
                    ->label('الاسم التجاري')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('active_ingredient')
                    ->label('المادة الفعالة')
                    ->searchable()
                    ->color('gray'),

                TextColumn::make('dosage_form')
                    ->label('الشكل الدوائي')
                    ->badge()
                    ->color('info'),

                TextColumn::make('default_dosage')
                    ->label('الجرعة الافتراضية')
                    ->limit(30),

                TextColumn::make('default_instructions')
                    ->label('تعليمات الاستخدام')
                    ->limit(35),
            ])
            ->filters([
                SelectFilter::make('dosage_form')
                    ->label('الشكل الدوائي')
                    ->options([
                        'قرص' => 'أقراص',
                        'كبسول' => 'كبسولات',
                        'شراب' => 'شراب',
                        'حقن عضل' => 'حقن عضل',
                        'حقن وريد' => 'حقن وريد',
                        'مرهم / كريم' => 'مرهم / كريم',
                        'نقط' => 'نقط',
                        'فوار' => 'فوار',
                        'بخاخ' => 'بخاخ',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
