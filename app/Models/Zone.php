<?php

namespace App\Models;

use App\Models\Interfaces\ZoneInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model implements ZoneInterface
{

    public $table = 'zones';

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'latitude',
        'longitude',
        'radius',
        'broadcast_time_offset',
        'created_at',
        'updated_at',
        'deleted_at',
        'timezone',
        'sameday_cutoff_time',

    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */


}
