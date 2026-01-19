<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskImage;
use App\Models\TaskSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        // ------------------------------------
        // 1. VALIDATION (NO USER_ID, NO CUSTOM)
        // ------------------------------------
        $validator = Validator::make($request->all(), [

            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',

            'budget_type' => 'required|in:fixed,hourly',
            'amount' => 'required|numeric|min:1',

            'urgency_level' => 'required|in:urgent,today,tomorrow,week',

            'duration' => 'nullable|numeric',

            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',

            'additional_info' => 'nullable|string',

            'contact_preference' => 'required|in:message,call,both',
            'privacy' => 'required|in:public,verified,invite',

            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',

            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:4096'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();


        // ------------------------------------
        // 2. DEADLINE LOGIC (NO CUSTOM OPTION)
        // ------------------------------------
        switch ($validated['urgency_level']) {

            case 'urgent':
                // urgent -> minutes must be given
                if (empty($validated['duration'])) {
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'duration' => ['Urgent tasks need duration in minutes.']
                        ]
                    ], 422);
                }
                $finalDeadline = Carbon::now()->addMinutes((int) $validated['duration']);
                break;

            case 'today':
                $finalDeadline = Carbon::now()->endOfDay();
                break;

            case 'tomorrow':
                $finalDeadline = Carbon::now()->addDay()->endOfDay();
                break;

            case 'week':
                $finalDeadline = Carbon::now()->addDays(7);
                break;
        }


        // ------------------------------------
        // 3. DATABASE TRANSACTION
        // ------------------------------------
        DB::beginTransaction();

        try {

            // ------------------------------------
            // 4. CREATE TASK (TOKEN USER)
            // ------------------------------------
            $task = Task::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'category' => $validated['category'],
                'description' => $validated['description'],

                'budget_type' => $validated['budget_type'],
                'amount' => $validated['amount'],

                'urgency_level' => $validated['urgency_level'],
                'duration' => $validated['duration'] ?? null,
                'deadline' => $finalDeadline,

                'location' => $validated['location'],
                'address' => $validated['address'] ?? null,
                'lat' => $validated['lat'] ?? null,
                'lng' => $validated['lng'] ?? null,

                'additional_info' => $validated['additional_info'] ?? null,

                'contact_preference' => $validated['contact_preference'],
                'privacy' => $validated['privacy'],

                'status' => 'pending'
            ]);


            // ------------------------------------
            // 5. SAVE SKILLS
            // ------------------------------------
            if (!empty($validated['skills'])) {
                foreach ($validated['skills'] as $skill) {
                    if (!empty($skill)) {
                        TaskSkill::create([
                            'task_id' => $task->id,
                            'skill'   => $skill
                        ]);
                    }
                }
            }


            // ------------------------------------
            // 6. SAVE IMAGES
            // ------------------------------------
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {

                    $path = $image->store('tasks', 'public');

                    TaskImage::create([
                        'task_id' => $task->id,
                        'image'   => $path
                    ]);
                }
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task posted successfully.',
                'task' => $task
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function postedTasks()
    {
        $user = Auth::user();

        // Fetch tasks with related images, skills, and helper details
        $tasks = Task::with(['images', 'skills', 'helper:id,name,user_uid'])
            ->where('user_id', $user->id)
            ->orderBy('id', 'DESC')
            ->get();

        // Add full URL to images
        foreach ($tasks as $task) {
            foreach ($task->images as $image) {
                $image->full_url = asset('storage/' . $image->image);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Your tasks fetched successfully.',
            'count' => $tasks->count(),
            'tasks' => $tasks
        ], 200);
    }

    public function show($id)
    {
        $user = Auth::user();

        // Task with relationships and category name
        $task = Task::select('tasks.*', 'categories.name as category_name')
            ->leftJoin(DB::raw('categories on tasks.category = categories.id::text'), function ($join) {})
            ->with([
                'images',
                'skills',
                'user:id,name,user_uid',
                'helper:id,name,user_uid',
            ])->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        // Replace category ID with name
        $task->category = $task->category_name ?? $task->category;
        unset($task->category_name);

        // Add full URL to images
        foreach ($task->images as $image) {
            $image->full_url = asset('storage/' . $image->image);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task details fetched successfully.',
            'task' => $task
        ], 200);
    }

    public function destroy($id)
    {
        // Find task with relations
        $task = Task::with(['skills', 'images'])->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        // Check ownership
        if ($task->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this task.',
            ], 403);
        }

        // Only pending tasks can be deleted
        if ($task->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Task can only be deleted while it is pending.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Delete related skills & images
            $task->skills()->delete();
            $task->images()->delete();

            // Delete task
            $task->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully.',
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function nearTasks()
    {
        $user = Auth::user();

        // Fetch tasks that do NOT belong to logged in user
        // Only PUBLIC tasks will be shown
        $tasks = Task::select('tasks.*', 'categories.name as category_name')
            ->leftJoin(DB::raw('categories on tasks.category = categories.id::text'), function ($join) {})
            ->with(['images', 'skills', 'user:id,name'])
            ->where('tasks.user_id', '!=', $user->id)
            ->where('tasks.privacy', 'public')
            ->orderBy('tasks.id', 'DESC')
            ->get();

        // Add full image URL and replace category with name
        foreach ($tasks as $task) {
            foreach ($task->images as $image) {
                $image->full_url = asset('storage/' . $image->image);
            }
            $task->category = $task->category_name ?? $task->category;
            unset($task->category_name);
        }

        return response()->json([
            'success' => true,
            'message' => 'Nearby tasks fetched successfully.',
            'count' => $tasks->count(),
            'tasks' => $tasks
        ], 200);
    }

    public function acceptTask($id)
    {
        $user = Auth::user();

        // Find the task
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        // Check if task is already accepted or completed
        if ($task->status !== 'pending' && $task->status !== 'open') {
            return response()->json([
                'success' => false,
                'message' => 'Task is not available for acceptance.',
            ], 422);
        }

        // Check if user is not the task owner
        if ($task->user_id == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot accept your own task.',
            ], 403);
        }

        // Check if task is already assigned to someone else
        if ($task->helper_id && $task->helper_id != $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Task is already accepted by another helper.',
            ], 422);
        }

        // Update task status and assign helper
        $task->update([
            'status' => 'accepted',
            'helper_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task accepted successfully.',
            'task' => $task
        ], 200);
    }

    public function acceptedTasks()
    {
        $user = Auth::user();

        // Fetch tasks accepted by the helper with related images, skills, and user details
        $tasks = Task::select('tasks.*', 'categories.name as category_name')
            ->leftJoin(DB::raw('categories on tasks.category = categories.id::text'), function ($join) {})
            ->with(['images', 'skills', 'user:id,name,user_uid'])
            ->where('helper_id', $user->id)
            ->whereIn('status', ['accepted', 'in-progress', 'completed'])
            ->orderBy('id', 'DESC')
            ->get();

        // Add full URL to images and replace category with name
        foreach ($tasks as $task) {
            foreach ($task->images as $image) {
                $image->full_url = asset('storage/' . $image->image);
            }
            $task->category = $task->category_name ?? $task->category;
            unset($task->category_name);
        }

        return response()->json([
            'success' => true,
            'message' => 'Accepted tasks fetched successfully.',
            'count' => $tasks->count(),
            'tasks' => $tasks
        ], 200);
    }
}
