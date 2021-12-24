<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class TasksTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_add_task()
    {

        Storage::fake('tasks');
        $user= User::factory()->create();
        $this->actingAs($user)
            ->post('api/tasks', [
                'title' => 'title',
                'file' => UploadedFile::fake()->create('sample1.json')
            ])->assertStatus(200);

    }
    public function tests_user_can_update_task()
    {
        $user= User::factory()->create();
        $task = Task::factory()->create();
         $this->actingAs($user)->putJson("api/tasks/{$task->id}", ['title' => 'test'])
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'test']);
    }

    public function tests_user_can_update_task_2()
    {
        $user= User::factory()->create();
        $task = Task::factory()->create();

        $this->actingAs($user)->postJson("api/updatetask/{$task->id}", ['title' => 'test'])
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'test']);

    }

    public function test_user_can_show_all_task()
    {
        $user= User::factory()->create();
        $task = Task::factory()->create();

        $this->actingAs($user)
            ->get('api/tasks/')
            ->assertSee($task->title);
    }
    public function test_user_can_show_task()
    {
        $user= User::factory()->create();
        $task = Task::factory()->create();

        $this->actingAs($user)
            ->get('api/tasks/'.$task->id)
            ->assertSee($task->title);
    }


    public function test_user_can_delete_task()
    {

        $user= User::factory()->create();
        $task = Task::factory()->create();
        $this->actingAs($user)->delete('api/tasks/'.$task->id)->assertStatus(500);

    }
    


}
