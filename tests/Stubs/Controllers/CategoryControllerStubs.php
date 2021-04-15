<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\BasicCrudController;
use Tests\Stubs\Models\CategoryStub;

class CategoryControllerStubs extends BasicCrudController
{
    private $rules = [
        'name' => 'required|max:255',
        'description' => 'nullable',
        'is_active' => 'boolean',
    ];

    protected function model()
    {
        return CategoryStub::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        return $this->rules;
    }
}
