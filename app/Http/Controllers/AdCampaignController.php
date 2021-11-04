<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdCampaignRequest;
use App\Http\Requests\UpdateAdCampaignRequest;
use App\Models\AdCampaign;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
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

        $adCampaigns = Cache::rememberForever("ad_campaigns_by_{$user->id}", function () use ($user) {
            return $user->adCampaigns()->orderBy('date_from')->get();
        });

        return response()->json([
            'status' => true,
            'message' => 'Ad campaign fetched successfully.',
            'data' => [
                'ad_campaigns' => $adCampaigns,
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
        $adCampaign->date_from = Carbon::parse($request->date_from)->toDateTimeString();
        $adCampaign->date_to = Carbon::parse($request->date_to)->toDateTimeString();
        $adCampaign->total_budget_in_usd = $request->total_budget_in_usd;
        $adCampaign->daily_budget_in_usd = $request->daily_budget_in_usd;
        $adCampaign->save();

        $adCampaign->addMultipleMediaFromRequest(['banner_images'])
            ->each(function ($fileAdder) {
                $fileAdder->toMediaCollection();
            });

        DB::commit();

        return $request->acceptsJson()
            ? response()
                ->json([
                    'status' => true,
                    'message' => 'Ad campaign stored successfully.',
                    'data' => [
                        'ad_campaign' => $adCampaign,
                    ]
                ], Response::HTTP_CREATED)
            : back()->with('status', 'Ad campaign stored successfully.')
        ;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\AdCampaign $adCampaign
     * @return \Illuminate\Http\Response
     */
    public function show(AdCampaign $adCampaign)
    {
        // $this->authorize('view', $adCampaign);

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
        $adCampaign->date_from = Carbon::parse($request->date_from)->toDateTimeString();
        $adCampaign->date_to = Carbon::parse($request->date_to)->toDateTimeString();
        $adCampaign->total_budget_in_usd = $request->total_budget_in_usd;
        $adCampaign->daily_budget_in_usd = $request->daily_budget_in_usd;
        $adCampaign->save();

        if ($request->banner_images) {
            $adCampaign->addMultipleMediaFromRequest(['banner_images'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection();
                });

            if (!$adCampaign->wasChanged()) {
                cache()->forget("ad_campaigns_by_{$adCampaign->user_id}");
            }
        }


        DB::commit();

        return $request->acceptsJson()
            ? response()
                ->json([
                    'status' => true,
                    'message' => 'Ad campaign updated successfully.',
                    'data' => [
                        'ad_campaign' => $adCampaign,
                    ]
                ])
            : back()->with('status', 'Ad campaign updated successfully.')
        ;
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
