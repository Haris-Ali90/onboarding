<?php

namespace App\Models;

use App\Models\Interfaces\JoeyDocumentVerificationInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JoeyDocumentVerification extends Model implements JoeyDocumentVerificationInterface
{

    public $table = 'joeys';

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'plan_id',
        'first_name',
        'last_name',
        'nickname',
        'display_name',
        'email',
        'password',
        'address',
        'suite',
        'buzzer',
        'city_id',
        'state_id',
        'country_id',
        'postal_code',
        'phone',
        'image_path',
        'image',
        'about_yourself',
        'about',
        'preferred_zone',
        'hear_from',
        'is_newsletter',
        'is_enabled',
        'created_at',
        'updated_at',
        'deleted_at',
        'vehicle_id',
        'comdata_emp_num',
        'comdata_cc_num',
        'comdata_cc_num_2',
        'pwd_reset_token',
        'pwd_reset_token_expiry',
        'is_busy',
        'current_location_id',
        'email_verify_token',
        'is_online',
        'balance',
        'location_id',
        'hst_number',
        'rbc_deposit_number',
        'cash_on_hand',
        'timezone',
        'work_type',
        'contact_time',
        'interview_time',
        'has_bag',
        'is_backcheck',
        'on_duty',
        'preferred_zone_id',
        'shift_amount_due',
        'is_on_shift',
        'api_key',
        'is_itinerary',
        'hub_id',
        'vendor_id',
        'category_id',
        'merchant_id',
        'cirminal_status',
        'driving_licence_status',
        'work_permit_status',
        'driving_licence_picture',
        'driving_licence_exp_date',
        'work_permit_image',
        'work_permit_exp_date',
        'document_status',
        'quiz_status',
        'profile_status',
        'is_background_check',
        'unit_number',
        'hub_joey_type',
        'has_route','is_active'



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
    public function getDisplayNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }
    public function setDisplayNameAttribute($value)
    {

        $this->attributes['display_name'] =$value;
    }

    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }
    public function setFirstNameAttribute($value)
    {

        $this->attributes['first_name'] =$value;
    }


    public function getLastNameAttribute($value)
    {
        return ucfirst($value);
    }
    public function setLastNameAttribute($value)
    {

        $this->attributes['last_name'] =$value;
    }
    public function agreementUser()
    {
        return $this->belongsTo(AgreementUser::class, 'id','user_id');
    }
    public function joeyDocumentsApproved()
    {
        return $this->hasMany(JoeyDocument::class, 'joey_id','id')->where('is_approved',1);
    }

    public function joeyDocuments()
    {
        return $this->hasMany(JoeyDocument::class, 'joey_id','id');
    }

    public function joeyAttemptedQuiz()
    {
        return $this->hasMany(JoeyQuiz::class, 'joey_id','id')->where('is_passed',1);
    }

    public function trainingSeen() {
        return $this->hasMany(JoeyTrainingSeen::class,'joey_id','id');
    }
    ### for joeyDeposit
    public function Deposit()
    {
        return $this->belongsTo(JoeyDeposit::class, 'id','joey_id');
    }

    ### For joey docuemnt not uploaded
    public function joeyDocumentsNotUploaded()
    {
        return $this->hasMany(JoeyDocument::class, 'joey_id','id');
    }
    ### For joey docuemnt not appvoved
    public function joeyDocumentsNotApproved()
    {
        return $this->hasMany(JoeyDocument::class, 'joey_id','id')->where('is_approved',0);
    }
    ### joey Not trained
    public function joeyNotTrained()
    {
        return $this->hasMany(JoeyQuiz::class, 'joey_id','id')->where('is_passed',0);
    }
    ### quiz pending
    public function joeyPendingQuiz()
    {
        return $this->hasMany(JoeyQuiz::class, 'joey_id','id');
    }

    ### training watched
    public function trainingWatched()
    {
        return $this->hasMany(JoeyTrainingSeen::class, 'joey_id','id');
    }

    public function joeyComplaints()
    {
        return $this->hasMany(JoeyComplaints::class, 'joey_id','id');
    }

    public function joeyDetail()
    {
        return $this->hasMany(JoeyDocument::class, 'joey_id','id')->whereNull('joey_documents.deleted_at')->select('joey_documents.id','joey_documents.joey_id','joey_documents.document_type','joey_documents.document_type_id','joey_documents.document_data','joey_documents.exp_date','joey_documents.is_approved');
    }

    public function documentType() {

        return $this->belongsTo(Documents::class,'document_type_id','id')
            ->whereNull('document_types.deleted_at');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class,'preferred_zone','id');
    }

    ### For joey docuemnt not appvoved
    public function joeyNotifications()
    {
        return $this->hasMany(JoeyNotification::class, 'joey_id','id');
    }

    ### For joey agreement not assigned
    public function joeyAgreementNotSigned()
    {
        return $this->hasOne(AgreementUser::class, 'user_id','id')->whereNull('signed_at');
    }

    public function joeyAgreementNotExist()
    {
        return $this->hasOne(AgreementUser::class, 'user_id','id')->whereNull('signed_at');
    }

    public function workType()
    {
        return $this->belongsTo(WorkType::class,'work_type_id','id');
    }

    public function joeyVehicle()
    {
        return $this->belongsTo( VehicleDetail::class,'id','joey_id')->where('deleted_at',null);
    }
	
	public function joeyLocation()
    {
        return $this->belongsTo( Locations::class,'location_id','id')->where('deleted_at',null);
    }

}
