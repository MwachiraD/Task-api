<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        $tasks = [
            [
                'title' => 'Review assignment brief',
                'due_date' => $today->toDateString(),
                'priority' => Task::PRIORITY_HIGH,
                'status' => Task::STATUS_IN_PROGRESS,
            ],
            [
                'title' => 'Design task API routes',
                'due_date' => $today->copy()->addDay()->toDateString(),
                'priority' => Task::PRIORITY_HIGH,
                'status' => Task::STATUS_PENDING,
            ],
            [
                'title' => 'Write feature tests',
                'due_date' => $today->copy()->addDays(2)->toDateString(),
                'priority' => Task::PRIORITY_MEDIUM,
                'status' => Task::STATUS_PENDING,
            ],
            [
                'title' => 'Draft deployment notes',
                'due_date' => $today->copy()->addDays(3)->toDateString(),
                'priority' => Task::PRIORITY_LOW,
                'status' => Task::STATUS_DONE,
            ],
        ];

        foreach ($tasks as $task) {
            Task::query()->updateOrCreate(
                [
                    'title' => $task['title'],
                    'due_date' => $task['due_date'],
                ],
                [
                    'priority' => $task['priority'],
                    'status' => $task['status'],
                ]
            );
        }
    }
}
