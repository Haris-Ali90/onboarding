<?php

namespace App\Models;
use App\Models\Interfaces\MicroHubUserDocumentInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MicroHubUserDocument extends Model implements MicroHubUserDocumentInterface
{

    public $table = 'microhub_documents';

    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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

    //Not Trained
    public function microHubUserDocumentType()
    {
        return $this->belongsTo(Documents::class,'document_type_id','id')->where('type','users');
    }


}
