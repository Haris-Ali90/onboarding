<?php

namespace App\Models;

use App\Models\Interfaces\CustomerFlagCategoryValuesInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFlagCategoryValues extends Model implements CustomerFlagCategoryValuesInterface
{

    public $table = 'customer_flag_category_values';


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
     * Function for 1st incident
     *
     */
    public function getFirstIncident()
    {
        return $this->belongsTo(CustomerIncidents::class, 'incident_1_ref_id', 'id');
    }

    /**
     * Function for 1st incident
     *
     */
    public function getSecondIncident()
    {
        return $this->belongsTo(CustomerIncidents::class, 'incident_2_ref_id', 'id');
    }

    /**
     * Function for 1st incident
     *
     */
    public function getThirdIncident()
    {
        return $this->belongsTo(CustomerIncidents::class, 'incident_3_ref_id', 'id');
    }

    /**
     * Function for 1st incident
     *
     */
    public function getConclusionIncident()
    {
        return $this->belongsTo(CustomerIncidents::class, 'conclusion_ref_id', 'id');
    }

}
