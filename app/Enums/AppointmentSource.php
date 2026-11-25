<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AppointmentSource: string implements HasColor, HasLabel
{
    case Reception = 'reception';
    case Online = 'online';

    public function getLabel(): string
    {
        return match ($this) {
            self::Reception => 'الاستقبال',
            self::Online => 'حجز إلكتروني',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Reception => 'gray',
            self::Online => 'info',
        };
    }
}
