<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Genre;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $genre;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = factory(Genre::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('genres.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->genre->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('genres.show', ['genre' => $this->genre->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->genre->toArray());
    }

    public function testInvalidationData()
    {
        $response = $this->json('POST', route('genres.store', []));
        $this->assertInvalidationRequired($response);

        $response = $this->json('POST', route('genres.store', [
            'name' => str_repeat('a', 256),
            'is_active' => 'a',
        ]));
        $this->assertInvalidationMax($response);
        $this->AssertInvalidationBoolean($response);

        $response = $this->json('PUT', route('genres.update', ['genre' => $this->genre->id]));
        $this->assertInvalidationRequired($response);

        $response = $this->json('PUT', route('genres.update', ['genre' => $this->genre->id]), [
            'name' => str_repeat('a', 256),
            'is_active' => 'a',
        ]);
        $this->assertInvalidationMax($response);
        $this->AssertInvalidationBoolean($response);
    }

    private function assertInvalidationRequired(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                Lang::get('validation.required', ['attribute' => 'name']),
            ]);
    }

    private function assertInvalidationMax(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name'])
            ->assertJsonFragment([
                Lang::get('validation.max.string', ['attribute' => 'name', 'max' => '255']),
            ]);
    }

    private function AssertInvalidationBoolean(TestResponse $response)
    {
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['is_active'])
            ->assertJsonFragment([
                Lang::get('validation.boolean', ['attribute' => 'is active']),
            ]);
    }

    public function testStore()
    {
        $response = $this->json('POST', route('genres.store'), [
            'name' => 'teste',
        ]);

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response->assertStatus(201);
        $response->assertJson($genre->toArray());
        $this->assertTrue($response->json('is_active'));

        $response = $this->json('POST', route('genres.store'), [
            'name' => 'teste',
            'is_active' => false,
        ]);
        $response->assertJsonFragment([
            'is_active' => false,
        ]);
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create([
            'is_active' => false,
        ]);

        $response = $this->json('PUT', route('genres.update', ['genre' => $genre->id]), [
            'name' => 'teste',
            'is_active' => true,
        ]);

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray())
            ->assertJsonFragment([
                'is_active' => true,
            ]);
    }
}
