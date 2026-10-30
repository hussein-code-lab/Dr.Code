<?php

namespace App\Filament\Resources\Consultations;

use App\Filament\Resources\Consultations\Pages\EditConsultation;
use App\Filament\Resources\Consultations\Pages\ListConsultations;
use App\Filament\Resources\Consultations\Pages\ViewConsultation;
use App\Filament\Resources\Consultations\Schemas\ConsultationForm;
use App\Filament\Resources\Consultations\Schemas\ConsultationInfolist;
use App\Filament\Resources\Consultations\Tables\ConsultationsTable;
use App\Models\Consultation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ConsultationResource extends Resource
{
    protected static ?string $model = Consultation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'الكشوفات والروشتات';

    protected static ?string $modelLabel = 'كشف';

    protected static ?string $pluralModelLabel = 'سجل الكشوفات';

    protected static string|UnitEnum|null $navigationGroup = 'العيادة والفحص الطبي';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ConsultationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ConsultationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConsultationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListConsultations::route('/'),
            'view' => ViewConsultation::route('/{record}'),
            'edit' => EditConsultation::route('/{record}/edit'),
        ];
    }
}
