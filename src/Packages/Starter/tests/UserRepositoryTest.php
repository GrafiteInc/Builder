<?php

use {{App\}}Repositories\User\UserRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $repo;

    public function setUp()
    {
        parent::setUp();

        $this->repo = $this->app->make(UserRepository::class);
    }

    public function testUpdate()
    {
        $user = factory({{App\}}Repositories\User\User::class)->create();
        $response = $this->repo->update($user->id, ['name' => 'sammy davis jr']);
        $this->assertTrue($response);
        $this->seeInDatabase('users', ['name' => 'sammy davis jr']);
    }

    public function testAssignRole()
    {
        $role = factory({{App\}}Repositories\Role\Role::class)->create();
        $user = factory({{App\}}Repositories\User\User::class)->create();
        $this->repo->assignRole('member', $user->id);
        $this->seeInDatabase('role_user', ['role_id' => $role->id, 'user_id' => $user->id]);
        $this->assertEquals($user->roles->first()->label, 'Member');
    }

    public function testUnassignRole()
    {
        $role = factory({{App\}}Repositories\Role\Role::class)->create();
        $user = factory({{App\}}Repositories\User\User::class)->create();
        $this->repo->assignRole('member', $user->id);
        $this->repo->unassignRole('member', $user->id);
        $this->assertEquals(0, count($user->roles));
    }

    public function testUnassignAllRole()
    {
        $role = factory({{App\}}Repositories\Role\Role::class)->create();
        $user = factory({{App\}}Repositories\User\User::class)->create();
        $this->repo->assignRole('member', $user->id);
        $this->repo->unassignAllRoles($user->id);
        $this->assertEquals(0, count($user->roles));
    }

    public function testJoinTeam()
    {
        $team = factory({{App\}}Repositories\Team\Team::class)->create();
        $user = factory({{App\}}Repositories\User\User::class)->create();
        $this->repo->joinTeam($team->id, $user->id);
        $this->seeInDatabase('team_user', ['team_id' => $team->id, 'user_id' => $user->id]);
        $this->assertEquals($user->teams->first()->name, $team->name);
    }

    public function testLeaveTeam()
    {
        $team = factory({{App\}}Repositories\Team\Team::class)->create();
        $user = factory({{App\}}Repositories\User\User::class)->create();
        $this->repo->joinTeam($team->id, $user->id);
        $this->repo->leaveTeam($team->id, $user->id);
        $this->assertEquals(0, count($user->teams));
    }

}
