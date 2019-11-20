<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    protected $table = 'beasiswa';

    public $incrementing = false;

    public function pendaftaran()
    {
        return $this->hasMany('App\Pendaftaran', 'id_beasiswa', 'beasiswa');
    }
}
 //controller mbe req, opo? iyo beasiswaa