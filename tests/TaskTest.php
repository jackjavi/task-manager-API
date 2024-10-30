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

     // Test fetching all tasks with pagination
    public function testGetAllTasksWithPagination()
    {
        Task::factory()->count(15)->create();

        $this->get('/api/tasks?page=1')
             ->seeStatusCode(200)
             ->seeJsonStructure([
                 '*' => ['id', 'title', 'description', 'status', 'due_date', 'created_at', 'updated_at'],
             ]);

        $response = $this->response->getContent();
        $tasks = json_decode($response, true);

        $this->assertCount(10, $tasks);

        $this->get('/api/tasks?page=2')
             ->seeStatusCode(200);

        $response = $this->response->getContent();
        $tasks = json_decode($response, true);

        $this->assertCount(5, $tasks);
    }

    // Test filtering tasks by status
    public function testGetTasksFilteredByStatus()
    {
        Task::factory()->count(5)->create(['status' => 'pending']);
        Task::factory()->count(3)->create(['status' => 'completed']);

        $this->get('/api/tasks?status=pending')
             ->seeStatusCode(200);

        $response = $this->response->getContent();
        $tasks = json_decode($response, true);

        $this->assertCount(5, $tasks); 
    }

    // Test filtering tasks by due date
    public function testGetTasksFilteredByDueDate()
    {
        $dueDate = Carbon::now()->addDays(7)->toDateString();
        Task::factory()->count(4)->create(['due_date' => $dueDate]);
        Task::factory()->create(['due_date' => Carbon::now()->addDays(3)->toDateString()]);

        $this->get("/api/tasks?due_date={$dueDate}")
             ->seeStatusCode(200);

        $response = $this->response->getContent();
        $tasks = json_decode($response, true);

        $this->assertCount(4, $tasks); 
    }

    // Test searching tasks by title
    public function testSearchTasksByTitle()
    {
        Task::factory()->create(['title' => 'Test Task Alpha']);
        Task::factory()->create(['title' => 'Alpha Task']);
        Task::factory()->create(['title' => 'Beta Task']);

        $this->get('/api/tasks?title=Alpha')
             ->seeStatusCode(200);

        $response = $this->response->getContent();
        $tasks = json_decode($response, true);

        $this->assertCount(2, $tasks); 
    }

}
