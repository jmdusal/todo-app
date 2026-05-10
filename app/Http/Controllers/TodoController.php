<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todos\TodoStoreRequest;
use App\DTOs\TodoData;
use App\Events\TodoCreated;
use App\Services\TodoService;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller
{
    public function __construct(
        protected TodoService $todoService
    ) {}

    public function index()
    {
        return Inertia::render('todos/Index', [
            'todos' => Todo::with('user:id,name')->latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('todos/Create', [
            'users' => User::all(['id', 'name'])
        ]);
    }

    public function store(TodoStoreRequest $request)
    {
        $dto = TodoData::fromRequest($request);
        $todo = $this->todoService->createTodo($dto);

        TodoCreated::dispatch($todo);

        return to_route('todos.index')->with([
            'data' => $todo,
            'message' => 'Todo created successfully!'
        ]);
    }

    public function edit(Todo $todo)
    {
        return Inertia::render('todos/Edit', [
            'todo' => $todo,
            'users' => User::all(['id', 'name'])
        ]);
    }

    public function destroy(Todo $todo)
    {
        $this->todoService->deleteTodo($todo);

        return to_route('todos.index')->with('message', 'Todo deleted successfully!');
    }
}
