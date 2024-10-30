<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    // Create a new task
    public function store(Request $request) : JsonResponse
    {
        $request->merge([
            'status' => strtolower($request->status ?? 'pending')
        ]);

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:tasks',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after:today',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'pending',
            'due_date' => $request->due_date,
        ]);

        return response()->json($task, Response::HTTP_CREATED);
    }

    // Get all tasks
    public function index() : JsonResponse
    {
        $tasks = Task::all();

        return response()->json($tasks, Response::HTTP_OK);
    }

    // Get a specific task by ID
    public function show(int $id) : JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($task, Response::HTTP_OK);
    }

    // Update a task by ID
    public function update(Request $request, int $id) : JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $request->merge([
            'status' => strtolower($request->status ?? 'pending')
        ]);

    $validator = Validator::make($request->all(), [
        'title' => 'nullable|string',
        'description' => 'nullable|string',
        'due_date' => 'nullable|date|after:today',
    ]);
        

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $task->update($request->only(['title', 'description', 'status', 'due_date']));

        return response()->json($task, Response::HTTP_OK);
    }

    // Delete a task by ID
    public function destroy(int $id) : JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully'], Response::HTTP_OK);
    }
   
}
