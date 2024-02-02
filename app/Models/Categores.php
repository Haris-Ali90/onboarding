<?php

namespace App\Models;

use App\Models\Interfaces\CategoresInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categores extends Model implements CategoresInterface
{

    public $table = 'categores';

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'category_id',
        'order_count',
        'deleted_at',
        'created_at',
        'updated_at',

    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'category_id'          => 'integer',
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */

    public function category() {
        return $this->belongsTo(Categories::class,'category_id','id');
    }


}
