<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TeamIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->team = factory(App\Repositories\Team\Team::class)->make([
            'id' => 1,
            'user_id' => 1,
            'name' => 'Awesomeness'
        ]);
        $this->teamEdited = factory(App\Repositories\Team\Team::class)->make([
            'id' => 1,
            'user_id' => 1,
            'name' => 'Hackers'
        ]);
        $this->user = factory(App\Repositories\User\User::class)->make(['id' => 1]);
        $this->actor = $this->actingAs($this->user);
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
        $admin = factory(App\Repositories\User\User::class)->create($this->user->toArray());
        $response = $this->actingAs($admin)->call('POST', 'teams', $this->team->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('teams/'.$this->team->id.'/edit');
    }

    public function testEdit()
    {
        $admin = factory(App\Repositories\User\User::class)->create($this->user->toArray());
        $this->actingAs($admin)->call('POST', 'teams', $this->team->toArray());

        $response = $this->actingAs($admin)->call('GET', '/teams/'.$this->team->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('team');
    }

    public function testUpdate()
    {
        $admin = factory(App\Repositories\User\User::class)->create($this->user->toArray());
        $this->actingAs($admin)->call('POST', 'teams', $this->team->toArray());

        $response = $this->actingAs($admin)->call('PATCH', '/teams/1', $this->teamEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase('teams', $this->teamEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $admin = factory(App\Repositories\User\User::class)->create($this->user->toArray());
        $this->actingAs($admin)->call('POST', 'teams', $this->team->toArray());

        $response = $this->actingAs($admin)->call('GET', '/teams/'.$this->team->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/teams');
    }

}
