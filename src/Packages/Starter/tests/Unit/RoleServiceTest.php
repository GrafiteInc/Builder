<?php

use Tests\TestCase;
use {{App\}}Services\RoleService;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RoleServiceTest extends TestCase
{
    use DatabaseMigrations;

    protected $service;
    protected $originalArray;
    protected $editedArray;
    protected $searchTerm;

    public function setUp()
    {
        parent::setUp();
        $this->service = $this->app->make(RoleService::class);
        $this->originalArray = [
            'id' => 1,
            'name' => 'coders',
            'label' => 'Coders',
            'permissions' => ['super' => 'on'],
        ];
        $this->modifiedArray = [
            'id' => 1,
            'name' => 'hackers',
            'label' => 'Hackers',
            'permissions' => [],
        ];
        $this->editedArray = [
            'id' => 1,
            'name' => 'hackers',
            'label' => 'Hackers',
            'permissions' => '',
        ];
        $this->searchTerm = 'who';
    }

    public function testAll()
    {
        $response = $this->service->all();
        $this->assertEquals(get_class($response), 'Illuminate\Database\Eloquent\Collection');
        $this->assertTrue(is_array($response->toArray()));
        $this->assertEquals(0, count($response->toArray()));
    }

    public function testPaginated()
    {
        $response = $this->service->paginated(1);
        $this->assertEquals(get_class($response), 'Illuminate\Pagination\LengthAwarePaginator');
        $this->assertEquals(0, $response->total());
    }

    public function testSearch()
    {
        $response = $this->service->search($this->searchTerm, 25);
        $this->assertEquals(get_class($response), 'Illuminate\Pagination\LengthAwarePaginator');
        $this->assertEquals(0, $response->total());
    }

    public function testCreate()
    {
        $response = $this->service->create($this->originalArray);
        $this->assertEquals(get_class($response), '{{App\}}Models\Role');
        $this->assertEquals(1, $response->id);
    }

    public function testUpdate()
    {
        $role = $this->service->create($this->originalArray);
        $response = $this->service->update($role->id, $this->modifiedArray);

        $this->assertEquals($role->id, $response->id);
        $this->assertDatabaseHas('roles', $this->editedArray);
    }

    public function testDestroy()
    {
        $role = $this->service->create($this->originalArray);
        $response = $this->service->destroy($role->id);
        $this->assertTrue($response);
    }
}
