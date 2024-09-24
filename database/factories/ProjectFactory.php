<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->text(Project::MAX_LENGTH_TITLE),
            'description' => $this->faker->text(Project::MAX_LENGTH_DESCRIPTION),
            'status' => $this->faker->randomElement([
                Project::STATUS_PENDING,
                Project::STATUS_IN_PROGRESS,
                Project::STATUS_COMPLETED
            ])
        ];
    }
}
