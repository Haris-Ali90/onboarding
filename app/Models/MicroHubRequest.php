<?php

namespace App\Models;

use App\Models\Interfaces\MicroHubRequestInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MicroHubRequest extends Model implements MicroHubRequestInterface
{

    public $table = 'micro_hub_request';

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
