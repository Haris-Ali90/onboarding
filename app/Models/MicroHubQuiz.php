<?php

namespace App\Models;
use App\Models\Interfaces\MicroHubQuizInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MicroHubQuiz extends Model implements MicroHubQuizInterface
{

    public $table = 'microhub_attempted_quiz';

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



    public function quizRecord(){
        return $this->belongsTo(QuizQuestion::class,'id','id');
    }
    public function microHubUsers(){

        return $this->belongsTo(JoeycoUsers::class,'jc_users_id','id');
    }
    public function category(){
        return $this->belongsTo(OrderCategory::class,'category_id','id');
    }


}
