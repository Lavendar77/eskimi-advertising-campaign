<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdCampaignRequest;
use App\Http\Requests\UpdateAdCampaignRequest;
use App\Models\AdCampaign;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AdCampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => true,
            'message' => 'Ad campaign restored successfully.',
            'data' => [
                'ad_campaign' => $user->adCampaigns,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdCampaignRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdCampaignRequest $request)
    {
        DB::beginTransaction();

        $adCampaign = new AdCampaign();
        $adCampaign->user()->associate($request->user());
        $adCampaign->name = $request->name;
        $adCampaign->date_from = $request->date_from;
        $adCampaign->date_to = $request->date_to;
        $adCampaign->total_budget_in_usd = $request->total_budget_in_usd;
        $adCampaign->daily_budget_in_usd = $request->daily_budget_in_usd;
        $adCampaign->save();

        $adCampaign->addMediaFromRequest('banner_images')->toMediaCollection();

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Ad campaign stored successfully.',
            'data' => [
                'ad_campaign' => $adCampaign,
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AdCampaign $adCampaign
     * @return \Illuminate\Http\Response
     */
    public function show(AdCampaign $adCampaign)
    {
        $this->authorize('view', $adCampaign);

        return response()->json([
            'status' => true,
            'message' => 'Ad campaign fetched successfully.',
            'data' => [
                'ad_campaign' => $adCampaign,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdCampaignRequest $request
     * @param \App\Models\AdCampaign $adCampaign
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdCampaignRequest $request, AdCampaign $adCampaign)
    {
        $this->authorize('update', $adCampaign);

        DB::beginTransaction();

        $adCampaign->name = $request->name;
        $adCampaign->date_from = $request->date_from;
        $adCampaign->date_to = $request->date_to;
        $adCampaign->total_budget_in_usd = $request->total_budget_in_usd;
        $adCampaign->daily_budget_in_usd = $request->daily_budget_in_usd;
        $adCampaign->save();

        $adCampaign->addMediaFromRequest('banner_images')->toMediaCollection();

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Ad campaign updated successfully.',
            'data' => [
                'ad_campaign' => $adCampaign,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\AdCampaign $adCampaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdCampaign $adCampaign)
    {
        $this->authorize('delete', $adCampaign);

        $adCampaign->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param \App\Models\AdCampaign $adCampaign
     * @return \Illuminate\Http\Response
     */
    public function restore(AdCampaign $adCampaign)
    {
        $this->authorize('restore', $adCampaign);

        $adCampaign->restore();

        return response()->json([
            'status' => true,
            'message' => 'Ad campaign restored successfully.',
            'data' => [
                'ad_campaign' => $adCampaign,
            ]
        ]);
    }
}
