<?php

declare(strict_types=1);

namespace App\Validation;

class Validator
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, array<int, string>> $rules
     * @return array<string, string>
     */
    public function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && ($value === null || $value === '')) {
                    $errors[$field] = 'This field is required.';
                }

                if ($rule === 'email' && ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Invalid e-mail address.';
                }
            }
        }

        return $errors;
    }
}
