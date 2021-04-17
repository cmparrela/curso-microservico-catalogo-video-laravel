<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Genre::class, 1)->create();
        $genres = Genre::all();
        $this->assertCount(1, $genres);

        $genreKey = array_keys($genres->first()->toArray());
        $this->assertEquals([
            'id',
            'name',
            'is_active',
            'deleted_at',
            'created_at',
            'updated_at',
        ],
            $genreKey
        );
    }

    public function testCreate()
    {
        $genre = Genre::create([
            'name' => 'test1',
        ]);
        $genre->refresh();

        $this->assertNotEmpty($genre->id);
        $this->assertEquals(36, strlen($genre->id));
        $this->assertTrue((bool) preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $genre->id));

        $this->assertEquals('test1', $genre->name);
        $this->assertTrue($genre->is_active);

        $genre = Genre::create(['name' => 'test1', 'is_active' => false]);
        $this->assertFalse($genre->is_active);

        $genre = Genre::create(['name' => 'test1', 'is_active' => true]);
        $this->assertTrue($genre->is_active);
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create()->first();

        $data = [
            'name' => 'Limpeza',
            'is_active' => false,
        ];
        $genre->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testDelete()
    {
        $genre = factory(Genre::class)->create();
        $genre->delete();

        $genreDeleted = Genre::onlyTrashed()->find($genre->id);
        $this->assertNotNull($genreDeleted);
    }
}
