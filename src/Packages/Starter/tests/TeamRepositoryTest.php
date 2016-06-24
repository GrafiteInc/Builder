<?php

use {{App\}}Repositories\Team\TeamRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TeamRepositoryTest extends TestCase
{
    use DatabaseMigrations;
    
    protected $repo;
    protected $originalArray;
    protected $editedArray;
    protected $searchTerm;


    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(TeamRepository::class);

        $this->originalArray = [
            'user_id' => 1,
            'name' => 'Awesomeness'
        ];
        $this->editedArray = [
            'user_id' => 1,
            'name' => 'Hackers'
        ];
        $this->searchTerm = 'who';
    }

    public function testAll()
    {
        $response = $this->repo->all(1);
        $this->assertEquals(get_class($response), 'Illuminate\Database\Eloquent\Collection');
        $this->assertTrue(is_array($response->toArray()));
        $this->assertEquals(0, count($response->toArray()));
    }

    public function testPaginated()
    {
        $response = $this->repo->paginated(1, 25);
        $this->assertEquals(get_class($response), 'Illuminate\Pagination\LengthAwarePaginator');
        $this->assertEquals(0, $response->total());
    }

    public function testSearch()
    {
        $response = $this->repo->search($this->searchTerm, 1, 25);
        $this->assertEquals(get_class($response), 'Illuminate\Pagination\LengthAwarePaginator');
        $this->assertEquals(0, $response->total());
    }

    public function testCreate()
    {
        $response = $this->repo->create($this->originalArray);
        $this->assertEquals(get_class($response), 'App\Repositories\Team\Team');
        $this->assertEquals(1, $response->id);
    }

    public function testFind()
    {
        $item = $this->repo->create($this->originalArray);

        $response = $this->repo->find($item->id);
        $this->assertEquals(1, $response->id);
    }

    public function testUpdate()
    {
        // create the item
        $item = $this->repo->create($this->originalArray);

        $response = $this->repo->update($item->id, $this->editedArray);

        $this->assertEquals(1, $response->id);
        $this->seeInDatabase('teams', $this->editedArray);
    }

    public function testDestroy()
    {
        // create the item
        $item = $this->repo->create($this->originalArray);

        $response = $this->repo->destroy($item->id);
        $this->assertTrue($response);
    }

}

