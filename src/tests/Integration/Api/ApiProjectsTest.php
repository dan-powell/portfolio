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


    public function testResponseGetProjects()
    {
        // Setup
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();

        // Actions
        $this->get(route('api.project.index'), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'id' => $project->id,
            'title' => $project->title,
        ]);
    }


    public function testResponseGetProject()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Project::class)->create();

        // Actions
        $this->get(route('api.project.show', $model->id), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'id' => $model->id,
            'title' => $model->title,
        ]);
    }


    public function testResponsePostProject()
    {
        // Setup
        $project = factory(DanPowell\Portfolio\Models\Project::class)->make();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->post(route('api.project.store'), $project->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

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
        $this->put(route('api.project.update', $project->id), $newProject->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

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
        $this->delete(route('api.project.destroy', $project->id), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->notSeeInDatabase('projects', ['id' => $project->id]);
    }


    // Test auth
    public function testResponseNoAuthProject()
    {
        // Setup
        $persistentModel = factory(DanPowell\Portfolio\Models\Project::class)->create();
        $model = factory(DanPowell\Portfolio\Models\Project::class)->make();

        // Actions
        $this->post(route('api.project.store'), $model->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseStatus('401');
        $this->missingFromDatabase('projects', ['title' => $model->title, 'markup' => $model->markup]); // Make sure data has not been posted

        // Actions
        $this->put(route('api.project.update', $persistentModel->id), $model->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);
        $this->seeInDatabase('projects', ['title' => $persistentModel->title, 'markup' => $persistentModel->markup]); // Make sure data has not been updated
        $this->missingFromDatabase('projects', ['title' => $model->title, 'markup' => $model->markup]); // Make sure data has not been posted

        // Assertions
        $this->assertResponseStatus('401');

        // Actions
        $this->delete(route('api.project.destroy', $persistentModel->id), [], ['X-Requested-With' => 'XMLHttpRequest']);
        $this->seeInDatabase('projects', ['title' => $persistentModel->title, 'markup' => $persistentModel->markup]); // Make sure data has not been deleted

        // Assertions
        $this->assertResponseStatus('401');
    }


    // Project Sections

    public function testResponseGetProjectSections()
    {
        // Setup
        $section = factory(DanPowell\Portfolio\Models\Section::class)->make();
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();
        $project->sections()->save($section);

        // Actions
        $this->get(route('api.project.section.index', $project->id), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'markup' => $section->markup,
        ]);
    }


    public function testResponseGetProjectSection()
    {
        // Setup
        $section = factory(DanPowell\Portfolio\Models\Section::class)->make();
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();
        $project->sections()->save($section);

        // Actions
        $this->get(route('api.project.section.show', $project->id, $section->id), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'markup' => $section->markup,
        ]);
    }


    public function testResponsePostProjectSection()
    {
        // Setup
        $section = factory(DanPowell\Portfolio\Models\Section::class)->make();
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->post(route('api.project.section.store', $project->id), $section->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'markup' => $section->markup,
        ]);
    }


    // Test auth
    public function testResponseNoAuthProjectSections()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Section::class)->make();

        // Actions
        $this->post(route('api.project.section.store'), $model->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseStatus('401');
        $this->missingFromDatabase('sections', ['markup' => $model->markup]); // Make sure data has not been posted
    }


    // Project Pages

    public function testResponseGetProjectPages()
    {
        // Setup
        $page = factory(DanPowell\Portfolio\Models\Page::class)->make();
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();
        $project->pages()->save($page);

        // Actions
        $this->get(route('api.project.page.index', $project->id), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'title' => $page->title,
        ]);
    }


    public function testResponseGetProjectPage()
    {
        // Setup
        $page = factory(DanPowell\Portfolio\Models\Page::class)->make();
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();
        $project->pages()->save($page);

        // Actions
        $this->get(route('api.project.page.show', $project->id, $page->id), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'title' => $page->title,
        ]);
    }


    public function testResponsePostProjectPage()
    {
        // Setup
        $page = factory(DanPowell\Portfolio\Models\Page::class)->make();
        $project = factory(DanPowell\Portfolio\Models\Project::class)->create();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->post(route('api.project.page.store', $project->id), $page->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'title' => $page->title,
        ]);
    }

    // Test auth
    public function testResponseNoAuthProjectPage()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Page::class)->make();

        // Actions
        $this->post(route('api.project.page.store'), $model->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseStatus('401');
        $this->missingFromDatabase('pages', ['markup' => $model->markup, 'title' => $model->title]); // Make sure data has not been posted
    }


}
