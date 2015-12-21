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


    // Test that Index route returns valid response and data is present
    public function testResponseIndex()
    {
        // Setup

        // Actions
        $this->visit(route('projects.index'));

        // Assertions
        $this->assertResponseOk();
        $this->assertViewHasAll(['projects', 'tags']);
    }

    // Test projects have a string of tags
    public function testProjectsShouldHaveTagsString()
    {
        // Setup
        factory(DanPowell\Portfolio\Models\Project::class)->create();

        // Actions
        $response = $this->call('GET', route('projects.index'));

        // Assertions
        $projects = $response->original['projects'];
        $this->assertInternalType('string', $projects[0]->allTags);
    }

    // Test that Show route returns valid response and data is present
    public function testResponseShow()
    {
        // Setup
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();

        // Actions
        $this->visit(route('projects.show', $project->slug));

        // Assertions
        $this->assertResponseOk();
        $this->assertViewHasAll(['project']);
    }


    // Test that Page route returns valid response and data is present
    public function testResponsePage()
    {
        // Setup
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();

        $page = factory(DanPowell\Portfolio\Models\Page::class)->create([
            'attachment_id' => $project->id,
            'attachment_type' => 'DanPowell\Portfolio\Models\Project'
        ]);

        // Actions
        $this->visit(route('projects.page', [$project->slug, $page->slug]));

        // Assertions
        $this->assertResponseOk();
        $this->assertViewHasAll(['page']);
    }


    // Test that Admin route returns valid response
    public function testResponseAdmin()
    {
        // Setup
        $user = factory(App\User::class)->create();
        $this->actingAs($user);

        // Actions
        $this->visit(route('admin'));

        // Assertions
        $this->assertResponseOk();
    }

}
