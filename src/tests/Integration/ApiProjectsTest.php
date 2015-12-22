<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;



class ApiProjectsTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
    }


    public function testResponseGetProject()
    {
        // Setup
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();

        // Actions
        $this->get(route('api.project.index'));

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'id' => $project->id,
            'title' => $project->title,
        ]);
    }


    public function testResponsePostProject()
    {
        // Setup
        $project = factory(DanPowell\Portfolio\Models\Project::class)->make();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->post(route('api.project.store'), $project->toArray());

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'title' => $project->title,
        ]);
    }


    public function testResponsePutProject()
    {
        // Setup
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();
        $newProject = factory(DanPowell\Portfolio\Models\Project::class)->make();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->put(route('api.project.update', $project->id), $newProject->toArray());

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'title' => $newProject->title,
        ]);

    }


    public function testResponseDeleteProject()
    {
        // Setup
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->delete(route('api.project.destroy', $project->id));

        // Assertions
        $this->assertResponseOk();
        $this->notSeeInDatabase('projects', ['id' => $project->id]);
    }

}
