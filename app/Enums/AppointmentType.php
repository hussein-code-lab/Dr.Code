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

    public function defaultFee(): float
    {
        return match ($this) {
            self::Examination => 300.00,
            self::FollowUp => 150.00,
            self::Consultation => 200.00,
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Examination => 'فحص طبي شامل وتشخيص كامل مع كتابة الروشتة',
            self::FollowUp => 'متابعة الحالة ومراجعة نتائج التحاليل والأشعة',
            self::Consultation => 'استشارة طبية سريعة ومراجعة خطة العلاج',
        };
    }
}
