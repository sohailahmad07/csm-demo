<div>
    <x-dashboard.header/>

    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-kpi-card title="Total Clients" :value="$this->totalClients" :description="$this->activeClients . ' active'"/>
        <x-kpi-card title="At Risk" :value="$this->atRiskCount" description="Need attention"
                    valueClass="text-red-600 dark:text-red-400" descriptionClass="text-red-500 dark:text-red-400"/>
        <x-kpi-card title="Onboarding" :value="$this->onboardingCount" description="In setup"
                    valueClass="text-amber-600 dark:text-amber-400" descriptionClass="text-amber-500 dark:text-amber-400"/>
        <x-kpi-card title="Total Collected" :value="'$' . number_format($this->totalCollected, 0)" description="All time"
                    descriptionClass="text-green-600 dark:text-green-400"/>
    </div>

    <x-dashboard.monthly-chart/>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-dashboard.at-risk-clients/>
        <x-dashboard.onboarding-clients/>
    </div>
</div>
