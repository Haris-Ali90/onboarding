<?php

namespace App\Models;

use App\Models\Interfaces\MiJobsAssignInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MiJobsAssign extends Model implements MiJobsAssignInterface
{

    public $table = 'microhub_assign_mi_jobs';

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
