<?php

namespace App\Http\Controllers\Api;

class OnBoardingPermissionController  extends ApiBaseController
{



    public function __construct()
    {

    }

    public function getOnBoardingPermissions()
    {
        $permissions_list = config('permissions');
        return $permissions_list;
    }


}
