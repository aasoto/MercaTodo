<?php

namespace Database\Factories;

use App\Domain\User\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = State::class;

    public function definition(): array
    {
        do {
            $state = fake()->state();
            $found = State::select('id')->where('name', $state)->get();
        } while (count($found) != 0);

        return [
            'name' => $state,
        ];
    }
}
