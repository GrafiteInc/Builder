<?php

use App\Repositories\Account\AccountRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AccountRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->repo = $this->app->make(AccountRepository::class);
    }

    public function testUpdate()
    {
        $user = factory(App\Repositories\User\User::class)->create();
        $account = factory(App\Repositories\Account\Account::class)->create([ 'user_id' => $user->id ]);

        $response = $this->repo->update($user->id, ['phone' => '666']);
        $this->assertTrue($response);
        $this->seeInDatabase('accounts', ['phone' => '666']);
    }

    public function testFindByUserId()
    {
        $user = factory(App\Repositories\User\User::class)->create();
        $account = factory(App\Repositories\Account\Account::class)->create([ 'user_id' => $user->id ]);

        $response = $this->repo->findByUserId($user->id);
        $this->assertEquals($account->phone, $response->phone);
    }

}
