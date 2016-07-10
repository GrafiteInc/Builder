<?php

use {{App\}}Repositories\UserMeta\UserMetaRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserMetaRepositoryTest extends TestCase
{
    use DatabaseMigrations;
    
     protected $repo;

    public function setUp()
    {
        parent::setUp();

        $this->repo = $this->app->make(UserMetaRepository::class);
    }

    public function testUpdate()
    {
        $user = factory({{App\}}Repositories\User\User::class)->create();
        $account = factory({{App\}}Repositories\UserMeta\UserMeta::class)->create([ 'user_id' => $user->id ]);

        $response = $this->repo->update($user->id, ['phone' => '666']);
        $this->assertTrue($response);
        $this->seeInDatabase('user_meta', ['phone' => '666']);
    }

    public function testFindByUserId()
    {
        $user = factory({{App\}}Repositories\User\User::class)->create();
        $account = factory({{App\}}Repositories\UserMeta\UserMeta::class)->create([ 'user_id' => $user->id ]);

        $response = $this->repo->findByUserId($user->id);
        $this->assertEquals($account->phone, $response->phone);
    }

}
