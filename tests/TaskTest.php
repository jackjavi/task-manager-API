<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models\Task;
use Illuminate\Support\Carbon;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    // Test validation for creating a task
    public function testCreateTaskValidation()
    {
        $invalidData = [
            'title' => '', 
            'due_date' => Carbon::now()->subDay()->toDateString(),
        ];

        $this->post('/api/tasks', $invalidData)
             ->seeStatusCode(400)
             ->seeJsonStructure(['errors' => ['title', 'due_date']]);
    }

    // Test creating a task with valid data
    public function testCreateValidTask()
    {
        $validData = [
            'title' => 'Valid Task',
            'description' => 'A valid task description',
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(2)->toDateString(),
        ];

        $this->post('/api/tasks', $validData)
             ->seeStatusCode(201)
             ->seeJson(['title' => 'Valid Task'])
             ->seeInDatabase('tasks', ['title' => 'Valid Task']);
    }

}
