<?php

namespace App\Models;

use App\Models\Interfaces\ZoneTypesInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZoneTypes extends Model implements ZoneTypesInterface
{

    public $table = 'zones_types';

    use SoftDeletes;

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
    protected $casts = [
    ];

}
