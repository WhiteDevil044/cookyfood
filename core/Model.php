<?php

namespace Framework;

use Framework\Validator;


abstract class Model
{

    protected string $table;
    public bool $timestamps = true;
    protected array $loaded = [];
    protected array $fillable = [];
    public array $attributes = [];
    protected array $rules = [];
    protected array $labels = [];
    protected array $errors = [];

    public function save(): false|string
    {
        $attributes = $this->attributes;
        foreach ($attributes as $k => $v) {
            if (!in_array($k, $this->fillable)) {
                unset($attributes[$k]);
            }
        }

        $fields_keys = array_keys($attributes);
        $fields = array_map(fn($field) => "`{$field}`", $fields_keys);
        $fields = implode(',', $fields);
        if ($this->timestamps) {
            $fields .= ', `created_at`, `updated_at`';
        }

        $placeholders = array_map(fn($field) => ":{$field}", $fields_keys);
        $placeholders = implode(',', $placeholders);
        if ($this->timestamps) {
            $placeholders .= ', :created_at, :updated_at';
            $attributes['created_at'] = date("Y-m-d H:i:s");
            $attributes['updated_at'] = date("Y-m-d H:i:s");
        }

        $query = "insert into {$this->table} ($fields) values ($placeholders)";
        db()->query($query, $attributes);
        return db()->getInsertId();
    }

    public function loadData(): void
    {
        $data = request()->getData();
        foreach ($this->loaded as $field) {
            if (isset($data[$field])) {
                $this->attributes[$field] = $data[$field];
            } else {
                $this->attributes[$field] = '';
            }
        }
    }

    public function validate($data = [], $ruleGroup = ''): bool
    {
        if (empty($data)) {
            $data = $this->attributes;
        }

        $validator = new Validator();
        $result = $validator->validate($data, $ruleGroup);

        if ($result === true) {
            return true;
        } else {
            $this->errors = is_array($result) ? $result : [];
            return false;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function listErrors(): string
    {
        $output = '<ul class="list-unstyled">';
        foreach ($this->errors as $field_errors) {
            foreach ($field_errors as $error) {
                $output .= "<li>$error</li>";
            }
        }
        $output .= "</ul>";
        return $output;
    }
}
