<div>
    <x-show-client.header :client="$client"/>

    @island('kpiCards', lazy: true)
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-kpi-card title="Total Collected" :value="Number::currency($this->totalCollected)"
                    :description="'payments all time'.Number::format($this->paymentCount)"/>
        <x-kpi-card title="This Month" :value="Number::currency($this->thisMonthCollected)"
                    :description="'of '. Number::currency($client->monthly_goal) . ' goal'"/>
        <x-kpi-card :title="$client->go_live_at->isNowOrPast() ? 'Day Active' : 'Will Be Live At'"
                    :value="$client->go_live_at->isNowOrPast() ? (int) $client->go_live_at->diffInDays(now()) : $client->go_live_at->format('j M, Y')"
                    :description="$client->go_live_at->isNowOrPast() ? 'Since '. $client->go_live_at->format('j M, Y') : ''"
        />
    </div>
    @endisland

    <x-show-client.monthly-goal-progress-bar :client="$client"/>

    @island('onboardingStepsBar')
    <x-show-client.onboarding-progress-bar :client="$client"/>
    @endisland

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @island('onboardingStepsBar')
        <div class="space-y-4 lg:col-span-2">
            @foreach ($this->groupedSteps as $group => $steps)
                <x-show-client.onboarding-checklist.card :steps="$steps" :group="$group"/>
            @endforeach
        </div>
        @endisland


        <!--right sidebar-->
        <div class="space-y-6">
            <x-show-client.notes :client="$client"/>
            <x-show-client.recent-payment/>
            <x-show-client.activity-timeline/>
        </div>
    </div>

</div>
