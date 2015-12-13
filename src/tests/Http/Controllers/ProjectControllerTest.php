<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use DanPowell\Portfolio\Http\Controllers\ProjectController;

class ProjectControllerTest extends TestCase
{

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

    public function testIndexRouteResponse()
    {
        $this->visit(config('portfolio.routes.public.index'));

        $this->assertResponseOk();
    }

    public function testIndexData()
    {
        $this->visit(config('portfolio.routes.public.index'))
            ->assertViewHasAll(['projects', 'tags']);
    }

    public function testProjectsShouldHaveTagsString()
    {
        $response = $this->call('GET', config('portfolio.routes.public.index'));

        $projects = $response->original['projects'];

        $this->assertInternalType('string', $projects[0]->allTags);
    }






}
