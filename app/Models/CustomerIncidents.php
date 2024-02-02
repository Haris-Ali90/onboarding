<?php

namespace App\Models;

use App\Models\Interfaces\CustomerIncidentsInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerIncidents extends Model implements CustomerIncidentsInterface
{

    public $table = 'customer_flag_incidents';

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

    /**
     * Disable Function
     **/
    public function disable(): void
    {
        $this->is_active = 0;
        $this->save();
    }

    /**
     * Enable Function
     **/
    public function enable(): void
    {
        $this->is_active = 1;
        $this->save();
    }

    /**
     * Scope For System Default Incident
     **/
    public function scopeEnableIncident($query)
    {
        return $query->where('is_active', 1);
    }


}
