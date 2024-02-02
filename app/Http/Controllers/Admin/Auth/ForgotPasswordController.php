<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
        parent::__construct();
    }

    public function broker()
    {
        return Password::broker('admins');
    }

    /**
     * Show Forgot Password View
     *
     */
    public function showLinkRequestForm()
    {

        return view('admin.auth.passwords.email');
    }


    /**
     * Show custom reset password form
     */
    public function send_reset_link_email(Request $request)
    {
        $user = User::where('email', $request->email)->where('role_type',$request->role_type)->first();
        if (!$user) {
            return redirect()
                ->route('password.request')->with('status',  'Email not exist. try again!.');
        }
        $token_validate = DB::table('onboarding_password_resets')
            ->where('email', $request->email)
            ->where('role_type', $request->role_type)
            ->whereNull('deleted_at')
            ->first();
        $token = hash('ripemd160',uniqid(rand(),true));
        if ($token_validate == null) {
            DB::table('onboarding_password_resets')->whereNull('deleted_at')
                ->insert(['email'=> $request->email,'role_type' =>  $request->role_type,'token' => $token]);
        }
        else
        {
            DB::table('onboarding_password_resets')->where('email', $request->email)
                ->where('role_type', $request->role_type)->whereNull('deleted_at')
                ->update(['token' => $token]);
        }

        $email = base64_encode ($request->email);
        $user->sendPasswordResetEmail($email,$token,$request->role_type);
        return redirect()
            ->route('password.request')->with('status',  'We have sent your password reset link on email, Please also check Junk/Spam folder as well!');
    }

}
