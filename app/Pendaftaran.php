<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use Uuid;
    protected $table = 'user';

    public $incrementing = false;

    public $with = [
        'beasiswa'
    ];

    public function beasiswa()
    {
        return $this->belongsTo('App\Beasiswa', 'id_beasiswa', 'id');
    }

    public function pendaftaran()
    {
        return $this->belongsTo('App\Beasiswa', 'username', 'username');
    }
}
