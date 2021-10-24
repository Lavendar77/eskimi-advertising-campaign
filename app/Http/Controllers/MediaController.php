<?php

namespace App\Http\Controllers;

use App\Models\AdCampaign;
use App\Models\Media;
use Illuminate\Http\Response;

class MediaController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AdCampaign $adCampaign
     * @param \App\Models\Media $media
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdCampaign $adCampaign, Media $media)
    {
        $this->authorize('delete', $adCampaign);

        $media->forceDelete();

        cache()->forget("ad_campaigns_by_{$adCampaign->user_id}");

        return response('', Response::HTTP_NO_CONTENT);
    }
}
