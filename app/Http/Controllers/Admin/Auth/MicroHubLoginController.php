<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\OnboardingLoginIp;
use App\Models\User;
use function GuzzleHttp\Promise\all;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

/**
 * Admin Login Controller
 */
class MicroHubLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {


        /*
         * Logged in user should not be able to visit login page, unless logged out
         *
         * It's redirection is managed from App > Http > Middleware > RedirectIfAuthenticated
         */
        //$this->middleware('guest:admin')->except('logout');
        parent::__construct();
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    private function get_ipaddress() {
        $ipaddress = null;
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        return $ipaddress;
    }

    protected function credentials(Request $request)
    {
        // return $request->only($this->username(), 'password');
        return [
            'email'     => $request->{$this->username()},
            'password'  => $request->password,
            // 'role_id' => 2,
            'status' => 1
        ];
    }

    /**
     * Handle forgot password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function forgotPassword(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('admin.auth.forgot-password');
    }


    //for micro hub login form show
    public function showMicroHubLoginForm()
    {

        if (Auth::check())
        {
            return redirect()->route('index');
        }

        return view('admin.micro-hub.auth.login');
    }

    //for micro hub login
    public function microHubLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        /* if ($this->attemptLogin($request)) {
             return $this->sendLoginResponse($request);
         }

         return $this->sendFailedLoginResponse($request);*/

        $admin = User::where('email', '=', $request->get('email'))->where('role_type','2')->where('login_type','micro_hub')->first();
        if (isset($admin->status)) {
            if ($admin->status != 1)
            {
                return redirect('micro-hub/login')->withErrors('Your Account Has Been Blocked Please Contact Admin!');
            }

        }
        if ($admin) {

                //$passwordencode = base64_encode($request->get('password'));
                //$id = base64_encode($admin->id);
                //$mail = base64_encode($admin->is_email);
                //$scan = base64_encode($admin->is_scan);
                ////dd([$passwordencode,$id,$mail,$scan]);
                //return redirect('micro-hub/type-auth?id=' . $id . '&key=' . $passwordencode.'&mail='.$mail.'&scan='.$scan );

            if (!Auth::attempt(['email'=>$request->get('email'),'password'=>$request->get('password'),'login_type'=>'micro_hub','role_type'=>'2','status'=>'1']))
            {
                return redirect('micro-hub/login')->withErrors('Invalid Username or Password.');
            }
            if ($this->attemptLogin($request)) {
                return redirect()->route('index');
            }

            /*$adminLoginIpTrusted = OnboardingLoginIp::where('onboarding_user_id', '=', $admin['id'])
                ->whereNull('deleted_at')
                ->where('ip', '=', $this->get_ipaddress())
                ->whereNotNull('trusted_date')
                ->where('trusted_date', '>', date('Y-m-d'))
                ->first();*/

            /*if (!is_null($adminLoginIpTrusted)) {
                return $this->microHubLogin($request);
            } else {
                $passwordencode = base64_encode($request->get('password'));
                $id = base64_encode($admin->id);
                $mail = base64_encode($admin->is_email);
                $scan = base64_encode($admin->is_scan);
                //dd([$passwordencode,$id,$mail,$scan]);
                return redirect('micro-hub/type-auth?id=' . $id . '&key=' . $passwordencode.'&mail='.$mail.'&scan='.$scan );

            }*/
        }
        else{
            return redirect('micro-hub/login')->withErrors('Invalid Email Address!');
        }

    }

    public function microHubLogout(Request $request)
    {
        if (Auth::guard()->check())
        {
            Auth::guard()->logout();
        }
        //dd(Auth::guard(),'out');
//        dd(auth()->guard('web'));
        //Redirect::to('micro-hub/login');
        // $request->session()->invalidate();
        return redirect()->route('micro-hub.login');
        //return view('admin.micro-hub.auth.login');
    }

    /**
     * Get view for selecting mfa
     *
     */
    public function getMicroHubType(Request $request){
        return view('admin.micro-hub.auth.logintype',$request->all() );
    }

    /**
     * getting auth token in mail
     *
     */
    public function postMicroHubTypeauth(Request $request)
    {
        $data=$request->all();
        if(strcmp("Scan",$data['type'])==0){

            return redirect('google-auth?id=' . $data['id'] . "&key=" . $data['key']);
        }
        else{

            $randomid = mt_rand(100000,999999);

            $admin = User::where('id','=', base64_decode($data['id']))->first();
            $admin['emailauthanticationcode'] = $randomid;

            $admin->save();

            //$admin->sendWelcomeEmail($randomid);

            $data['email'] = base64_encode($admin['email']);

            return redirect('micro-hub/verify-code?key=' . $data['key'] . '&email=' . $data['email']);

        }
    }

    /**
     * getting view for verify code
     *
     */
    public function getMicroHubVerifyCode(Request $request){
        return view('admin.micro-hub.auth.verificationcode', $request->all());
    }

    /**
     * check auth code
     *
     */
    public function postMicroHubVerifyCode(Request $request)
    {


        $code=$request->get('code');

        $data= User::where('email','=', base64_decode($request->get('email')))->where('role_type','2')->where('emailauthanticationcode','=',$code)->first();

        $email = base64_decode($request->get('email'));
        $passworddecode = base64_decode($request->get('key'));
        $request['email'] = $email;
        $request['password'] = $passworddecode;

        $email = $request->get('email');
        $key = $request->get('key');
        if(empty($data)){
            return redirect('micro-hub/verify-code?key=' . $key . '&email=' . base64_encode($email))->withErrors('Invalid verification code!');
        }
        else if (!Auth::attempt(['email'=>$email,'password'=>$passworddecode,'role_type'=>'2','status'=>'1']))
        {
            return redirect('micro-hub/login')->withErrors('Invalid Username or Password.');
        }
        if ($this->attemptLogin($request)) {
            return redirect()->route('index');
        }

        return $this->sendFailedLoginResponse($request);
    }

}
