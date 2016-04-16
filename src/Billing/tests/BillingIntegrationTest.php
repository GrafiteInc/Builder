<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class BillingIntegrationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory({{App\}}Repositories\User\User::class)->create([ 'id' => rand(1000, 9999) ]);
        $this->user->meta = factory({{App\}}Repositories\UserMeta\UserMeta::class)->create([ 'id' => $this->user->id ]);
        $this->role = factory({{App\}}Repositories\Role\Role::class)->create(['name' => 'admin']);
        $this->user->roles()->attach($this->role);
        $this->actor = $this->actingAs($this->user);
        $subscription = Mockery::mock('subscription')->shouldReceive('upcomingInvoice')->withAnyArgs()->andReturn([])->getMock();
        $this->user->meta->subscription = $subscription;
        Config::set('minify.config.ignore_environments', ['local', 'testing']);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/user/billing/details');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('user');
    }

    public function testChangeCard()
    {
        $response = $this->actor->call('GET', '/user/billing/change-card');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('user');
    }

    public function testCoupon()
    {
        $response = $this->actor->call('GET', '/user/billing/coupon');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('user');
    }
}
