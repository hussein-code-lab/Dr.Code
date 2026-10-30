<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AppointmentType: string implements HasColor, HasLabel
{
    case Examination = 'examination';
    case FollowUp = 'follow_up';
    case Consultation = 'consultation';

    public function getLabel(): string
    {
        return match ($this) {
            self::Examination => 'كشف جديد',
            self::FollowUp => 'إعادة / متابعة',
            self::Consultation => 'استشارة',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Examination => 'primary',
            self::FollowUp => 'warning',
            self::Consultation => 'info',
        };
    }
}
