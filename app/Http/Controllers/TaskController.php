<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(Task::STATUSES)],
        ]);

        $tasks = Task::query()
            ->when(
                isset($validated['status']),
                fn ($query) => $query->where('status', $validated['status'])
            )
            ->orderByRaw(Task::prioritySortSql())
            ->orderBy('due_date')
            ->orderBy('id')
            ->get();

        if ($tasks->isEmpty()) {
            return response()->json([
                'message' => 'No tasks found.',
                'tasks' => [],
            ]);
        }

        return response()->json($tasks);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(
            [
                'title' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('tasks')->where(
                        fn ($query) => $query->whereDate('due_date', $request->input('due_date'))
                    ),
                ],
                'due_date' => ['required', 'date', 'after_or_equal:today'],
                'priority' => ['required', Rule::in(Task::PRIORITIES)],
            ],
            [
                'title.unique' => 'A task with this title already exists for the selected due date.',
            ]
        );

        $task = Task::query()->create([
            'title' => $validated['title'],
            'due_date' => $validated['due_date'],
            'priority' => $validated['priority'],
            'status' => Task::STATUS_PENDING,
        ]);

        return response()->json($task, Response::HTTP_CREATED);
    }

    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Task::STATUSES)],
        ]);

        $expectedNextStatus = Task::STATUS_TRANSITIONS[$task->status] ?? null;

        if ($validated['status'] !== $expectedNextStatus) {
            return response()->json([
                'message' => $expectedNextStatus === null
                    ? 'Completed tasks cannot change status.'
                    : "Invalid status transition. The next allowed status is {$expectedNextStatus}.",
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $task->update([
            'status' => $validated['status'],
        ]);

        return response()->json($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        if ($task->status !== Task::STATUS_DONE) {
            return response()->json([
                'message' => 'Only done tasks can be deleted.',
            ], Response::HTTP_FORBIDDEN);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully.',
        ]);
    }

    public function report(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
        ]);

        $summary = [];

        foreach (Task::PRIORITIES as $priority) {
            foreach (Task::STATUSES as $status) {
                $summary[$priority][$status] = 0;
            }
        }

        Task::query()
            ->whereDate('due_date', $validated['date'])
            ->get()
            ->each(function (Task $task) use (&$summary): void {
                $summary[$task->priority][$task->status]++;
            });

        return response()->json([
            'date' => $validated['date'],
            'summary' => $summary,
        ]);
    }
}
