<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends BasicCrudController
{

    private $rules;

    public function __construct()
    {
        $this->rules = [
            'title' => 'required|max:255',
            'description' => 'required',
            'year_launched' => 'required|date_format:Y',
            'opened' => 'boolean',
            'rating' => 'required|in:' . implode(',', Video::RATING_LIST),
            'duration' => 'required|integer',
            'categories_id' => 'required|array|exists:categories,id',
            'genres_id' => 'required|array|exists:genres,id',
        ];
    }

    public function store(Request $request)
    {
        $validatedData = $this->validate($request, $this->rulesStore());
        $self = $this;

        $obj = DB::transaction(function () use ($validatedData, $request, $self) {
            $obj = $this->model()::create($validatedData);
            $self->handleRelations($obj, $request);
            return $obj;
        });

        return $obj->refresh();
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validate($request, $this->rulesUpdate());
        $obj = $this->findOrFail($id);
        $self = $this;

        $obj = DB::transaction(function () use ($validatedData, $request, $self, $obj) {
            $obj->update($validatedData);
            $self->handleRelations($obj, $request);
            return $obj;
        });
        return response($obj, 200);
    }

    protected function handleRelations($video, Request $request)
    {
        $video->categories()->sync($request->get('categories_id'));
        $video->genres()->sync($request->get('genres_id'));
    }

    protected function model()
    {
        return Video::class;
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
