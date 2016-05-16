<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TeamIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    protected $user;
    protected $role;
    protected $team;
    protected $teamEdited;
    protected $actor;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory({{App\}}Repositories\User\User::class)->create([ 'id' => rand(1000, 9999) ]);
        $this->role = factory({{App\}}Repositories\Role\Role::class)->create(['name' => 'admin']);

        $this->team = factory({{App\}}Repositories\Team\Team::class)->make([
            'id' => 1,
            'user_id' => $this->user->id,
            'name' => 'Awesomeness'
        ]);
        $this->teamEdited = factory({{App\}}Repositories\Team\Team::class)->make([
            'id' => 1,
            'user_id' => $this->user->id,
            'name' => 'Hackers'
        ]);

        $this->user->roles()->attach($this->role);
        $this->actor = $this->actingAs($this->user);
        Config::set('minify.config.ignore_environments', ['local', 'testing']);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/teams');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('teams');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/teams/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $admin = factory({{App\}}Repositories\User\User::class)->create([ 'id' => rand(1000, 9999) ]);
        $response = $this->actingAs($admin)->call('POST', 'teams', $this->team->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('teams/'.$this->team->id.'/edit');
    }

    public function testEdit()
    {
        $admin = factory({{App\}}Repositories\User\User::class)->create([ 'id' => rand(1000, 9999) ]);
        $admin->roles()->attach($this->role);
        $this->actingAs($admin)->call('POST', 'teams', $this->team->toArray());

        $response = $this->actingAs($admin)->call('GET', '/teams/'.$this->team->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('team');
    }

    public function testUpdate()
    {
        $admin = factory({{App\}}Repositories\User\User::class)->create([ 'id' => rand(1000, 9999) ]);
        $admin->roles()->attach($this->role);
        $this->actingAs($admin)->call('POST', 'teams', $this->team->toArray());

        $response = $this->actingAs($admin)->call('PATCH', '/teams/1', $this->teamEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase('teams', $this->teamEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $admin = factory({{App\}}Repositories\User\User::class)->create([ 'id' => rand(1000, 9999) ]);
        $team = factory({{App\}}Repositories\Team\Team::class)->create([
            'user_id' => $admin->id,
            'name' => 'Awesomeness'
        ]);
        $admin->roles()->attach($this->role);
        $admin->teams()->attach($team);

        $response = $this->actingAs($admin)->call('DELETE', '/teams/'.$team->id);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/teams');
    }
}
