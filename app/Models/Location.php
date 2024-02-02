<?php

namespace App\Models;

use App\Models\Interfaces\LocationInterface;
use Illuminate\Database\Eloquent\Model;

class Location extends Model implements LocationInterface
{

    public $table = 'locations';

    public $guarded = [];

    /**
     * get city table details
     */
    public function cityDetail()
    {
        return $this->hasOne(City::class,'id','city_id');
    }
}
