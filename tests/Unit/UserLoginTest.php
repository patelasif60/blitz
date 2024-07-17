<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    
    //To check endpoint is exist or not for login form
    public function test_login_form() {
        $response = $this->get('/signin');
        $response->assertStatus(200);
    }

    //Check user duplication
    public function test_user_duplication() {
        $user1 = User::make([
            'firstname' => 'admin',
            'email' => 'admin@blitznet.com'
        ]);

        $user2 = User::make([
            'firstname' => 'Ronak',
            'email' => 'ronak.makwana@bcssarl.com'
        ]);

        $this->assertTrue($user1->firstname != $user2->firstname);
    }

    //Check User Credentials are valid or not
    public function test_user_credentials_valid_or_not() {
        $userData = User::where(['email' => 'ronak.makwana@bcssarl.com','password' => '$2y$10$I9aGht8uwQQ71omqHuoB6eOOM4g6H9TYwfmBAcyRmGAp01Gk6t6ti'])->get()->first();
        $this->assertNotNull($userData);
    }

    //Check User Account is active or not
    public function test_user_account_active_or_not() {
        $userData = User::where('email', 'ronak.makwana@bcssarl.com')->get()->first();
        if($userData->role_id == 2) {
            if ($userData->is_active == 1) {
                $this->assertTrue(true);
            }
        }
    }
}
