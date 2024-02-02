<?php

namespace App\Models;

use App\Models\Interfaces\JoeyPlansInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JoeyPlans extends Model implements JoeyPlansInterface
{

    public $table = 'joey_plans';

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'vehicle_using_type',
        'plan_type',
        'internal_name',
        'scheduled_commission',
        'unscheduled_commission',
        'hourly_rate',
        'view_all_orders',
        'has_minimum_hourly',
        'minimum_hourly_income',
        'cash_limit',
        'base_amount_applied',

    ];


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


}
