<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskImage;
use App\Models\TaskSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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

        'duration' => 'nullable|integer|min:1',

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
            $finalDeadline = Carbon::now()->addMinutes($validated['duration']);
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
            'user_id' => auth()->id(),

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

}
