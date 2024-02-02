<?php

namespace App\Models;

use App\Models\Interfaces\LocationsInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Locations extends Model implements LocationsInterface
{

    private $key;
    private $cipher;

    public function __construct()
    {
        // getting keys
        $this->key = 'c9e92bb1ffd642abc4ceef9f4c6b1b3aaae8f5291e4ac127d58f4ae29272d79d903dfdb7c7eb6e487b979001c1658bb0a3e5c09a94d6ae90f7242c1a4cac60663f9cbc36ba4fe4b33e735fb6a23184d32be5cfd9aa5744f68af48cbbce805328bab49c99b708e44598a4efe765d75d7e48370ad1cb8f916e239cbb8ddfdfe3fe';
        $this->cipher = 'f13c9f69097a462be81995330c7c68f754f0c6026720c16ad2c1f5f316452ee000ce71d64ed065145afdd99b43c0d632b1703fc6a6754284f5d19b82dc3697d664dc9f66147f374d46c94cf23a78f14f0c6823d1cbaa19c157b4cb81e106b79b11593dcddf675951bc07f54528fc8c03cf66e9c437595d1cac658a737ab1183f';
    }

    /**
     * Table name.
     *
     * @var array
     */
    public $table = 'locations_enc';

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
    protected $casts = [];

    public function setDecryptAddressAttribute($value, $locationId)
    {
        $query = DB::select("SELECT address, AES_DECRYPT(address,'$this->key','$this->cipher') AS decrypt_address  FROM locations_enc WHERE id = $locationId");
        return $query[0]->decrypt_address;
    }

    public function setDecryptPostalcodeAttribute($value, $locationId)
    {
        $query = DB::select("SELECT postal_code, AES_DECRYPT(postal_code,'$this->key','$this->cipher') AS decrypt_postal_code  FROM locations_enc WHERE id = $locationId");
        return $query[0]->decrypt_postal_code;
    }


}
