<?php

namespace App\Models;
use App\Models\Interfaces\JoeyAttemptQuizInterface;
use App\Models\Interfaces\JoeyQuizInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\JoeyDocumentVerification;

class JoeyQuiz extends Model implements JoeyQuizInterface
{

    public $table = 'joey_attempted_quiz';

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'joey_id',
        'is_passed',
        'category_id'
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
    public function joey(){

        return $this->belongsTo(JoeyDocumentVerification::class,'joey_id','id');
    }
    public function category(){
        return $this->belongsTo(OrderCategory::class,'category_id','id');
    }


}
