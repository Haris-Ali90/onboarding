<?php

namespace App\Models;

use App\Models\Interfaces\MicroHubUserTrainingSeenInterface;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MicroHubUserTrainingSeen extends Model implements MicroHubUserTrainingSeenInterface
{

    public $table = 'microhub_training_seen';

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
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */    public function getName() {
    return $this->attributes['name'];
}


}
