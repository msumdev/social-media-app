<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFilter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'age_from',
        'age_to',
        'sexes',
        'genders',
        'countries',
        'city',
        'online',
        'keywords',
        'interests',
        'username',
    ];

    protected $casts = [
        'sexes' => 'collection',
        'genders' => 'collection',
        'countries' => 'collection',
        'keywords' => 'collection',
        'interests' => 'collection',
    ];

    /**
     * @var bool $timestamps
     */
    public $timestamps = true;
}
