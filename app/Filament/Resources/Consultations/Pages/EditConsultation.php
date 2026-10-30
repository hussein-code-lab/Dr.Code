<?php

namespace App\Filament\Resources\Consultations\Pages;

use App\Filament\Resources\Consultations\ConsultationResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditConsultation extends EditRecord
{
    protected static string $resource = ConsultationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('printPrescription')
                ->label('طباعة الروشتة')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->visible(fn () => $this->record->prescription()->exists())
                ->url(fn () => route('prescriptions.print', ['prescription' => $this->record->prescription]))
                ->openUrlInNewTab(),

            DeleteAction::make(),
        ];
    }
}
