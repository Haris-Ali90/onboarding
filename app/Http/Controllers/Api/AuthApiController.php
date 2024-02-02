<?php

namespace App\Http\Controllers\Api;


use Illuminate\Foundation\Auth\SendsPasswordResetEmails;


class AuthApiController extends ApiBaseController
{


    use SendsPasswordResetEmails;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

}
