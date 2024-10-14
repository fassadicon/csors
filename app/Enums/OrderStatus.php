<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case To_Review = 'to_review';

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Confirmed => 'blue',
            self::Completed => 'success',
            self::Cancelled => 'danger',
            self::To_Review => 'outline',
        };
    }

    public function getMaryColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Confirmed => 'outline !border-[1px] !border-black/25',
            self::Completed => 'success',
            self::Cancelled => 'error bg-red-500 text-white',
            self::To_Review => 'outline',
        };
    }
}
