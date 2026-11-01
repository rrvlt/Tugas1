<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class MataKuliahRequest extends FormRequest
{
    // Modul tugas lokal belum memakai login; tambahkan policy saat multi-user.
    public function authorize(): bool { return true; }
    protected function prepareForValidation(): void
    {
        $values = [];
        foreach (['kode', 'nama', 'sks', 'semester', 'deskripsi'] as $field) {
            if (is_string($this->input($field))) $values[$field] = trim($this->input($field));
        }
        if (isset($values['email'])) $values['email'] = strtolower($values['email']);
        if (isset($values['kode'])) $values['kode'] = strtoupper($values['kode']);
        $this->merge($values);
    }
    public function rules(): array { return [
            'kode' => ['required','string','max:20','regex:/^[A-Za-z0-9.-]+$/',Rule::unique('mata_kuliahs','kode')->ignore($this->route('mataKuliah'))],
            'nama' => ['required','string','max:150'],
            'sks' => ['required','integer','between:1,6'],
            'semester' => ['required','integer','between:1,14'],
            'deskripsi' => ['nullable','string','max:2000']
    ]; }
    public function messages(): array { return [
        'required' => ':attribute wajib diisi.', 'unique' => ':attribute sudah digunakan.',
        'email' => 'Format email tidak valid.', 'integer' => ':attribute harus bilangan bulat.',
        'between' => ':attribute harus antara :min dan :max.',
        'max' => ':attribute maksimal :max karakter.', 'regex' => ':attribute hanya boleh huruf, angka, titik, dan tanda minus.',
        'string' => ':attribute harus berupa teks.',
    ]; }
    public function attributes(): array { return ['kode' => 'Kode mata kuliah', 'nama' => 'Nama mata kuliah', 'sks' => 'SKS', 'semester' => 'Semester', 'deskripsi' => 'Deskripsi']; }
}
