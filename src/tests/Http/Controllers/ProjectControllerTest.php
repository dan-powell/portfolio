<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use DanPowell\Portfolio\Http\Controllers\ProjectController;

class ProjectControllerTest extends TestCase
{

    public function __construct()
    {
        $this->controller = new ProjectController();
    }


    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testArseFunctionShouldReturnButts()
    {

        $result = $this->controller->arse();

        $this->assertEquals('butts', $result);

    }

    public function testIndexShouldReturnView()
    {
        $result = $this->controller->index();
        $this->assertInstanceOf('Illuminate\View\View', $result);
    }

    public function testIndexView()
    {
        $this->visit('portfolio');

        $this->assertResponseOk();
    }

    public function testIndexShouldHaveProjectsCollection()
    {
        $this->visit('portfolio')
            ->assertViewHas('projects');
    }

    public function testIndexShouldHaveTagsCollection()
    {
        $this->visit('portfolio')
            ->assertViewHas('tags');
    }

    public function testProjectsShouldHaveTagsString()
    {
        $response = $this->call('GET', 'portfolio');

        $projects = $response->original['projects'];

        $this->assertInternalType('string', $projects[0]->allTags);
    }

}
