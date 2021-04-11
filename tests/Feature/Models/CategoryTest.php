<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Category::class, 1)->create();
        $categories = Category::all();
        $this->assertCount(1, $categories);

        $categoryKey = array_keys($categories->first()->toArray());
        $this->assertEquals([
            'id',
            'name',
            'description',
            'is_active',
            'deleted_at',
            'created_at',
            'updated_at',
        ],
            $categoryKey
        );
    }

    public function testCreate()
    {
        $category = Category::create([
            'name' => 'teste',
        ]);
        $category->refresh();

        $this->assertEquals('teste', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue((bool) $category->is_active);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create()->first();

        $data = [
            'name' => 'Limpeza',
            'description' => 'Produtos de limpeza',
            'is_active' => false,
        ];
        $category->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }
}
