<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Completed = 'completed';
    case Pending = 'pending';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Completed => 'Completed',
            self::Pending => 'Pending',
            self::Failed => 'Failed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Completed => 'green',
            self::Pending => 'yellow',
            self::Failed => 'red',
        };
    }
}
