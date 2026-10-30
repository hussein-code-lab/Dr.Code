<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentMethod: string implements HasLabel
{
    case Cash = 'cash';
    case Card = 'card';
    case DigitalWallet = 'digital_wallet';

    public function getLabel(): string
    {
        return match ($this) {
            self::Cash => 'نقدي (كاش)',
            self::Card => 'بطاقة بنكية',
            self::DigitalWallet => 'محفظة إلكترونية',
        };
    }
}
