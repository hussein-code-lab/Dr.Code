<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Enums\PaymentMethod;
use App\Models\Invoice;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('paid_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('# الفاتورة')
                    ->sortable(),

                TextColumn::make('patient.name')
                    ->label('اسم المريض')
                    ->weight('bold')
                    ->searchable()
                    ->description(fn (Invoice $record) => "كود: {$record->patient?->code}"),

                TextColumn::make('amount')
                    ->label('المبلغ')
                    ->money('EGP')
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('طريقة الدفع')
                    ->badge(),

                TextColumn::make('paid_at')
                    ->label('وقت السداد')
                    ->dateTime('d/m/Y - h:i A')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('today')
                    ->label('تحصيل اليوم')
                    ->query(fn (Builder $query) => $query->whereDate('paid_at', today())),

                SelectFilter::make('payment_method')
                    ->label('طريقة الدفع')
                    ->options(PaymentMethod::class),
            ])
            ->recordActions([
                ViewAction::make()->label('عرض السند'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
