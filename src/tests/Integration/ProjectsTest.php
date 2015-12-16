<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectsTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
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
        factory(DanPowell\Portfolio\Models\Project::class)->create();

        $response = $this->call('GET', config('portfolio.routes.public.index'));

        $projects = $response->original['projects'];

        $this->assertInternalType('string', $projects[0]->allTags);
    }





    public function testShowRouteResponse()
    {
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();

        $this->visit(config('portfolio.routes.public.show') . '/' . $project->slug);

        $this->assertResponseOk();
    }


    public function testShowData()
    {
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();

        $this->visit(config('portfolio.routes.public.show') . '/' . $project->slug)
            ->assertViewHasAll(['project']);
    }



}
