<?php

namespace App\Models;

use App\Models\Interfaces\CustomerFlagCategoryPortalsInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFlagCategoryPortals extends Model implements CustomerFlagCategoryPortalsInterface
{

     use SoftDeletes;

    public $table = 'customer_flag_category_portals';


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
