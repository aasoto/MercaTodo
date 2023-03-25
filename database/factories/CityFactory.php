<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = City::class;

    public function definition(): array
    {
        return [
            'name' => fake()->city(),
            'state_id' => $this->find_state(),
        ];
    }

    public function find_state()
    {
        return State::select('id')->inRandomOrder()->first();
    }

}
