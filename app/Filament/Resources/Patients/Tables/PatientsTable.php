<?php

namespace App\Filament\Resources\Patients\Tables;

use App\Enums\Gender;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('الكود')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('phone')
                    ->label('الهاتف')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('gender')
                    ->label('النوع')
                    ->badge(),

                TextColumn::make('blood_group')
                    ->label('الفصيلة')
                    ->badge()
                    ->color('danger'),

                TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->date('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('gender')
                    ->label('النوع')
                    ->options(Gender::class),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
