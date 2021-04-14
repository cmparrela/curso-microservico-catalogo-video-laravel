<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class BasicCrudController extends Controller
{
    abstract protected function model();
    abstract protected function rulesStore();

    public function index()
    {
        return $this->model()::all();
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, $this->rulesStore());
        $category = $this->model()::create($validatedData);
        return $category->refresh();
    }

    protected function findOrFail($id)
    {
        $model = $this->model();
        $kyeName = (new $model)->getRouteKeyName();
        return $this->model()::where($kyeName, $id)->firstOrFail();
    }

}
