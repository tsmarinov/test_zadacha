<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->text(100),
            'description' => $this->faker->text(500),
            'status' => $this->faker->randomElement([
                Project::STATUS_PENDING,
                Project::STATUS_IN_PROGRESS,
                Project::STATUS_COMPLETED
            ]),
            'duration' => $this->faker->numberBetween(10, 120)
        ];
    }
}
