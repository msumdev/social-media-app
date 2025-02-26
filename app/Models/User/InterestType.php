<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterestType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @var bool $timestamps
     */
    public $timestamps = false;

    /**
     * @var string[] $hidden
     */
    public $hidden = [
        'pivot'
    ];
}
