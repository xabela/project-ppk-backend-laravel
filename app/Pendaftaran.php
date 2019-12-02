<?php

namespace App;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Kreait\Firebase;
use Illuminate\Support\Facades\App;

class Pendaftaran extends Model
{
    use Uuid;
    protected $table = 'pendaftarans';
    public $incrementing = false;
    protected $appends = ['pas_foto', 'transkrip_nilai'];
    public $with = ['pendaftar'];

    public function beasiswa()
    {
        return $this->belongsTo('App\Beasiswa', 'id_beasiswa', 'id');
    }

    public function pendaftar()
    {
        return $this->belongsTo('App\User', 'username', 'username');
    }

    public function getPasFotoAttribute()
    {
        $factory = App::make(Firebase\Factory::class);
        $storage = $factory->createStorage();
        $bucket = $storage->getBucket();
        $pas_foto_pendaftar_file_name = 'pas_foto/pas_foto_' . $this->id . '.png';
        $pas_foto_pendaftar = $bucket->object($pas_foto_pendaftar_file_name);
        if ($pas_foto_pendaftar->exists()) {
            return $pas_foto_pendaftar->signedUrl(new \DateTime('tomorrow'));
        }
        return "https://dummyimage.com/400x400/000/fff&text={$this->username}";
    }

    public function getTranskripNilaiAttribute()
    {
        $factory = App::make(Firebase\Factory::class);
        $storage = $factory->createStorage();
        $bucket = $storage->getBucket();
        $transkrip_nilai_pendaftar_file_name = 'transkrip_nilai/transkrip_nilai_' . $this->id . '.png';
        $transkrip_nilai_pendaftar = $bucket->object($transkrip_nilai_pendaftar_file_name);
        if ($transkrip_nilai_pendaftar->exists()) {
            return $transkrip_nilai_pendaftar->signedUrl(new \DateTime('tomorrow'));
        }
        return "https://dummyimage.com/400x400/000/fff&text={$this->username}";
    }
}
