<?php

namespace App\Modules\SharedKernel\Application\Validation;

class DynamicValidator
{
    public static function rules(array $schema): array
    {
        if (isset($schema['validation']) && is_array($schema['validation'])) {
            return $schema['validation'];
        }

        $rules = [];
        $fields = $schema['fields'] ?? [];
        foreach ($fields as $item) {
            if (!is_array($item) || !isset($item['key'])) {
                continue;
            }
            $key = $item['key'];

            $val = $item['validation'] ?? (($item['required'] ?? false) ? 'required' : '');

            if (is_array($val)) {
                $val = implode('|', $val);
            }

            $rules[$key] = trim((string)$val, '|');
        }

        return $rules;
    }
}
