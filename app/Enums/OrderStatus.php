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
    case Declined = 'declined';

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
            self::Declined => 'danger',
        };
    }

    public function getMaryColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'warning !bg-orange-500 ',
            self::Confirmed => 'success !bg-green-500 text-white',
            self::Completed => 'success !bg-blue-500 text-white',
            self::Cancelled => 'error bg-red-500 text-white',
            self::Declined => 'error bg-red-500 text-white',
        };
    }
}
