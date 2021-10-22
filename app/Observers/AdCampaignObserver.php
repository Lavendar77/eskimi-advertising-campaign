<?php

namespace App\Observers;

use App\Models\AdCampaign;
use Illuminate\Support\Facades\Cache;

class AdCampaignObserver
{
    /**
     * Handle the AdCampaign "created" event.
     *
     * @param \App\Models\AdCampaign $adCampaign
     * @return void
     */
    public function created(AdCampaign $adCampaign)
    {
        Cache::forget("ad_campaigns_by_{$adCampaign->user_id}");
    }

    /**
     * Handle the AdCampaign "updated" event.
     *
     * @param \App\Models\AdCampaign $adCampaign
     * @return void
     */
    public function updated(AdCampaign $adCampaign)
    {
        Cache::forget("ad_campaigns_by_{$adCampaign->user_id}");
    }

    /**
     * Handle the AdCampaign "deleted" event.
     *
     * @param \App\Models\AdCampaign $adCampaign
     * @return void
     */
    public function deleted(AdCampaign $adCampaign)
    {
        Cache::forget("ad_campaigns_by_{$adCampaign->user_id}");
    }

    /**
     * Handle the AdCampaign "restored" event.
     *
     * @param \App\Models\AdCampaign $adCampaign
     * @return void
     */
    public function restored(AdCampaign $adCampaign)
    {
        Cache::forget("ad_campaigns_by_{$adCampaign->user_id}");
    }

    /**
     * Handle the AdCampaign "force deleted" event.
     *
     * @param \App\Models\AdCampaign $adCampaign
     * @return void
     */
    public function forceDeleted(AdCampaign $adCampaign)
    {
        Cache::forget("ad_campaigns_by_{$adCampaign->user_id}");
    }
}
