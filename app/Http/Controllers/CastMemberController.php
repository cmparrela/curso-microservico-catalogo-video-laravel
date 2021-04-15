<?php

namespace App\Http\Controllers;

use App\Models\CastMember;

class CastMemberController extends BasicCrudController
{

    private $rules;

    public function __construct()
    {
        $typeActor = CastMember::TYPE_ACTOR;
        $typeDirector = CastMember::TYPE_DIRECTOR;

        $this->rules = [
            'name' => 'required|max:255',
            'type' => "required|in:{$typeActor},{$typeDirector}",
        ];
    }

    protected function model()
    {
        return CastMember::class;
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
