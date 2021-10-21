<?php

namespace Database\Seeders;

use App\Models\AdCampaign;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdCampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdCampaign::factory()
            ->count(10)
            ->for(User::factory()->create())
            ->create();
    }
}
