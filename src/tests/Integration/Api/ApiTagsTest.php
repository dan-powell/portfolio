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
        $this->get(route('api.tag.index'));

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
        $this->get(route('api.tag.show', $model->id));

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
        $this->post(route('api.tag.store'), $model->toArray());

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
        $this->put(route('api.tag.update', $model->id), $newModel->toArray());

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
        $this->delete(route('api.tag.destroy', $model->id));

        // Assertions
        $this->assertResponseOk();
        $this->notSeeInDatabase('tags', ['id' => $model->id]);
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
        var_dump($this->get(route('api.tag.search') . '?query=' . $randomTag->title));

        // Assertions
        $this->assertResponseOk();
        $this->seeJson([
            'title' => $randomTag->title,
        ]);
    }


}
