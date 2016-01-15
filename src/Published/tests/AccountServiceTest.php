<?php

use App\Services\AccountService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AccountServiceTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->service = $this->app->make(AccountService::class);
    }

    public function testGetAccounts()
    {
        $response = $this->service->allAccounts();
        $this->assertEquals(get_class($response), 'Illuminate\Database\Eloquent\Collection');
    }

    public function testGetAccount()
    {
        $user = factory(App\Repositories\User\User::class)->create();
        factory(App\Repositories\Account\Account::class)->create([ 'user_id' => $user->id ]);
        $response = $this->service->getAccount($user->id);

        $this->assertTrue(is_object($response));
        $this->assertEquals($user->name, $response->name);
    }

    public function testCreateAccount()
    {
        $user = factory(App\Repositories\User\User::class)->create();
        $response = $this->service->create($user, 'password');

        $this->assertTrue(is_object($response));
        $this->assertEquals($user->name, $response->name);
    }

    public function testUpdateAccount()
    {
        $user = factory(App\Repositories\User\User::class)->create();

        $response = $this->service->update($user->id, [
            'email' => $user->email,
            'name' => 'jim',
            'role' => 'member',
            'account' => [
                'phone' => '666',
                'marketing' => 1
            ]
        ]);

        $this->assertTrue($response);
        $this->seeInDatabase('accounts', ['phone' => '666']);
        $this->seeInDatabase('users', ['name' => 'jim']);
    }

}
