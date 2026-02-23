<?php

namespace App\Models;

use Database\Factories\OnboardingStepFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingStep extends Model
{
    /** @use HasFactory<OnboardingStepFactory> */
    use HasFactory;

    /** @formatter:off */
    /** @var array<int, array{slug: string, name: string, group: string, sort: int}> */
    public const array TEMPLATES = [
        ['name' => 'Create Agency Account', 'group' => 'Account Setup', 'position' => 1, 'group_position' => 1],
        ['name' => 'Verify Email Address', 'group' => 'Account Setup', 'position' => 2, 'group_position' => 1],
        ['name' => 'Upload Brand Logo & Colors', 'group' => 'Account Setup', 'position' => 3, 'group_position' => 1],
        ['name' => 'Connect Bank Account (ACH)', 'group' => 'Payment Configuration', 'position' => 1, 'group_position' => 2],
        ['name' => 'Set Up Merchant Processing', 'group' => 'Payment Configuration', 'position' => 2, 'group_position' => 2],
        ['name' => 'Configure Payment Plan Rules', 'group' => 'Payment Configuration', 'position' => 3, 'group_position' => 2],
        ['name' => 'Import Debtor Accounts', 'group' => 'Campaign Setup', 'position' => 1, 'group_position' => 3],
        ['name' => 'Launch First Email Campaign', 'group' => 'Campaign Setup', 'position' => 2, 'group_position' => 3],
        ['name' => 'Process Test Payment', 'group' => 'Go Live', 'position' => 1, 'group_position' => 4],
        ['name' => 'Activate Live Collections', 'group' => 'Go Live', 'position' => 2, 'group_position' => 4],
    ];

    /** @formatter:on */
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'completed_at' => 'datetime',
            'due_at' => 'datetime',
        ];
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function isOverdue(): bool
    {
        return ! $this->isCompleted() && $this->due_at?->isPast();
    }

    public static function booted()
    {
        parent::booted();
        static::creating(function (OnboardingStep $onboardingStep) {
            $count = OnboardingStep::where('name', $onboardingStep->name)->count();
            $onboardingStep->slug = str($onboardingStep->slug).'-'.($count + 1);
        });
    }
}
