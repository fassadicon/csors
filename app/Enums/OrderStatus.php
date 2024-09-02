<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus : string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Confirmed => 'blue',
            self::Completed => 'success',
            self::Cancelled => 'danger',
        };
    }
}
