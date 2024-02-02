<?php

namespace App\Models;


use App\Models\Interfaces\JobTypeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobType extends Model implements JobTypeInterface
{
    use SoftDeletes;
    public $table = 'job_type';

    protected $guarded = [];

    protected $fillable = [
        'title',

    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];

    /**
     * The attributes that should be append to toArray.
     *
     * @var array
     */
    protected $appends = [];




}
