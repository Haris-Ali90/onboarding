<?php

namespace App\Models;

use App\Models\Interfaces\FlagOrderTypeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlagOrderType extends Model implements FlagOrderTypeInterface
{
    use SoftDeletes;

    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'flag_order_types';

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


}
