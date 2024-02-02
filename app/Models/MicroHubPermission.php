<?php

namespace App\Models;

use App\Models\Interfaces\MicroHubPermissionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MicroHubPermission extends Model implements MicroHubPermissionInterface
{

    public $table = 'micro_hub_permissions';

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
