<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class MahasiswaRequest extends FormRequest
{
    // Modul tugas lokal belum memakai login; tambahkan policy saat multi-user.
    public function authorize(): bool { return true; }
    protected function prepareForValidation(): void
    {
        $values = [];
        foreach (['nim', 'nama', 'email', 'program_studi', 'angkatan', 'status'] as $field) {
            if (is_string($this->input($field))) $values[$field] = trim($this->input($field));
        }
        if (isset($values['email'])) $values['email'] = strtolower($values['email']);
        if (isset($values['kode'])) $values['kode'] = strtoupper($values['kode']);
        $this->merge($values);
    }
    public function rules(): array { return [
            'profil' => ['sometimes','array:tempat_lahir,tanggal_lahir,telepon,alamat'],
            'profil.tempat_lahir'=>['nullable','string','max:100'], 'profil.tanggal_lahir'=>['nullable','date','before_or_equal:today'],
            'profil.telepon'=>['nullable','string','max:20'], 'profil.alamat'=>['nullable','string','max:2000'],
            'krs_enabled'=>['sometimes','boolean'], 'krs_mode'=>['sometimes','in:sync,add'],
            'tahun_akademik'=>['required_if:krs_enabled,1','nullable',new \App\Rules\TahunAkademik],
            'semester_krs'=>['required_if:krs_enabled,1','nullable','in:ganjil,genap'],
            'mata_kuliah_ids'=>['sometimes','array'], 'mata_kuliah_ids.*'=>['integer','distinct','exists:mata_kuliahs,id'],
            'status' => ['required', Rule::in(['aktif', 'cuti', 'nonaktif'])],
            'nim' => ['required','string','max:20','regex:/^[A-Za-z0-9.-]+$/',Rule::unique('mahasiswas','nim')->ignore($this->route('mahasiswa'))],
            'nama' => ['required','string','max:100'],
            'email' => ['required','email','max:150',Rule::unique('mahasiswas','email')->ignore($this->route('mahasiswa'))],
            'program_studi_id' => ['required','integer','exists:program_studis,id'],
            'angkatan' => ['required','integer','between:2000,'.(date('Y') + 1)]
    ]; }
    public function messages(): array { return [
        'in' => 'Status harus Aktif, Cuti, atau Nonaktif.',
        'required' => ':attribute wajib diisi.', 'unique' => ':attribute sudah digunakan.',
        'email' => 'Format email tidak valid.', 'integer' => ':attribute harus bilangan bulat.',
        'between' => ':attribute harus antara :min dan :max.',
        'max' => ':attribute maksimal :max karakter.', 'regex' => ':attribute hanya boleh huruf, angka, titik, dan tanda minus.',
        'string' => ':attribute harus berupa teks.',
    ]; }
    public function attributes(): array { return ['nim' => 'NIM', 'nama' => 'Nama mahasiswa', 'email' => 'Email', 'program_studi_id' => 'Program studi', 'angkatan' => 'Angkatan']; }
}
