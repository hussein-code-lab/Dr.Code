<?php

namespace App\Filament\Resources\Consultations\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ConsultationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('appointment.queue_number')
                    ->label('الدور')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('patient.name')
                    ->label('اسم المريض')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn ($record) => "كود: {$record->patient?->code}"),

                TextColumn::make('patient.phone')
                    ->label('رقم الهاتف')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('diagnosis')
                    ->label('التشخيص')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->diagnosis)
                    ->searchable(),

                TextColumn::make('prescription_items_count')
                    ->label('عدد الأدوية')
                    ->counts('prescriptionItems')
                    ->badge()
                    ->color('info'),

                TextColumn::make('created_at')
                    ->label('تاريخ الكشف')
                    ->dateTime('d/m/Y - h:i A')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('today')
                    ->label('كشوفات اليوم')
                    ->query(fn (Builder $query) => $query->whereDate('created_at', today())),
            ])
            ->recordActions([
                ViewAction::make()->label('عرض'),
                EditAction::make()->label('تعديل'),
                Action::make('printPrescription')
                    ->label('طباعة الروشتة')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->visible(fn ($record) => $record->prescription()->exists())
                    ->url(fn ($record) => route('prescriptions.print', ['prescription' => $record->prescription]))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
