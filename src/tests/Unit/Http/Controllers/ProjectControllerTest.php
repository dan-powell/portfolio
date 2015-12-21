<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use DanPowell\Portfolio\Http\Controllers\ProjectController;

class ProjectControllerTest extends TestCase
{

    use DatabaseMigrations;

    private $controller;
    private $repository;

    public function setUp()
    {

        $this->repository = $this->getMock(
            'DanPowell\Portfolio\Repositories\ModelRepository',
            array(
                'getAll',
                'addAllTagstoCollection',
                'filterOnlyWithRelationship'
            )
        );

        $this->controller = new ProjectController($this->repository);

        parent::setUp();
    }


    public function testIndexMethodReturn()
    {
        $result = $this->controller->index();
        $this->assertInstanceOf('Illuminate\View\View', $result);
    }


    public function testIndexMethods()
    {
        $this->repository->expects($this->exactly(2))
            ->method('getAll');

        $this->repository->expects($this->once())
            ->method('addAllTagstoCollection');

        $this->repository->expects($this->once())
            ->method('filterOnlyWithRelationship');

        $this->controller->index();
    }

}
