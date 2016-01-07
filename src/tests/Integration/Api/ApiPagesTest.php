<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class ApiPagesTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
    }


    public function testResponseGetPages()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Page::class)->create();

        // Actions
        $this->get(route('api.page.index'), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'id' => $model->id,
            'title' => $model->title,
        ]);
    }


    public function testResponseGetPage()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Page::class)->create();

        // Actions
        $this->get(route('api.page.show', $model->id), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'id' => $model->id,
            'title' => $model->title,
        ]);
    }


/*
    public function testResponsePostPage()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Page::class)->make();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->post(route('api.page.store'), $model->toArray());

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'title' => $model->title,
        ]);
    }
*/


    public function testResponsePutPage()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Page::class)->create();
        $newModel = factory(DanPowell\Portfolio\Models\Page::class)->make();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->put(route('api.page.update', $model->id), $newModel->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'title' => $newModel->title,
        ]);

    }


    public function testResponseDeletePage()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Page::class)->create();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->delete(route('api.page.destroy', $model->id), [], ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->notSeeInDatabase('pages', ['id' => $model->id]);
    }


    // Test auth
    public function testResponseNoAuthPage()
    {
        // Setup
        $persistentModel = factory(DanPowell\Portfolio\Models\Page::class)->create();
        $model = factory(DanPowell\Portfolio\Models\Page::class)->make();

        // Actions
        $this->post(route('api.page.store'), $model->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseStatus('401');
        $this->missingFromDatabase('pages', ['title' => $model->title, 'markup' => $model->markup]); // Make sure data has not been posted

        // Actions
        $this->put(route('api.page.update', $persistentModel->id), $model->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);
        $this->seeInDatabase('pages', ['title' => $persistentModel->title, 'markup' => $persistentModel->markup]); // Make sure data has not been updated
        $this->missingFromDatabase('pages', ['title' => $model->title, 'markup' => $model->markup]); // Make sure data has not been posted

        // Assertions
        $this->assertResponseStatus('401');

        // Actions
        $this->delete(route('api.page.destroy', $persistentModel->id), [], ['X-Requested-With' => 'XMLHttpRequest']);
        $this->seeInDatabase('pages', ['title' => $persistentModel->title, 'markup' => $persistentModel->markup]); // Make sure data has not been deleted

        // Assertions
        $this->assertResponseStatus('401');
    }


    // Page Sections
    /* ------------------------------------------------- */

    public function testResponseGetPageSections()
    {
        // Setup
        $relation = factory(DanPowell\Portfolio\Models\Section::class)->make();
        $model = factory(DanPowell\Portfolio\Models\Page::class)->create();
        $model->sections()->save($relation);

        // Actions
        $this->get(route('api.page.section.index', $model->id), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'markup' => $relation->markup,
        ]);
    }


    public function testResponseGetPageSection()
    {
        // Setup
        $relation = factory(DanPowell\Portfolio\Models\Section::class)->make();
        $model = factory(DanPowell\Portfolio\Models\Page::class)->create();
        $model->sections()->save($relation);

        // Actions
        $this->get(route('api.page.section.show', $model->id, $relation->id), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'markup' => $relation->markup,
        ]);
    }


    public function testResponsePostPageSection()
    {
        // Setup
        $relation = factory(DanPowell\Portfolio\Models\Section::class)->make();
        $model = factory(DanPowell\Portfolio\Models\Page::class)->create();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->post(route('api.page.section.store', $model->id), $relation->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'markup' => $relation->markup,
        ]);
    }

    // Test auth
    public function testResponseNoAuthPageSections()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Section::class)->make();

        // Actions
        $this->post(route('api.project.section.store'), $model->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseStatus('401');
        $this->missingFromDatabase('sections', ['markup' => $model->markup]); // Make sure data has not been posted
    }

}
