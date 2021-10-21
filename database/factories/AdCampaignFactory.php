<?php

namespace Database\Factories;

use App\Models\AdCampaign;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdCampaignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AdCampaign::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (AdCampaign $adCampaign) {
            //
        })->afterCreating(function (AdCampaign $adCampaign) {
            dispatch(function () use ($adCampaign) {
                $adCampaign->addMediaFromUrl($this->faker->imageUrl())->toMediaCollection();
            });
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $date = now();

        return [
            'name' => 'This is an ad for ' . $this->faker->word(),
            'date_from' => $date,
            'date_to' => $date->addDays(mt_rand(1, 30)),
            'total_budget_in_usd' => mt_rand(0, 500000),
            'daily_budget_in_usd' => mt_rand(0, 500000),
        ];
    }
}
