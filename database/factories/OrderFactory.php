<?php

namespace Database\Factories;

use App\Domain\Order\Models\Order;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::select('id')->where('email', env('CLIENT_EMAIL'))->first(),
            'purchase_date' => fake()->date(),
            'payment_status' => fake()->randomElement(['pending', 'paid']),
        ];
    }
}
