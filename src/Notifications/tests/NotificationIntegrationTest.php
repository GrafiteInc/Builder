<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NotificationIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->notification = factory(App\Repositories\Notification\Notification::class)->make([
            // put fields here
        ]);
        $this->notificationEdited = factory(App\Repositories\Notification\Notification::class)->make([
            // put fields here
        ]);
        $user = factory(App\Repositories\User\User::class)->make();
        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/notifications');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('notifications');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/notifications/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'notifications', $this->notification->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('notifications/'.$this->notification->id.'/edit');
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'notifications', $this->notification->toArray());

        $response = $this->actor->call('GET', '/notifications/'.$this->notification->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('notification');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'notifications', $this->notification->toArray());
        $response = $this->actor->call('PATCH', '/notifications/1', $this->notificationEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->seeInDatabase('notifications', $this->notificationEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'notifications', $this->notification->toArray());

        $response = $this->call('GET', '/notifications/'.$this->notification->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/notifications');
    }

}
