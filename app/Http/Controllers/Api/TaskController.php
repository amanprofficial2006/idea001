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
        // ---------------------------
        // 1. VALIDATOR::MAKE()
        // ---------------------------
        $validator = Validator::make($request->all(), [

            'user_id' => 'required|integer|exists:users,id',

            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',

            'budget_type' => 'required|in:fixed,hourly',
            'amount' => 'required|numeric|min:1',

            'urgency_level' => 'required|in:urgent,today,tomorrow,week,custom',

            'help_needed_within' => 'nullable|required_if:urgency_level,urgent|integer|min:5',

            'deadline' => 'nullable|required_if:urgency_level,custom|date',

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

        ], [
            'help_needed_within.required_if' => 'Urgent tasks must include minutes.',
            'deadline.required_if' => 'Custom urgency must have date and time.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // ---------------------------
        // 2. DEADLINE LOGIC
        // ---------------------------
        $finalDeadline = null;

        switch ($validated['urgency_level']) {

            case 'urgent':
                $finalDeadline = Carbon::now()->addMinutes($validated['help_needed_within']);
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

            case 'custom':
                $finalDeadline = $validated['deadline'];
                break;
        }

        DB::beginTransaction();
        try {

            // ---------------------------
            // 3. CREATE TASK
            // ---------------------------
            $task = Task::create([
                'user_id' => $validated['user_id'],
                'title' => $validated['title'],
                'category' => $validated['category'],
                'description' => $validated['description'],

                'budget_type' => $validated['budget_type'],
                'amount' => $validated['amount'],

                'urgency_level' => $validated['urgency_level'],
                'help_needed_within' => $validated['help_needed_within'] ?? null,
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

            // ---------------------------
            // 5. SAVE IMAGES
            // ---------------------------
            if ($request->hasFile('images')) {
                foreach ($request->images as $image) {

                    $path = $image->store('tasks', 'public');

                    TaskImage::create([
                        'task_id' => $task->id,
                        'image' => $path
                    ]);
                }
            }
            // SAVE SKILLS (safe handling)
            if ($request->has('skills') && is_array($validated['skills']) && count($validated['skills']) > 0) {
                foreach ($validated['skills'] as $skill) {

                    if (!empty($skill)) { // ensures no empty inserts
                        TaskSkill::create([
                            'task_id' => $task->id,
                            'skill' => $skill
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task posted successfully',
                'task' => $task
            ], 201);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
