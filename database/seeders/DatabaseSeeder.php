<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::count() == 0) {
            $this->call(UserSeeder::class);
        }
        if (Project::count() == 0) {
            $this->call(ProjectSeeder::class);
        }
        if (Task::count() == 0) {
            $this->call(TaskSeeder::class);
        }
    }
}
