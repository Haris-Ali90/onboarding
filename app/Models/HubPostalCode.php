<?php

namespace App\Models;

use App\Models\Interfaces\HubPostalCodeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HubPostalCode extends Model implements HubPostalCodeInterface
{
    use SoftDeletes;
    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'micro_hub_postal_codes';

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
