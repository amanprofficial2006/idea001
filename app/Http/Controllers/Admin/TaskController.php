<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['user', 'helper'])->paginate(10);
        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.tasks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'budget_type' => 'required|in:fixed,hourly',
            'amount' => 'required|numeric|min:1',
            'urgency_level' => 'required|in:urgent,today,tomorrow,week,custom',
            'duration' => 'nullable|required_if:urgency_level,urgent|integer|min:5',
            'deadline' => 'nullable|required_if:urgency_level,custom|date',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'additional_info' => 'nullable|string',
            'contact_preference' => 'required|in:message,call,both',
            'privacy' => 'required|in:public,verified,invite',
            'status' => 'required|in:pending,accepted,completed,cancelled',
        ]);

        Task::create($request->all());

        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully');
    }

    public function show(Task $task)
    {
        $task->load(['user', 'helper']);
        return view('admin.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $users = User::all();
        return view('admin.tasks.edit', compact('task', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'budget_type' => 'required|in:fixed,hourly',
            'amount' => 'required|numeric|min:1',
            'urgency_level' => 'required|in:urgent,today,tomorrow,week,custom',
            'duration' => 'nullable|required_if:urgency_level,urgent|integer|min:5',
            'deadline' => 'nullable|required_if:urgency_level,custom|date',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'additional_info' => 'nullable|string',
            'contact_preference' => 'required|in:message,call,both',
            'privacy' => 'required|in:public,verified,invite',
            'status' => 'required|in:pending,accepted,completed,cancelled',
        ]);

        $task->update($request->all());

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully');
    }
}
