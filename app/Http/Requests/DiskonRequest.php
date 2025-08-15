<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiskonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ubah ini agar request diizinkan
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'kode_kupon' => [
            'required',
            Rule::unique('diskons', 'kode_kupon')->ignore($this->diskon)
        ],
        'tipe_diskon' => 'required|in:persen,nominal',
        'jumlah_diskon' => 'required|numeric|min:1',
        'kategori_id' => 'nullable|exists:kategoris,id',
        'produk_id' => 'nullable|exists:produks,id',
        'minimal_belanja' => 'nullable|numeric',
        'tanggal_kadaluarsa' => 'nullable|date'
        ];

    }
}
