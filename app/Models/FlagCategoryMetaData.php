<?php

namespace App\Models;

use App\Models\Interfaces\FlagCategoryMetaDataInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlagCategoryMetaData extends Model implements FlagCategoryMetaDataInterface
{

     use SoftDeletes;

    public $relationData = [];


    /**
     * @var array
     *
     * meta data types for refrance
     */
    public static $flag_meta_types = [
        'portal'=>'portal',
        'order_type'=>'order_type',
        'vendor_relation'=>'vendor_relation',
        'is_show_on_route'=>'is_show_on_route',
    ];


    public $table = 'flag_category_meta_data';


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
     *
     *  get flag_meta_types
     *
     */

    public function getFlagMetaTypes($arrg = [])
    {
        $retrun_data = self::$flag_meta_types;
        $arrg_type = gettype($arrg);
        if($arrg_type == 'array')
        {
            $retrun_data =  array_intersect($retrun_data,$arrg);
        }
        else if($arrg_type == 'string')
        {
            $retrun_data = $retrun_data[$arrg];
        }

        return $retrun_data;
    }

}
