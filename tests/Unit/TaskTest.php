<?php

namespace Tests\Unit;

// use App\Models\Task;

use App\Models\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{

    // show all tasks 

    public function test_show_all_tasks()
    {
        $response = $this->get('api/tasks');
        $response->assertStatus(200);
    }


    // create task
    
    public function test_create_task()
    {
        $response = $this->post('api/tasks',[
            'title'=>'task2',
            'description'=>'test task',
            'status'=>'completed',
            'due_date'=>'2023-8-2',
        ]);
        $response->assertStatus(200);
    }

    // update task

    public function test_update_task()
    {
        $response = $this->put('api/tasks/2',[
            'title'=>'task555',
            'description'=>'updated task',
            'status'=>'pending',
            'due_date'=>'2023-8-10',
        ]);
        $response->assertStatus(200);
    }


    // delete task

       public function test_delete_task()
    {
        $response = $this->delete('api/tasks/1');
        $response->assertStatus(200);
    }

     // delete one  task

        public function test_show_task()
    {
        $response = $this->get('api/tasks/3');
        $response->assertStatus(200);
    }
 
 

}
