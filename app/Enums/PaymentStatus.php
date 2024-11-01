<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Partial = 'partial';
    case Paid = 'paid';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';

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
            self::Cancelled => 'danger',
            self::Refunded => ' bg-indigo-400',
        };
    }

    public function getMaryColor(): string | null
    {
        return match ($this) {
            self::Pending => 'warning bg-orange-800 badge-warning',
            self::Cancelled => 'warning bg-orange-800 badge-warning',
            self::Partial => ' bg-orange-500 border-transparent text-white',
            self::Paid => 'success !bg-green-500 text-white border-transparent',
            self::Refunded => 'success !bg-indigo-500 text-white border-transparent',
        };
    }
}
