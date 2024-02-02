<?php

namespace App\Models;
use App\Models\Interfaces\JoeyComplaintsInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JoeyComplaints extends Model implements JoeyComplaintsInterface
{

    public $table = 'complaints';

    use SoftDeletes;

 /**
 * The attributes that are guarded.
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

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function ComplaintsJoeys() {

        return $this->belongsTo(JoeyDocumentVerification::class,'joey_id','id')->whereNull('deleted_at');
    }


}
