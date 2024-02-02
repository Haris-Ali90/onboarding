<?php

namespace App\Models;

use App\Models\Interfaces\DashboardUsersInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DashboardUsers extends Model implements DashboardUsersInterface
{

    use SoftDeletes;
    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'dashboard_users';

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

    /**
     * Function For Postal Code
     **/
    public function getPostalCode()
    {
        return $this->hasMany(HubPostalCode::class, 'hub_id', 'hub_id')->get();
    }

    /**
     * Function For Postal Code
     **/
    public function getHubCode()
    {
        return $this->belongsTo(Hubs::class, 'hub_id', 'id')->first('is_consolidated');
    }


}
