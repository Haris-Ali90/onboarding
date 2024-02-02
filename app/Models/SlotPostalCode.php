<?php

namespace App\Models;

use App\Models\Interfaces\SlotPostalCodeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SlotPostalCode extends Model implements SlotPostalCodeInterface
{

    public $table = 'slots_postal_code';

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
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
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */


    public function vendors() {
        return $this->belongsTo(Vendor::class,'vendor_id')->whereNull('vendors.deleted_at');
    }

}
