<?php

namespace App\Models;

use App\Enums\ClientPlan;
use App\Enums\ClientStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_name',
        'contact_email',
        'plan',
        'status',
        'monthly_goal',
        'go_live_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => ClientStatus::class,
            'plan' => ClientPlan::class,
            'monthly_goal' => 'decimal:2',
            'go_live_at' => 'datetime',
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function onboardingSteps(): HasMany
    {
        return $this->hasMany(OnboardingStep::class);
    }

    public function signedUpAt(): Attribute
    {
        return Attribute::get(function (): string {
            $days = (int) $this->created_at->diffInDays(now());

            return match (true) {
                $days === 0 => 'Today',
                $days === 1 => 'Yesterday',
                $days < 7 => $days.' days ago',
                default => $this->created_at->format('j M, Y'),
            };
        });
    }

    public function onboardingProgressPercent(): int
    {
        $steps = $this->onboardingSteps;

        if ($steps->isEmpty()) {
            return 0;
        }

        return (int) round($steps->filter(fn ($s) => $s->isCompleted())->count() / $steps->count() * 100);
    }
}
