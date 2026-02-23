<?php

namespace App\Enums;

enum ClientPlan: string
{
    case Starter = 'starter';
    case Growth = 'growth';
    case Enterprise = 'enterprise';

    public function label(): string
    {
        return match ($this) {
            self::Starter => 'Starter',
            self::Growth => 'Growth',
            self::Enterprise => 'Enterprise',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Starter => 'zinc',
            self::Growth => 'blue',
            self::Enterprise => 'indigo',
        };
    }
}
