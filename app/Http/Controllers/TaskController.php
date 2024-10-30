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

   
}
