<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AppointmentStatus: string implements HasColor, HasLabel
{
    case Booked = 'booked';
    case Waiting = 'waiting';
    case InConsultation = 'in_consultation';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case NoShow = 'no_show';

    public function getLabel(): string
    {
        return match ($this) {
            self::Booked => 'محجوز',
            self::Waiting => 'في صالة الانتظار',
            self::InConsultation => 'داخل غرفة الكشف',
            self::Completed => 'مكتمل',
            self::Cancelled => 'ملغي',
            self::NoShow => 'لم يحضر',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Booked => 'gray',
            self::Waiting => 'warning',
            self::InConsultation => 'info',
            self::Completed => 'success',
            self::Cancelled, self::NoShow => 'danger',
        };
    }
}
