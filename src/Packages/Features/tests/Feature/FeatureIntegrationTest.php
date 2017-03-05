<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FeatureIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->feature = factory({{App\}}Models\Feature::class)->make([
            'id' => 1,
            'key' => 'signup'
        ]);
        $this->featureEdited = factory({{App\}}Models\Feature::class)->make([
            'id' => 1,
            'key' => 'register',
        ]);

        $role = factory({{App\}}Models\Role::class)->create();
        $user = factory({{App\}}Models\User::class)->create();
        $user->roles()->attach($role);

        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', 'admin/features');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertViewHas('features');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', 'admin/features/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'admin/features', $this->feature->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('admin/features/'.$this->feature->id.'/edit');
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'admin/features', $this->feature->toArray());

        $response = $this->actor->call('GET', 'admin/features/'.$this->feature->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertViewHas('feature');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'admin/features', $this->feature->toArray());
        $response = $this->actor->call('PATCH', 'admin/features/1', $this->featureEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('features', [
            'id' => 1,
            'key' => 'register',
        ]);
        $response->assertRedirect('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'admin/features', $this->feature->toArray());

        $response = $this->call('DELETE', 'admin/features/'.$this->feature->id);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('admin/features');
    }
}
