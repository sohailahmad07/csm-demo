<?php

namespace App\Enums;

enum ClientStatus: string
{
    case Onboarding = 'onboarding';
    case Active = 'active';
    case AtRisk = 'at_risk';

    public function label(): string
    {
        return match ($this) {
            self::Onboarding => 'Onboarding',
            self::Active => 'Active',
            self::AtRisk => 'At Risk',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Onboarding => 'amber',
            self::Active => 'green',
            self::AtRisk => 'red',
        };
    }
}
