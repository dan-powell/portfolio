<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectsTest extends TestCase
{

/*
    public function setUp()
    {
        parent::setUp();
    }
*/

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
