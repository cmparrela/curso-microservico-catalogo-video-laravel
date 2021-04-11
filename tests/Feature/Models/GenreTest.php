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
            'name' => 'teste',
        ]);
        $genre->refresh();

        $this->assertEquals('teste', $genre->name);
        $this->assertNull($genre->description);
        $this->assertTrue((bool) $genre->is_active);
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
}
