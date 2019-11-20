<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BeasiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama_beasiswa' => 'required|string|min:3|max:100',
            'deksripsi_beasiswa' => 'required|string|min:1|max:500',
            'kuota_beasiswa' => 'required|int',
            'tanggal_mulai' => 'required|date|after_or_equal:today', // after beeel, lul :v, after or equal wingi yoopo?
            'tanggal_selesai' => 'required|date|after:tanggal_mulai', // iki after tomorrow ngono a kan kependeken
        ];
    }
}
