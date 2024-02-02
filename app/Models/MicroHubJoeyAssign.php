<?php

namespace App\Models;

use App\Models\Interfaces\MicroHubJoeyAssignInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MicroHubJoeyAssign extends Model implements MicroHubJoeyAssignInterface
{

    public $table = 'microhub_joey_assign';

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
