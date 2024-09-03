<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CancellationRequestStatus : string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Declined = 'declined';
    case Approved = 'approved';

    public function getLabel(): ?string
    {
        return $this->name;
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Declined => 'danger',
            self::Approved => 'success',
        };
    }
}
