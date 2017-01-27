<?php

use Tests\TestCase;
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
        $user = factory({{App\}}Models\User::class)->create();
        factory({{App\}}Models\UserMeta::class)->create(['user_id' => $user->id]);
        $response = $this->service->find($user->id);

        $this->assertTrue(is_object($response));
        $this->assertEquals($user->name, $response->name);
    }

    public function testCreateUser()
    {
        $role = factory({{App\}}Models\Role::class)->create();
        $user = factory({{App\}}Models\User::class)->create();
        $response = $this->service->create($user, 'password');

        $this->assertTrue(is_object($response));
        $this->assertEquals($user->name, $response->name);
    }

    public function testUpdateUser()
    {
        $user = factory({{App\}}Models\User::class)->create();
        factory({{App\}}Models\UserMeta::class)->create(['user_id' => $user->id]);

        $response = $this->service->update($user->id, [
            'email' => $user->email,
            'name' => 'jim',
            'role' => 'member',
            'meta' => [
                'phone' => '666',
                'marketing' => 1,
                'terms_and_cond' => 1,
            ],
        ]);

        $this->assertDatabaseHas('user_meta', ['phone' => '666']);
        $this->assertDatabaseHas('users', ['name' => 'jim']);
    }

    public function testAssignRole()
    {
        $role = factory({{App\}}Models\Role::class)->create();
        $user = factory({{App\}}Models\User::class)->create();
        $this->service->assignRole('member', $user->id);
        $this->assertDatabaseHas('role_user', ['role_id' => $role->id, 'user_id' => $user->id]);
        $this->assertEquals($user->roles->first()->label, 'Member');
    }

    public function testHasRole()
    {
        $role = factory({{App\}}Models\Role::class)->create();
        $user = factory({{App\}}Models\User::class)->create();
        $this->service->assignRole('member', $user->id);
        $this->assertDatabaseHas('role_user', ['role_id' => $role->id, 'user_id' => $user->id]);
        $this->assertTrue($user->hasRole('member'));
    }

    public function testUnassignRole()
    {
        $role = factory({{App\}}Models\Role::class)->create();
        $user = factory({{App\}}Models\User::class)->create();
        $this->service->assignRole('member', $user->id);
        $this->service->unassignRole('member', $user->id);
        $this->assertEquals(0, count($user->roles));
    }

    public function testUnassignAllRole()
    {
        $role = factory({{App\}}Models\Role::class)->create();
        $user = factory({{App\}}Models\User::class)->create();
        $this->service->assignRole('member', $user->id);
        $this->service->unassignAllRoles($user->id);
        $this->assertEquals(0, count($user->roles));
    }

    public function testJoinTeam()
    {
        $team = factory({{App\}}Models\Team::class)->create();
        $user = factory({{App\}}Models\User::class)->create();
        $this->service->joinTeam($team->id, $user->id);
        $this->assertDatabaseHas('team_user', ['team_id' => $team->id, 'user_id' => $user->id]);
        $this->assertEquals($user->teams->first()->name, $team->name);
    }

    public function testLeaveTeam()
    {
        $team = factory({{App\}}Models\Team::class)->create();
        $user = factory({{App\}}Models\User::class)->create();
        $this->service->joinTeam($team->id, $user->id);
        $this->service->leaveTeam($team->id, $user->id);
        $this->assertEquals(0, count($user->teams));
    }
}
