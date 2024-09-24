<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            Task::factory(rand(3,10))->create([
                'project_id' => $project->id,
            ]);
        }
    }
}
