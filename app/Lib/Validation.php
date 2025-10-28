<?php
declare(strict_types=1);

namespace App\Lib;

final class Validation
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, array<int, string>> $rules
     * @return array<string, string>
     */
    public static function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $fieldRules) {
            $value = trim((string) ($data[$field] ?? ''));
            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && $value === '') {
                    $errors[$field] = 'Bu alan zorunludur.';
                }
                if ($rule === 'email' && $value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
                    $errors[$field] = 'Geçerli bir e-posta giriniz.';
                }
                if (str_starts_with($rule, 'min:')) {
                    $min = (int) substr($rule, 4);
                    if (strlen($value) < $min) {
                        $errors[$field] = 'En az ' . $min . ' karakter olmalıdır.';
                    }
                }
            }
        }

        return $errors;
    }
}
