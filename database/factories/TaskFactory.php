<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'due_date' => fake()->dateTimeBetween('today', '+30 days')->format('Y-m-d'),
            'priority' => fake()->randomElement(Task::PRIORITIES),
            'status' => Task::STATUS_PENDING,
        ];
    }
}
