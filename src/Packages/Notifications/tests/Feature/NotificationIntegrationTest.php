<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NotificationIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->notification = factory({{App\}}Models\Notification::class)->make([
            'id' => 1,
            'user_id' => 1,
            'flag' => 'info',
            'uuid' => 'lksjdflaskhdf',
            'title' => 'Testing',
            'details' => 'Your car has been impounded!',
            'is_read' => 0,
        ]);
        $this->notificationEdited = factory({{App\}}Models\Notification::class)->make([
            'id' => 1,
            'user_id' => 1,
            'flag' => 'info',
            'uuid' => 'lksjdflaskhdf',
            'title' => 'Testing',
            'details' => 'Your car has been impounded!',
            'is_read' => 1,
        ]);

        $role = factory({{App\}}Models\Role::class)->create();
        $user = factory({{App\}}Models\User::class)->create();
        $user->roles()->attach($role);

        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', 'admin/notifications');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertViewHas('notifications');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', 'admin/notifications/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'admin/notifications', $this->notification->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('admin/notifications/'.$this->notification->id.'/edit');
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'admin/notifications', $this->notification->toArray());

        $response = $this->actor->call('GET', 'admin/notifications/'.$this->notification->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertViewHas('notification');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'admin/notifications', $this->notification->toArray());
        $response = $this->actor->call('PATCH', 'admin/notifications/1', $this->notificationEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('notifications', $this->notificationEdited->toArray());
        $response->assertRedirect('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'admin/notifications', $this->notification->toArray());

        $response = $this->call('DELETE', 'admin/notifications/'.$this->notification->id);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('admin/notifications');
    }
}
