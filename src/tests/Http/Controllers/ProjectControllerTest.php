<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use DanPowell\Portfolio\Http\Controllers\ProjectController;

class ProjectControllerTest extends TestCase
{

    private $controller;
    private $projectRepository;

    public function setUp()
    {

        $this->projectRepository = $this->getMock(
            'DanPowell\Portfolio\Repositories\ProjectRepository',
            array(
                'getAllProjects',
                'addAllTagstoCollection',
                'getAllTags',
                'filterProjectTagsWithRelationship'
            )
        );

        $this->controller = new ProjectController($this->projectRepository);

        parent::setUp();
    }


    public function testIndexMethodReturn()
    {
        $result = $this->controller->index();
        $this->assertInstanceOf('Illuminate\View\View', $result);
    }

    public function testIndexMethods()
    {
        $this->projectRepository->expects($this->once())
            ->method('getAllProjects');

        $this->projectRepository->expects($this->once())
            ->method('getAllTags');

        $this->projectRepository->expects($this->once())
            ->method('addAllTagstoCollection');

        $this->projectRepository->expects($this->once())
            ->method('filterProjectTagsWithRelationship');

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
