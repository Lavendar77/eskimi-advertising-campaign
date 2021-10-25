<?php

namespace Tests\Feature;

use App\Models\AdCampaign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdCampaignApiTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_get_all_ad_campaigns_via_api()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/ad-campaigns', [
            'Accept' => 'application/json',
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'ad_campaigns'
            ]
        ]);
    }

    public function test_new_ad_campaigns_can_be_stored_via_api()
    {
        $date = date('Y-m-d');
        $user = User::factory()->create();

        // copy sample file first
        copy(resource_path('/images/sample.png'), resource_path('/test-files/sample.png'));

        $response = $this->actingAs($user)->post(
            '/api/ad-campaigns',
            [
                'name' => 'This is an ad for ' . $this->faker->word(),
                'date_from' => $date,
                'date_to' => date('Y-m-d', strtotime($date . ' + 1 days')),
                'total_budget_in_usd' => 5000,
                'daily_budget_in_usd' => 1000,
                'banner_images' => [
                    new \Illuminate\Http\UploadedFile(
                        path: resource_path('test-files/sample.png'),
                        originalName: 'sample.png',
                        test: true
                    ),
                ]
            ],
            [
                'Accept' => 'application/json',
            ]
        );

        $response->assertCreated();

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'ad_campaign'
            ]
        ]);
    }

    public function test_ad_campaign_can_be_fetched_via_api()
    {
        $user = User::factory()->create();
        $adCampaign = AdCampaign::factory()->count(1)->for($user)->create()->first()->id;

        $response = $this->actingAs($user)
            ->get("/api/ad-campaigns/{$adCampaign}", [
                'Accept' => 'application/json',
            ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'ad_campaign'
            ]
        ]);
    }

    public function test_ad_campaign_can_be_updated_via_api()
    {
        $user = User::factory()->create();
        $adCampaign = AdCampaign::factory()->count(1)->for($user)->create()->first();

        // copy sample file first
        copy(resource_path('/images/sample.png'), resource_path('/test-files/sample.png'));

        $response = $this->actingAs($user)->post(
            "/api/ad-campaigns/{$adCampaign->id}",
            [
                'name' => 'This is an ad for ' . $this->faker->word(),
                'date_from' => $adCampaign->date_from,
                'date_to' => date('Y-m-d', strtotime($adCampaign->date_from . ' + 1 days')),
                'total_budget_in_usd' => 5000,
                'daily_budget_in_usd' => 1000,
                'banner_images' => [
                    new \Illuminate\Http\UploadedFile(
                        path: resource_path('test-files/sample.png'),
                        originalName: 'sample.png',
                        test: true
                    ),
                ],
                '_method' => 'PUT',
            ],
            [
                'Accept' => 'application/json',
            ]
        );

        $response->assertOk();

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'ad_campaign'
            ]
        ]);
    }

    public function test_ad_campaign_can_be_deleted_via_api()
    {
        $user = User::factory()->create();
        $adCampaign = AdCampaign::factory()->count(1)->for($user)->create()->first()->id;

        $response = $this->actingAs($user)->delete(
            "/api/ad-campaigns/{$adCampaign}",
            [
                'Accept' => 'application/json',
            ]
        );

        $response->assertNoContent();
    }

    public function test_ad_campaign_can_be_restored_via_api()
    {
        $user = User::factory()->create();
        $adCampaign = AdCampaign::factory()->count(1)->for($user)->deleted()->create()->first()->id;

        $response = $this->actingAs($user)->post(
            "/api/ad-campaigns/{$adCampaign}/restore",
            [
                'Accept' => 'application/json',
            ]
        );

        $response->assertOk();

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'ad_campaign'
            ]
        ]);
    }
}
