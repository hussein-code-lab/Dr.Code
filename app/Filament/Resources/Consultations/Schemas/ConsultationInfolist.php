<?php

namespace App\Filament\Resources\Consultations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ConsultationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('appointment_id')
                    ->numeric(),
                TextEntry::make('patient_id')
                    ->numeric(),
                TextEntry::make('chief_complaint')
                    ->columnSpanFull(),
                TextEntry::make('diagnosis')
                    ->columnSpanFull(),
                TextEntry::make('internal_notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
