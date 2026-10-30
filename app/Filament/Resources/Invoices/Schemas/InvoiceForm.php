<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\PaymentMethod;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('بيانات الفاتورة')
                    ->columns(2)
                    ->schema([
                        TextInput::make('patient.name')
                            ->label('المريض')
                            ->disabled(),

                        TextInput::make('amount')
                            ->label('المبلغ المحصل')
                            ->prefix('EGP')
                            ->disabled(),

                        Select::make('payment_method')
                            ->label('طريقة الدفع')
                            ->options(PaymentMethod::class)
                            ->disabled(),

                        DateTimePicker::make('paid_at')
                            ->label('تاريخ ووقت السداد')
                            ->disabled(),
                    ]),
            ]);
    }
}
