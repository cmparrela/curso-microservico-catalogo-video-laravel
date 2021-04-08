<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use UuidTrait;
    use SoftDeletes;

    protected $fillable = ['name', 'is_active'];
    protected $dates = ['deleted_at'];
    protected $keyType = 'string';

    public $incrementing = false;

}
