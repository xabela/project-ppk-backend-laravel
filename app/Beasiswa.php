<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase;
use Illuminate\Support\Facades\App;

class Beasiswa extends Model
{
    use Uuid;
    protected $table = 'beasiswa';

    public $incrementing = false;

    public $with = ['data_penyelenggara'];
    public $hidden = ['pendaftaran'];
    protected $appends = ['jumlah_pendaftar', 'foto_beasiswa'];

    public function pendaftaran()
    {
        return $this->hasMany('App\Pendaftaran', 'id_beasiswa', 'id');
    }

    public function data_penyelenggara()
    {
        return $this->belongsTo('App\User', 'penyelenggara', 'username');
    }

    public function getJumlahPendaftarAttribute()
    {
        return count($this->pendaftaran);
    }

    public function getFotoBeasiswaAttribute()
    {
        $factory = App::make(Firebase\Factory::class);
        $storage = $factory->createStorage();
        $bucket = $storage->getBucket();
        $pas_foto_pendaftar_file_name = 'pas_foto/pas_foto_' . $this->id . '.png';
        $pas_foto_pendaftar = $bucket->object($pas_foto_pendaftar_file_name);
        if ($pas_foto_pendaftar->exists()) {
            return $pas_foto_pendaftar->signedUrl(new \DateTime('tomorrow'));
        }
        return "https://dummyimage.com/400x400/000/fff&text={$this->nama}";
    }
}
