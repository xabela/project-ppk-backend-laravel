<?php

namespace App\Http\Controllers;

use App\Beasiswa;
use App\Http\Requests\PendaftaranRequest;
use App\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Kreait\Firebase;

class PendaftaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->loggedin_role === 1) {
            return response()->json();
        }

        $pendaftarans = Pendaftaran::where('username', $request->loggedin_username)->with('beasiswa')->orderBy('created_at')->get();
        return response()->json($pendaftarans);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PendaftaranRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PendaftaranRequest $request, $id_beasiswa)
    {
        $beasiswa = Beasiswa::where('id', $id_beasiswa)->first();
        if (!$beasiswa) {
            return abort(404, "Beasiswa tidak ditemukan");
        }

        $registered = Pendaftaran::where([
            'id_beasiswa' => $id_beasiswa,
            'username' => request()->loggedin_username,
        ])->first();

        if ($registered) {
            return abort(419, "Anda telah terdaftar dalam beasiswa ini");
        }

        try {
            $pendaftaran = new Pendaftaran();

            $factory = App::make(Firebase\Factory::class);
            $storage = $factory->createStorage();
            $bucket = $storage->getBucket();
            $pas_foto_pendaftar = $request->file('pas_foto_pendaftar');
            if ($pas_foto_pendaftar) {
                $pas_foto_pendaftar_file_name = 'pas_foto/pas_foto_' . $pendaftaran->id . '.png';
                $bucket->upload(file_get_contents($pas_foto_pendaftar), [
                    'name' => $pas_foto_pendaftar_file_name,
                ]);
            }
            $transkrip_nilai_pendaftar = $request->file('transkrip_nilai_pendaftar');
            if ($transkrip_nilai_pendaftar) {
                $transkrip_nilai_pendaftar_file_name = 'transkrip_nilai/transkrip_nilai_' . $pendaftaran->id . '.png';
                $bucket->upload(file_get_contents($transkrip_nilai_pendaftar), [
                    'name' => $transkrip_nilai_pendaftar_file_name,
                ]);
            }

            $pendaftaran->username = request()->loggedin_username;
            $pendaftaran->id_beasiswa = $id_beasiswa;
            $pendaftaran->alamat = $request->alamat_pendaftar;
            $pendaftaran->nomor_telepon = $request->no_telepon_pendaftar;
            $pendaftaran->ipk = $request->ipk_pendaftar;
            $pendaftaran->jurusan = $request->jurusan_pendaftar;
            $pendaftaran->fakultas = $request->fakultas_pendaftar;
            $pendaftaran->universitas = $request->universitas_pendaftar;

            $pendaftaran->save();
            return response()->json($pendaftaran);
        } catch (\Exception $e) {
            return abort(500, "Kesalahan operasi pada server");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $withs = ['beasiswa'];
        if (request()->loggedin_role === 1) {
            $withs[] = 'pendaftar';
        }
        $pendaftaran = Pendaftaran::where('id', $id)->with($withs)->first();

        if (!$pendaftaran) {
            return abort(404, "Data pendaftaran tidak ditemukan");
        }
        if (request()->loggedin_role != 1 && request()->loggedin_username != $pendaftaran->username) {
            return abort(403, "Akses tidak diizinkan");
        }

        return response()->json($pendaftaran);
    }

    public function update(PendaftaranRequest $request, $id)
    {
        $pendaftaran = Pendaftaran::where('id', $id)->first();

        if (!$pendaftaran) {
            return abort(404, 'Data tidak ditemukan');
        }
        $pendaftaran->verifikasi = $request->verifikasi_pendaftar;
        $pendaftaran->save();

        return response()->json($pendaftaran);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pendaftaran = Pendaftaran::where('id', $id)->first();

        if (!$pendaftaran) {
            return abort(404, "Data pendaftaran tidak ditemukan");
        }
        if (request()->loggedin_username != $pendaftaran->username) {
            return abort(403, "Akses tidak diizinkan");
        }

        $factory = App::make(Firebase\Factory::class);
        $storage = $factory->createStorage();
        $bucket = $storage->getBucket();
        $pas_foto_pendaftar = $bucket->object("pas_foto/pas_foto_{$pendaftaran->id}.png");
        if ($pas_foto_pendaftar->exists()) {
            $pas_foto_pendaftar->delete();
        }
        $transkrip_nilai_pendaftar = $bucket->object("transkrip_nilai/transkrip_nilai_{$pendaftaran->id}.png");
        if ($transkrip_nilai_pendaftar->exists()) {
            $transkrip_nilai_pendaftar->delete();
        }

        $pendaftaran->delete();
        return response()->json();
    }
}
