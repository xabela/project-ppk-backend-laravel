<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    use Uuid;
    protected $table = 'beasiswa';

    public $incrementing = false;

    public function pendaftaran()
    {
        return $this->hasMany('App\Pendaftaran', 'id_beasiswa', 'beasiswa');
    }
}
