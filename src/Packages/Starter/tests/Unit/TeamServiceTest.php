<?php

use Tests\TestCase;
use {{App\}}Services\TeamService;
use {{App\}}Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TeamServiceTest extends TestCase
{
    use DatabaseMigrations;

    protected $service;
    protected $userService;
    protected $originalArray;
    protected $editedArray;

    public function setUp()
    {
        parent::setUp();
        $this->service = $this->app->make(TeamService::class);
        $this->userService = $this->app->make(UserService::class);

        $this->originalArray = [
            'user_id' => 1,
            'name' => 'Awesomeness',
        ];
        $this->editedArray = [
            'user_id' => 1,
            'name' => 'Hackers',
        ];
        $this->searchTerm = 'who';
    }

    public function testAll()
    {
        $user = factory({{App\}}Models\User::class)->create();
        $this->userService->joinTeam($user->id, 1);
        $response = $this->service->all($user->id);
        $this->assertEquals(get_class($response), 'Illuminate\Database\Eloquent\Collection');
        $this->assertTrue(is_array($response->toArray()));
        $this->assertEquals(0, count($response->toArray()));
    }

    public function testPaginated()
    {
        $user = factory({{App\}}Models\User::class)->create();
        $this->userService->joinTeam($user->id, 1);
        $response = $this->service->paginated(1, 25);
        $this->assertEquals(get_class($response), 'Illuminate\Pagination\LengthAwarePaginator');
        $this->assertEquals(0, $response->total());
    }

    public function testSearch()
    {
        $user = factory({{App\}}Models\User::class)->create();
        $this->userService->joinTeam($user->id, 1);
        $response = $this->service->search(1, $this->searchTerm, 25);
        $this->assertEquals(get_class($response), 'Illuminate\Pagination\LengthAwarePaginator');
        $this->assertEquals(0, $response->total());
    }

    public function testCreate()
    {
        $user = factory({{App\}}Models\User::class)->create();
        $response = $this->service->create($user->id, $this->originalArray);
        $this->assertEquals(get_class($response), '{{App\}}Models\Team');
        $this->assertEquals(1, $response->id);
    }

    public function testInvite()
    {
        $admin = factory({{App\}}Models\User::class)->create();
        $team = $this->service->create($admin->id, $this->originalArray);
        $user = factory({{App\}}Models\User::class)->create();
        $response = $this->service->invite($admin, $team->id, $user->email);
        $this->assertTrue($response);
    }

    public function testRemove()
    {
        $admin = factory({{App\}}Models\User::class)->create();
        $team = $this->service->create($admin->id, $this->originalArray);
        $user = factory({{App\}}Models\User::class)->create();
        $response = $this->service->remove($admin, $team->id, $user->id);
        $this->assertTrue($response);
    }

    public function testFind()
    {
        $admin = factory({{App\}}Models\User::class)->create();
        $team = $this->service->create($admin->id, $this->originalArray);

        $response = $this->service->find($team->id);
        $this->assertEquals($team->id, $response->id);
    }

    public function testUpdate()
    {
        $admin = factory({{App\}}Models\User::class)->create();
        $team = $this->service->create($admin->id, $this->originalArray);

        $response = $this->service->update($team->id, $this->editedArray);

        $this->assertEquals($team->id, $response->id);
        $this->assertDatabaseHas('teams', $this->editedArray);
    }

    public function testDestroy()
    {
        $admin = factory({{App\}}Models\User::class)->create();
        $team = $this->service->create($admin->id, $this->originalArray);

        $response = $this->service->destroy($admin, $team->id);
        $this->assertTrue($response);
    }
}
