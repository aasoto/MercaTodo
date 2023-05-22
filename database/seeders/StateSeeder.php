<?php

namespace Database\Seeders;

use App\Domain\User\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 5; $i++) {
            do {
                $state = fake()->state();
                $found = State::select('id')->where('name', $state)->get();
            } while (count($found) != 0);

            State::factory()->create([
                'name' => $state,
            ]);
        }
    }
}
