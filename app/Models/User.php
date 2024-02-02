<?php

namespace App\Models;




use App\Mail\WelcomeMail;
use App\Models\Interfaces\UserInterface;
use App\Notifications\Backend\AdminResetPasswordNotification;
use App\Notifications\Backend\ResetPasswordEmailSend;
use Carbon\Carbon;
use DB;
use http\Url;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable implements UserInterface, JWTSubject
{

    public $table = 'onboarding_users';

    use SoftDeletes,Notifiable;


    public const ROLE_ADMIN       = 'admin';
    public const ROLE_ID = '1';



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAdmin($query)
    {
        $admin_role_id =  config('app.super_admin_role_id');
        return $query->where('role_id', '!=',$admin_role_id);
    }

    /*
 * @User related methods
 */
    public function validateUserActiveCriteria() : bool
    {
        if((int)$this->attributes['is_active'] === 0){

            if((int)$this->attributes['is_unblock'] === 0){
                //throw new \Mockery\Exception('Your account has been blocked by the admin, please contact '. constants('global.site.name').' admin');
                throw new \App\Exceptions\UserNotAllowedToLogin('Your account has been blocked by the admin, please contact ', 'account_block');
            }
            if((int)$this->attributes['is_verified'] === 0){
                //throw new \Mockery\Exception('Your account has not been verify by the admin, please contact '. constants('global.site.name').' admin');
                throw new \App\Exceptions\UserNotAllowedToLogin('Your account has not been verify by the admin, please contact ', 'account_verify');
            }

            if((int)$this->attributes['email_verified'] !== 1){
                //throw new \Mockery\Exception('Your email is not verified please verify your email first.');
                throw new \App\Exceptions\UserNotAllowedToLogin('Your email is not verified please verify your email first.', 'account_email_verify');
            }

            if((int)$this->attributes['sms_verified'] !== 1){
                //throw new \Mockery\Exception('Please verify your mobile number first, it\'s not verified.');
                throw new \App\Exceptions\UserNotAllowedToLogin('Please verify your mobile number first, it\'s not verified.', 'account_sms_verify');
            }

            //throw new \Mockery\Exception('Your account is inactive, please contact '. constants('global.site.name').' admin');
            throw new \App\Exceptions\UserNotAllowedToLogin('Your account is inactive, please contact '. constants('global.site.name').' admin', 'account_active');
        }

        return true;

    }

    public function deactivate() : void
    {
        $this->status  = 0;
        $this->save();
    }

    public function activate() : void
    {
        $this->status  = 1;
        $this->save();
    }

    public function getStatusTextFormattedAttribute() : string
    {
        return (int)$this->attributes['status'] === 1 ?
            '<a href="'. route('sub-admin.inactive', $this->attributes['id']) .'"><span class="label label-success">Active</span></a>' :
            '<a href="'. route('sub-admin.active', $this->attributes['id']) .'"><span class="label label-warning">Inactive</span></a>';
    }

    public function sendPasswordResetNotification($token)
    {

        $this->notify(new \App\Notifications\Backend\AdminResetPasswordNotification($token));
    }



    public function getPhoneFormattedAttribute()
    {
        return $this->attributes['phone'] ? phone($this->attributes['phone'])->formatNational() : '';// $this->attributes['phone'] : '';
    }

/*    public function getProfilePictureAttribute() {
        $file = $this->attributes['profile_picture'];
        return is_file(backendUserFile().$file) ? backendUserUrl($file) : backendUserUrl(constants('front.default.admin'));
    }*/

 /*   public function getProfilePictureAutoAttribute() : string
    {
        $file = $this->attributes['profile_picture'];
        return is_file(backendUserFile().$file) ? $file : constants('front.default.admin');
    }*/


    /**
     * Get current permissions user.
     *
     * @return array
     */
    public function getPermissions()
    {
       
        return $this->Permissions->pluck('route_name')->toArray();
    }


    /**
     * Get dashboard cards rights user.
     *
     * @return array
     */
    public function getDashboardCardsPermissionsArray()
    {
        $data = $this->Role->pluck('dashbaord_cards_rights');

    }



    /**
     * Get the role of user.
     *
     * @return array
     */

    public function Role()
    {
        return $this->belongsTo(Roles::class, 'role_id','id');
    }



    /**
     * Get the role of user.
     *
     * @return array
     */

    public function DashboardCardRightsArray()
    {
        $rights = false;
        $data = $this->Role()->pluck('dashbaord_cards_rights')->first();

        if($this->role_id == Permissions::GetSuperAdminRole())
        {
            return true;
        }

        if($data != null && $data != '')
        {
            $rights = explode(',',$data);

        }

        return $rights;

    }

    /**
     * Get the role permissions .
     *
     * @return array
     */

    public function Permissions()
    {
        return $this->hasMany(Permissions::class, 'role_id','role_id');
    }


    /**
     * Get permissions data extracted .
     *
     * @return array
     */

    public function PermissionsExtract()
    {
        return $this->hasMany(Permissions::class, 'role_id','role_id')->pluck('route_name')->toArray();
    }

 public  function sendSubAdminEmail($email,$token,$role_type)
 {
     $this->notify(new ResetPasswordEmailSend($email,$token,$role_type));
 }

    /**
     * Send Reset Password Email
     */
    public function sendPasswordResetEmail($email,$token,$role_type)
    {
        $this->notify(new AdminResetPasswordNotification($email,$token,$role_type));
    }

    /**
     * Send Code For MFA
     */
    public function sendWelcomeEmail($randomid)
    {
        $email = $this->attributes['email'];
        $full_name = $this->attributes['full_name'];
        $style = "font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';color: black !important;";
        $bg_img = 'background-image:url(' . url("/images/joeyco_icon_water.png") . ');';
        $bg_img = trim($bg_img);
        $body = '<div class="row" style=" width: 32%;margin: 0 AUTO;">
                <div style="text-align: center;
    background-color: lightgrey;"><img src="' . url('/') . '/assets/admin/logo.png" alt="Web Builder" class="img-responsive" style="margin:0 auto; width:150px;" /></div>
                <div style="' . $bg_img . '
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;">
                  <h1 style="' . $style . '">Hi, ' . $full_name . '!</h1>
            
                 <p style="' . $style . '">You are receiving this email because we received a Two-factor authentication request for your account.</p>
                <p style="' . $style . '">Your Two-factor authentication code is <span style="background-color: #E36D28;border: 0px;">' . $randomid . '</span></p>
				<br/>
                 <p style="' . $style . '">If you did not request a Two-factor authentication, no further action is required.</p>
                  <br/>
               
               
                </div>
                <div style="background-color: lightgrey;padding: 5px;">
        <p style="padding-bottom: -1px;margin: 0px;margin-left: 20px;' . $style . '">JoeyCo Inc.</p>
        <p style="margin-top: 0x;margin: 0px;margin-left: 20px;' . $style . '">16 Four Seasons Pl., Etobicoke, ON M9B 6E5</p>
        <p style="margin: 0px;margin-left: 20px;' . $style . '">+1 (855) 556-3926 · support@joeyco.com </p>   
    </div>
                </div>
                ';
        $subject = "Your 6 digit code for Authentication";
        Mail::send(array(), array(), function ($m) use ($email, $subject, $body) {
            $m->to($email)
                ->subject($subject)
                ->from(env('MAIL_USERNAME'))
                ->setBody($body, 'text/html');
        });
    }

}
