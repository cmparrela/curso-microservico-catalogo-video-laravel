<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class GenreUnitTest extends TestCase
{

    private $genre;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = new Genre();
    }

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            UuidTrait::class,
        ];
        $usedTraits = array_keys(class_uses(Genre::class));
        $this->assertEquals($traits, $usedTraits);
    }

    public function testFillableAttribute()
    {
        $fillable = ['name', 'is_active'];
        $this->assertEquals($fillable, $this->genre->getFillable());
    }

    public function testDateAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $this->assertEquals($dates, $this->genre->getDates());
    }

    public function testKeyTypeAttribute()
    {
        $this->assertEquals('string', $this->genre->getKeyType());
    }

    public function testCastsAttribute()
    {
        $casts = ['is_active' => 'boolean'];
        $this->assertEquals($casts, $this->genre->getCasts());
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->genre->incrementing);
    }
}
