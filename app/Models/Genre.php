<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use SoftDeletes;
    use UuidTrait;

    protected $fillable = ['name', 'is_active'];
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    protected $keyType = 'string';

    public $incrementing = false;

}
