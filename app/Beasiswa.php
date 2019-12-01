<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    use Uuid;
    protected $table = 'beasiswa';

    public $incrementing = false;

    public $with = ['data_penyelenggara'];
    public $hidden = ['pendaftaran'];
    protected $appends = ['jumlah_pendaftar'];

    public function pendaftaran()
    {
        return $this->hasMany('App\Pendaftaran', 'id_beasiswa', 'id')->select('id', 'username');
    }

    public function data_penyelenggara()
    {
        return $this->belongsTo('App\User', 'penyelenggara', 'username');
    }

    public function getJumlahPendaftarAttribute()
    {
        return count($this->pendaftaran);
    }
}
