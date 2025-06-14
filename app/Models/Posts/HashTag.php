<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDPhilip\Elasticsearch\Eloquent\HybridRelations;

class HashTag extends Model
{
    use HasFactory, HybridRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'pivot',
    ];
}
