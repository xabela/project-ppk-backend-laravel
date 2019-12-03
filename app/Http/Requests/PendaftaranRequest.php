<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $method = strtoupper(request()->method());
        return request()->loggedin_role === ($method === 'PATCH' || $method === 'PUT' ? 1 : 0);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = strtoupper(request()->method());
        if ($method === 'PATCH' || $method === 'PUT') {
            return [
                'verifikasi_pendaftar' => 'required|in:0,1,2',
            ];
        } else {
            return [
                'alamat_pendaftar' => 'required|string|min:3|max:100',
                'no_telepon_pendaftar' => 'required|string|min:3|max:20',
                'ipk_pendaftar' => 'required|numeric|between:0.00,4.00',
                'transkrip_nilai_pendaftar' => 'sometimes|required|image|mimes:png|max:4096',
                'pas_foto_pendaftar' => 'sometimes|required|image|mimes:png|max:4096',
                'jurusan_pendaftar' => 'required|string|min:3|max:255',
                'fakultas_pendaftar' => 'required|string|min:3|max:255',
                'universitas_pendaftar' => 'required|string|min:3|max:255',
            ];
        }
    }
}
