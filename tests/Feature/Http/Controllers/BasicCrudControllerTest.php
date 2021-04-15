<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\BasicCrudController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Mockery;
use ReflectionClass;
use Tests\Stubs\Controllers\CategoryControllerStubs;
use Tests\Stubs\Models\CategoryStub;
use Tests\TestCase;

class BasicCrudControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        CategoryStub::dropTable();
        CategoryStub::createTable();

        $this->controller = new CategoryControllerStubs();
    }

    protected function tearDown(): void
    {
        CategoryStub::dropTable();
        parent::tearDown();
    }

    private function createCategory()
    {
        return CategoryStub::create(['name' => 'test_name', 'description' => 'test_description'])->refresh();
    }

    public function testIndex()
    {
        $category = $this->createCategory();
        $result = $this->controller->index()->toArray();
        $this->assertEquals([$category->toArray()], $result);
    }

    private function createMockRequest(array $data)
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('all')->once()->andReturn($data);
        return $request;
    }

    public function testInvalidationDataInStore()
    {
        $this->expectException(ValidationException::class);
        $request = $this->createMockRequest(['name' => '']);
        $this->controller->store($request);
    }

    public function testStore()
    {
        $request = $this->createMockRequest(['name' => 'test_name', 'description' => 'test_description']);
        $obj = $this->controller->store($request);
        $this->assertEquals(CategoryStub::find(1)->toArray(), $obj->toArray());
    }

    private function reflectionControllerFindOrFail(int $id)
    {
        $reflectionClass = new ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('findOrFail');
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invokeArgs($this->controller, [$id]);
    }

    public function testIfFindOrFailFetchModel()
    {
        $category = $this->createCategory();
        $result = $this->reflectionControllerFindOrFail($category->id);
        $this->assertInstanceOf(CategoryStub::class, $result);
    }

    public function testIfFindOrFailThrowExceptionWhenIdInvalid()
    {
        $this->expectException(ModelNotFoundException::class);
        $result = $this->reflectionControllerFindOrFail(0);
    }

    public function testShow()
    {
        $category = $this->createCategory();
        $result = $this->controller->show($category->id);
        $this->assertEquals($result->toArray(), $category->toArray());
    }

    public function testUpdate()
    {
        $category = $this->createCategory();
        $request = $this->createMockRequest(['name' => 'test_name', 'description' => 'test_description']);

        $this->controller->update($request, $category->id);
        $result = $this->reflectionControllerFindOrFail($category->id);

        $this->assertEquals($category->toArray(), $result->toArray());
    }

    public function testDestroy()
    {
        $category = $this->createCategory();
        $this->controller->destroy($category->id);

        $this->assertNull(CategoryStub::find($category->id));
        $this->assertNotNull(CategoryStub::withTrashed()->find($category->id));
    }

}
