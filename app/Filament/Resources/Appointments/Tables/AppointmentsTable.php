<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Actions\CheckInPatientAction;
use App\Actions\CollectAppointmentFeeAction;
use App\Actions\StartConsultationAction;
use App\Enums\AppointmentStatus;
use App\Enums\PaymentMethod;
use App\Filament\Resources\Consultations\ConsultationResource;
use App\Models\Appointment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('queue_number', 'asc')
            ->columns([
                TextColumn::make('queue_number')
                    ->label('الدور')
                    ->badge()
                    ->sortable(),

                TextColumn::make('patient.name')
                    ->label('المريض')
                    ->searchable()
                    ->description(fn (Appointment $record) => $record->patient->phone),

                TextColumn::make('appointment_date')
                    ->label('التاريخ')
                    ->date('Y-m-d')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('النوع')
                    ->badge(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge(),

                IconColumn::make('is_paid')
                    ->label('مدفوع')
                    ->boolean(),

                TextColumn::make('fee')
                    ->label('القيمة')
                    ->money('EGP'),
            ])
            ->filters([
                Filter::make('today')
                    ->label('مواعيد اليوم فقط')
                    ->default()
                    ->query(fn ($query) => $query->whereDate('appointment_date', today())),

                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(AppointmentStatus::class),
            ])
            ->recordActions([
                Action::make('checkIn')
                    ->label('تسجيل وصول')
                    ->icon('heroicon-m-arrow-right-end-on-rectangle')
                    ->color('warning')
                    ->visible(fn (Appointment $record) => $record->status === AppointmentStatus::Booked)
                    ->action(function (Appointment $record) {
                        app(CheckInPatientAction::class)->execute($record);

                        Notification::make()
                            ->title('تم تسجيل وصول المريض لصالة الانتظار')
                            ->success()
                            ->send();
                    }),

                Action::make('startConsultation')
                    ->label('بدء الكشف')
                    ->icon('heroicon-m-play')
                    ->color('success')
                    ->visible(fn (Appointment $record) => in_array($record->status, [AppointmentStatus::Waiting, AppointmentStatus::InConsultation]))
                    ->action(function (Appointment $record) {
                        $consultation = app(StartConsultationAction::class)->execute($record);

                        return redirect(ConsultationResource::getUrl('edit', ['record' => $consultation]));
                    }),

                Action::make('collectPayment')
                    ->label('تحصيل الرسوم')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => ! $record->is_paid)
                    ->form([
                        Placeholder::make('patient_info')
                            ->label('المريض')
                            ->content(fn ($record) => "{$record->patient?->name} ({$record->patient?->code})"),

                        TextInput::make('fee_display')
                            ->label('قيمة الكشف المقررة')
                            ->default(fn ($record) => "{$record->fee} ج.م")
                            ->disabled(),

                        Select::make('payment_method')
                            ->label('طريقة الدفع')
                            ->options(PaymentMethod::class)
                            ->default(PaymentMethod::Cash)
                            ->required(),
                    ])
                    ->modalHeading('تحصيل رسوم الكشف')
                    ->modalSubmitActionLabel('تأكيد السداد وإصدار الفاتورة')
                    ->action(function ($record, array $data) {
                        $feeAction = app(CollectAppointmentFeeAction::class);

                        // التعامل مع القيمة سواء جاءت كـ Enum Object أو String
                        $paymentMethod = $data['payment_method'] instanceof PaymentMethod
                            ? $data['payment_method']
                            : PaymentMethod::from($data['payment_method']);

                        $feeAction->execute(
                            appointment: $record,
                            paymentMethod: $paymentMethod,
                            collectedBy: auth('admin')->id()
                        );

                        Notification::make()
                            ->title('تم التحصيل بنجاح')
                            ->body("تم سداد مبلغ {$record->fee} ج.م وإصدار الفاتورة.")
                            ->success()
                            ->send();
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
