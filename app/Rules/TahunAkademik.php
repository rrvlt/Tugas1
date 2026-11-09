<?php
namespace App\Rules;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
class TahunAkademik implements ValidationRule {
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (!is_string($value) || !preg_match('/^(20\d{2})\/(20\d{2})$/',$value,$m) || (int)$m[2] !== (int)$m[1]+1)
            $fail('Tahun akademik harus berurutan, misalnya 2026/2027.');
    }
}
