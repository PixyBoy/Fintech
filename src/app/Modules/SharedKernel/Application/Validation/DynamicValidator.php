<?php

namespace App\Modules\SharedKernel\Application\Validation;

class DynamicValidator
{
    public static function rules(array $schema): array
    {
        $rules = [];
        foreach ($schema as $field => $rule) {
            if (is_array($rule)) {
                $rule = implode('|', $rule);
            }
            $rules[$field] = $rule;
        }

        return $rules;
    }
}
