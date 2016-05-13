<?php

use {{App\}}Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserServiceTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $service;

    public function setUp()
    {
        parent::setUp();
        $this->service = $this->app->make(UserService::class);
    }

    public function testGetUsers()
    {
        $response = $this->service->all();
        $this->assertEquals(get_class($response), 'Illuminate\Database\Eloquent\Collection');
    }

    public function testGetUser()
    {
        $user = factory({{App\}}Repositories\User\User::class)->create();
        factory({{App\}}Repositories\UserMeta\UserMeta::class)->create([ 'user_id' => $user->id ]);
        $response = $this->service->find($user->id);

        $this->assertTrue(is_object($response));
        $this->assertEquals($user->name, $response->name);
    }

    public function testCreateUserMeta()
    {
        $role = factory(App\Repositories\Role\Role::class)->create();
        $user = factory({{App\}}Repositories\User\User::class)->create();
        $response = $this->service->create($user, 'password');

        $this->assertTrue(is_object($response));
        $this->assertEquals($user->name, $response->name);
    }

    public function testUpdateUserMeta()
    {
        $user = factory({{App\}}Repositories\User\User::class)->create();

        $response = $this->service->update($user->id, [
            'email' => $user->email,
            'name' => 'jim',
            'role' => 'member',
            'meta' => [
                'phone' => '666',
                'marketing' => 1,
                'terms_and_cond' => 1
            ]
        ]);

        $this->assertTrue($response);
        $this->seeInDatabase('user_meta', ['phone' => '666']);
        $this->seeInDatabase('users', ['name' => 'jim']);
    }

}
