<?php

namespace App\Http\Controllers;

use App\Beasiswa;
use App\Http\Requests\BeasiswaRequest;
use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $beasiswa = Beasiswa::whereRaw('1 = 1');

        if ($request->nama_beasiswa) {
            $beasiswa = $beasiswa->where('nama', 'LIKE', '%' . $request->nama_beasiswa . '%');
        }
        if ($request->penyelenggara) {
            $beasiswa = $beasiswa->where('penyelenggara', $request->penyelenggara);
        }

        $beasiswa = $beasiswa->orderBy('created_at', 'DESC')->take(100)->get();

        return response()->json($beasiswa);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\BeasiswaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BeasiswaRequest $request)
    {
        $beasiswa = new Beasiswa();
        $beasiswa->nama = $request->nama_beasiswa;
        $beasiswa->deskripsi = $request->deskripsi_beasiswa;
        $beasiswa->kuota = $request->kuota_beasiswa;
        $beasiswa->tanggal_mulai = $request->tanggal_mulai;
        $beasiswa->tanggal_selesai = $request->tanggal_selesai;
        $beasiswa->penyelenggara = request()->loggedin_username;
        $beasiswa->save();

        return response()->json($beasiswa);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $beasiswa = Beasiswa::where('id', $id)->first();

        if (!$beasiswa) {
            return abort(404, "Beasiswa tidak ditemukan");
        }

        if (request()->loggedin_role === 1 && $beasiswa->penyelenggara === request()->loggedin_username) {
            $beasiswa = $beasiswa->makeVisible('pendaftaran');
        }

        return response()->json($beasiswa);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\BeasiswaRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BeasiswaRequest $request, $id)
    {
        $beasiswa = Beasiswa::where('id', $id)->first();

        if (!$beasiswa) {
            return abort(404, "Beasiswa tidak ditemukan");
        }
        if (request()->loggedin_username != $beasiswa->penyelenggara) {
            return abort(403, "Akses tidak diizinkan");
        }

        $beasiswa->nama = $request->nama_beasiswa;
        $beasiswa->deskripsi = $request->deskripsi_beasiswa;
        $beasiswa->kuota = $request->kuota_beasiswa;
        $beasiswa->tanggal_mulai = $request->tanggal_mulai;
        $beasiswa->tanggal_selesai = $request->tanggal_selesai;
        $beasiswa->save();

        return response()->json($beasiswa);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $beasiswa = Beasiswa::where('id', $id)->first();

        if ($beasiswa) {
            if (request()->loggedin_username != $beasiswa->penyelenggara) {
                return abort(403, "Akses tidak diizinkan");
            }

            $beasiswa->delete();
            return response()->json();
        }
        return abort(404, "Beasiswa tidak ditemukan");
    }
}
