<?php

namespace Tests\Feature;

use DB;
use App\Model\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserInformationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * This function is called before testcase
     */
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        \DB::table('users')->insert([
            'employee_code' => 'AT0123',
            'name' => 'SA Nguyen',
            'email' => 'sa.nguyen@gmail.com',
            'team' => 'SA',
            'role' => '1'
        ]);
    }

    /**
     * Test show user success.
     *
     * @return void
     */
    public function testShowUser()
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/users/1');
        $response->assertJsonStructure([
            "meta" => [
                "message",
                "code"
            ],
            "data" => [
                "id",
                "employee_code",
                "name",
                "email",
                "team",
                "role",
                "total_borrowed",
                "total_donated"
            ]
            
        ])->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test show user fail.
     *
     * @return void
     */
    public function testShowUserFail()
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/users/2');
        $response->assertJsonStructure([
            "meta" => [
                "message",
                "code"
            ],
        ]);
        $response->assertJson([
            "meta" => [
                "code" => Response::HTTP_NOT_FOUND
            ]
        ]);
    }
}
