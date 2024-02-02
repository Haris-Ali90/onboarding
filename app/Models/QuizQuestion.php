<?php

namespace App\Models;
use App\Models\Interfaces\QuizQuestionInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizQuestion extends Model implements QuizQuestionInterface
{

    public $table = 'quiz_questions';

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


    public function answers(){
       //dd($this->hasMany(QuizAnswer::class,'quiz_questions_id','id')->get());
        return $this->hasMany(QuizAnswer::class,'quiz_questions_id','id');
    }
    public function category(){
        //dd($this->hasMany(QuizAnswer::class,'quiz_questions_id','id')->get());
        return $this->belongsTo(OrderCategory::class,'order_category_id','id');
    }



}
