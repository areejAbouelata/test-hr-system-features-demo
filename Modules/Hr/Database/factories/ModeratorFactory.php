<?php

namespace Modules\Hr\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Hr\App\Models\Department;
use Modules\Hr\App\Models\Moderator;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ModeratorFactory extends Factory
{
    protected $model = Moderator::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'department_id' => function () {
                return Department::factory()->create()->id;
            },
            'branch_id' => $this->faker->numberBetween(1, 10),
            'job' => $this->faker->jobTitle,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'craft' => $this->faker->word,
            'remember_token' => \Illuminate\Support\Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
