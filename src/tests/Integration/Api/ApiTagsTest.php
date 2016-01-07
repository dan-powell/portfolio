<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class ApiTagsTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
    }


    public function testResponseGetTags()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Tag::class)->create();

        // Actions
        $this->get(route('api.tag.index'), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'id' => $model->id,
            'title' => $model->title,
        ]);
    }


    public function testResponseGetTag()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Tag::class)->create();

        // Actions
        $this->get(route('api.tag.show', $model->id), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'id' => $model->id,
            'title' => $model->title,
        ]);
    }


    public function testResponsePostTag()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Tag::class)->make();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->post(route('api.tag.store'), $model->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'title' => $model->title,
        ]);
    }


    public function testResponsePutTag()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Tag::class)->create();
        $newModel = factory(DanPowell\Portfolio\Models\Tag::class)->make();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->put(route('api.tag.update', $model->id), $newModel->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'title' => $newModel->title,
        ]);

    }


    public function testResponseDeleteTag()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Tag::class)->create();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->delete(route('api.tag.destroy', $model->id), [], ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->notSeeInDatabase('tags', ['id' => $model->id]);
    }


    // Test auth
    public function testResponseNoAuthTag()
    {
        // Setup
        $persistentModel = factory(DanPowell\Portfolio\Models\Tag::class)->create();
        $model = factory(DanPowell\Portfolio\Models\Tag::class)->make();

        // Actions
        $this->post(route('api.tag.store'), $model->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseStatus('401');
        $this->missingFromDatabase('tags', ['title' => $model->title]); // Make sure data has not been posted

        // Actions
        $this->put(route('api.tag.update', $persistentModel->id), $model->toArray(), ['X-Requested-With' => 'XMLHttpRequest']);
        $this->seeInDatabase('tags', ['title' => $persistentModel->title]); // Make sure data has not been updated
        $this->missingFromDatabase('tags', ['title' => $model->title]); // Make sure data has not been posted

        // Assertions
        $this->assertResponseStatus('401');

        // Actions
        $this->delete(route('api.tag.destroy', $persistentModel->id), [], ['X-Requested-With' => 'XMLHttpRequest']);
        $this->seeInDatabase('tags', ['title' => $persistentModel->title]); // Make sure data has not been deleted

        // Assertions
        $this->assertResponseStatus('401');
    }




    // Search tags

    public function testResponseSearchTags()
    {
        // Setup
        $model = factory(DanPowell\Portfolio\Models\Tag::class, 10)->create();
        $randomTag = $model->random();
        $user = factory(App\User::class)->create();

        // Actions
        $this->actingAs($user);
        $this->get(route('api.tag.search') . '?query=' . $randomTag->title, ['X-Requested-With' => 'XMLHttpRequest']);

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'title' => $randomTag->title,
        ]);
    }


}
