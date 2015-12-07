<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use DanPowell\Portfolio\Http\Controllers\ProjectController;

class ProjectControllerTest extends TestCase
{


    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testArseFunctionShouldReturnButts()
    {

        $controller = new ProjectController();

        $result = $controller->arse();

        $this->assertEquals('butts', $result);

    }


    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testIndexShouldDisplay()
    {
        $this->visit('portfolio')
            ->see('Portfolio Index')
            ->assertViewHas('projects');
            ->assertViewHas('tags');
    }


    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testIndexShouldReturnView()
    {
        $controller = new ProjectController();
        $result = $controller->index();
        $this->assertInstanceOf('Illuminate\View\View', $result);
    }

}
