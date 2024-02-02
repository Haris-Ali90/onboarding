<?php

namespace App\Models;

use App\Models\Interfaces\CitiesInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cities extends Model implements CitiesInterface
{

    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'cities';

    /**
     * The attributes that are guarded.
     *
     * @var array
     */
    protected $guarded = [
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];


}
