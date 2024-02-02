<?php

namespace App\Models;

use App\Models\Interfaces\ZonesRoutingInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZonesRouting extends Model implements ZonesRoutingInterface
{

    public $table = 'zones_routing';

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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

    public function zoneType()
    {
        return $this->belongsTo(ZoneTypes::class, 'zone_type','id');
    }

}
