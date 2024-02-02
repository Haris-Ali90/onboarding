<?php

namespace App\Models;

use App\Models\Interfaces\BuildingInterface;
use App\Models\Interfaces\CategoryInterface;
use App\Models\Interfaces\ComplexInterface;
use App\Models\Interfaces\UserDeviceInterface;
use App\Models\Interfaces\VerificationInterface;
use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\CityInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class UserDevice extends Model implements UserDeviceInterface
{
    public $table = 'user_devices';

    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'device_token',
        'device_type',
        'auth_token',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [
        'user_id'          => 'integer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];

    /**
     * The attributes that should be append to toArray.
     *
     * @var array
     */
    protected $appends = [];


    /**
     * Get Customer Detail
     */
    public function userDetail()
    {
        return $this->belongsTo(\App\Models\User::class);
    }


}
