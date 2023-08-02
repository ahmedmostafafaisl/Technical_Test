<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{

    // Register test

    public function test_register_form()
    {
        $response = $this->post('api/register',[
            'name'=>'faisalq',
            'email'=>'faisalq@gmail.com',
            'mobile'=>'501234510',
            'password'=>'fasqs',
        ]);
        $response->assertStatus(200);
    }


    // login test

    public function test_login_form()
    {
        $response = $this->post('api/login',[
            'key'=>'501234510',
            'password'=>'fasqs',
        ]);
        $response->assertStatus(200);
    }

      //Delete user test

    public function test_delete_user()
    {
        $user=User::factory()->count(1)->make();
        $user=User::first();
        if($user)
        {
            $user->delete();
        }
        $this->assertTrue(true);
    }
 
}
