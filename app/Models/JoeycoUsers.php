<?php

namespace App\Models;

use App\Models\Interfaces\JoeycoUsersInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JoeycoUsers extends Model implements JoeycoUsersInterface
{

    public $table = 'jc_users';

    //use SoftDeletes;

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

    /**
     * Relation for Micro Hub User List
     */
    public function microHubUserList(){
        return $this->hasOne(MicroHubRequest::class,'jc_user_id','id')
            ->whereNull('micro_hub_request.deleted_at')
            ->where('micro_hub_request.status','!=',1);
    }

    /**
     * Relation for Micro Hub User List
     */
    public function microHubRejectedUserList(){

        return $this->hasOne(MicroHubRequest::class,'jc_user_id','id')
            ->whereNull('micro_hub_request.deleted_at')
            ->where('micro_hub_request.status',2);
    }

    public function dashboardUser()
    {
        return $this->belongsTo(DashboardUsers::class,'email_address','email');
    }

    //Not Trained
    public function microHubUserTrainingSeen()
    {
        return $this->hasMany(MicroHubUserTrainingSeen::class,'jc_users_id','id');
    }

    // Quiz Pending
    public function microHubUserPendingQuiz()
    {
        return $this->hasMany(MicroHubUserQuizPending::class, 'jc_users_id','id');
    }

    public function microHubUserAttemptedQuiz()
    {
        return $this->hasMany(MicroHubUserQuizPending::class, 'jc_users_id','id')->where('is_passed',1);
    }

    //For Micro Hub Document Not Uploaded
    public function microHubUserDocumentsNotUploaded()
    {
        return $this->hasMany(MicroHubUserDocument::class, 'jc_users_id','id');
    }

    //Not Trained
    public function microHubUserDocument()
    {
        return $this->hasMany(MicroHubUserDocument::class, 'user_id','id');
        //return $this->hasOne(MicroHubUserDocument::class,'id','jc_users_id');
    }

    ### For document approved
    public function userDocumentsApproved()
    {
        return $this->hasMany(MicroHubUserDocument::class, 'jc_users_id','id')->where('is_approved',1);
    }

    ### For document not approved
    public function userDocumentsNotApproved()
    {
        return $this->hasMany(MicroHubUserDocument::class, 'jc_users_id','id')->where('is_approved',0);
    }
}
