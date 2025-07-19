<?php

namespace Framework;

class Validator
{
    private array $rules;
    protected array $errors = [];

    public function __construct()
    {
        $this->rules = [
            'register' => [
                'name' => self::nameRules(),
                'email' => self::emailRules(),
                'password' => self::passwordRules(),
            ],
            'login' => [
                'email' => self::emailRules(),
                'password' => self::passwordRules(),
            ]
        ];
    }

    public static function nameRules(): array
    {
        return ['required', 'max:255', 'alpha_spaces'];
    }

    public static function emailRules(): array
    {
        return ['required', 'email', 'max:255'];
    }

    public static function passwordRules(): array
    {
        return ['required', 'min:8', 'password_complexity'];
    }

    public static function messageRules(): array
    {
        return ['required', 'min:10', 'max:1000'];
    }

    public static function fileRules(): array
    {
        return ['file', 'max_size:2048', 'mimes:jpg,png,pdf'];
    }

    public function validate(array $data, string $ruleGroup): array | bool
    {
        $this->errors = [];

        foreach ($this->rules[$ruleGroup] as $field => $validators) {
            $value = $data[$field] ?? null;

            foreach ($validators as $validator) {
                $this->applyRule($field, $value, $validator);
            }
        }

        return empty($this->errors) ? true : $this->errors;
    }

    private function applyRule(string $field, $value, string $validator): void
    {
        $errors = [];
        $parts = explode(':', $validator, 2);
        $rule = $parts[0];
        $param = $parts[1] ?? null;

        // Required
        if ($rule === 'required' && empty(trim((string)$value))) {

            $errors[$field][] = "Поле $field обязательно для заполнения";
        }


        // Email
        if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$field][] = "Некорректный формат email";
        }

        // Min length
        if ($rule === 'min') {
            $cleanValue = (string)($value ?? '');
            if (strlen($cleanValue) < (int)$param) {
                $errors[$field][] = "Минимальная длина: $param символов";
            }
        }

        // Max length
        if ($rule === 'max') {
            $cleanValue = (string)($value ?? '');
            if (strlen($cleanValue) > (int)$param) {

                $errors[$field][] = "Максимальная длина: $param символов";
            }
        }

        // Alpha & spaces
        if ($rule === 'alpha_spaces') {
            $cleanValue = (string)($value ?? '');
            if (!preg_match('/^[\p{L}\s]+$/u', $cleanValue)) {
                $errors[$field][] = "Допустимы только буквы";
            }
        }

        // Password complexity
        if ($rule === 'password_complexity') {
            $cleanValue = (string)($value ?? '');
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $cleanValue)) {
                $errors[$field][] = "Пароль должен содержать цифры, заглавные и строчные буквы  ";
            }
        }

        if (!empty($errors)) {
            $this->errors[$field] = array_merge(
                $this->errors[$field] ?? [],
                $errors[$field]
            );
        }
    }
}
