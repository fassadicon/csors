<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Partial = 'partial';
    case Paid = 'paid';

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'danger',
            self::Partial => 'amber',
            self::Paid => 'success',
        };
    }

    public function getMaryColor(): string | null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Partial => 'accent bg-orange-500 border-transparent text-white',
            self::Paid => 'accent bg-yellow-500 border-transparent ',
        };
    }
}
