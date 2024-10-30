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

    // Test fetching all tasks
    public function testGetAllTasks()
    {
        Task::factory()->count(5)->create();

        $this->get('/api/tasks')
             ->seeStatusCode(200)
             ->seeJsonStructure([
                 '*' => ['id', 'title', 'description', 'status', 'due_date', 'created_at', 'updated_at'],
             ]);

        $response = $this->response->getContent();
        $tasks = json_decode($response, true);

        $this->assertCount(5, $tasks);
    }

    // Test updating a task with partial data
    public function testUpdateTaskWithPartialData()
    {
        $task = Task::factory()->create();

        $updateData = ['description' => 'Updated description'];
        $this->put("/api/tasks/{$task->id}", $updateData)
             ->seeStatusCode(200)
             ->seeJson(['description' => 'Updated description'])
             ->seeInDatabase('tasks', ['id' => $task->id, 'description' => 'Updated description']);
    }

    // Test deleting a non-existing task
    public function testDeleteNonExistingTask()
    {
        $this->delete('/api/tasks/9999')
             ->seeStatusCode(404)
             ->seeJson(['error' => 'Task not found']);
    }

}
