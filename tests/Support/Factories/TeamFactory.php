<?php

namespace Maize\Badges\Tests\Support\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Maize\Badges\Tests\Support\Models\Team;

class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model|TModel>
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
