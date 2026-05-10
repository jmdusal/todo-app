<?php

namespace App\Services;

use App\Models\Todo;
use App\DTOs\TodoData;
// use App\Events\TodoCreated;

class TodoService
{
    public function createTodo(TodoData $data): Todo
    {
        $todo = Todo::updateOrCreate(
            ['id' => $data->id],
            $data->toUpsertArray()
        );
        // TodoCreated::dispatch($todo);

        return $todo;
    }

    public function deleteTodo(Todo $todo): bool
    {
        return $todo->delete();
    }
}
