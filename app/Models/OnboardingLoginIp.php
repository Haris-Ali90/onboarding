<?php

namespace App\Models;

use App\Models\Interfaces\OnboardingLoginIpInterface;
use Illuminate\Database\Eloquent\Model;

class OnboardingLoginIp extends Model implements OnboardingLoginIpInterface
{

    public $table = 'onboarding_login_ips';

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
