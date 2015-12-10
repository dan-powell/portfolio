<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use DanPowell\Portfolio\Repositories\ProjectRepository;

use DanPowell\Portfolio\Models\Project;

class ProjectRepositoryTest extends TestCase
{

    private $repository;

    public function setUp()
    {
        $this->repository = new ProjectRepository();
        parent::setUp();
    }

    /**
     * @param string $varOne String to be sluggified
     * @param string $varTwo What we expect our slug result to be
     * @param string $varThree What we expect our slug result to be
     *
     * @dataProvider providerGetAllThings
     */
    public function testMethodGetAllThings($with, $order, $by)
    {
        $result = $this->repository->getAllThings($with, $order, $by);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $result);
    }


    public function testMethodGetAllProjects()
    {
        $result = $this->repository->getAllProjects();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $result);
    }


    public function testMethodGetAllTags()
    {
        $result = $this->repository->getAllTags();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $result);
    }



    public function providerGetAllThings()
    {
        return array(
            array(new project, 'tags', 'created_at', 'desc'),
            array(new project, [], null, ''),
        );
    }


}
