<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->all());
        return $category->refresh();
    }

    public function show(Category $category)
    {
        return $category;
    }

    public function update(CategoryRequest $request, Category $category)
    {
        if ($category->update($request->all())) {
            return $category;
        }
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }
}
