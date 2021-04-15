<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class BasicCrudController extends Controller
{
    abstract protected function model();
    abstract protected function rulesStore();
    abstract protected function rulesUpdate();

    public function index()
    {
        return $this->model()::all();
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, $this->rulesStore());
        $obj = $this->model()::create($validatedData);
        return $obj->refresh();
    }

    protected function findOrFail($id)
    {
        $model = $this->model();
        $kyeName = (new $model)->getRouteKeyName();
        return $this->model()::where($kyeName, $id)->firstOrFail();
    }

    public function show($id)
    {
        return $this->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validate($request, $this->rulesUpdate());

        $obj = $this->findOrFail($id);
        $obj->update($validatedData);

        return response($obj, 200);
    }

    public function destroy($id)
    {
        $obj = $this->findOrFail($id);
        $obj->delete();
        return response()->noContent();
    }

}
