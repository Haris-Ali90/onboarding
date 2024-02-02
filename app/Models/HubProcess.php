<?php

namespace App\Models;

use App\Models\Interfaces\HubProcessInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HubProcess extends Model implements HubProcessInterface
{
    use SoftDeletes;
    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'hub_process';

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
