<?php

namespace App\Models;


use App\Models\Interfaces\OrderCategoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCategory extends Model implements OrderCategoryInterface
{
    use SoftDeletes;
    public $table = 'order_category';

    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];

    /**
     * The attributes that should be append to toArray.
     *
     * @var array
     */
    protected $appends = [];


    public function questions(){
        return $this->hasMany(QuizQuestion::class,'order_category_id','id');
    }

    public function questionsMicroHub(){
        return $this->hasMany(QuizQuestion::class,'order_category_id','id')->where('user_type','micro_hub');
    }

}
