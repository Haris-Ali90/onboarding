<?php

namespace App\Models;

use App\Models\Interfaces\MiJobsInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MiJobs extends Model implements MiJobsInterface
{

    public $table = 'mi_jobs';

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
