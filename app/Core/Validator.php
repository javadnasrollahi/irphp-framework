<?php
namespace App\Core;

class Validator
{
    protected array $data;
    protected array $rules;
    protected array $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data  = $data;
        $this->rules = $rules;
    }

    public static function make(array $data, array $rules): self
    {
        return (new self($data, $rules))->run();
    }

    public function run(): self
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);
                $this->applyRule($field, $value, $name, $param);
            }
        }

        return $this;
    }

    protected function applyRule(string $field, $value, string $rule, ?string $param): void
    {
        switch ($rule) {
            case 'required':
                if ($value === null || $value === '') {
                    $this->addError($field, "فیلد {$field} الزامی است.");
                }
                break;
            case 'email':
                if ($value && ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "فیلد {$field} باید یک ایمیل معتبر باشد.");
                }
                break;
            case 'numeric':
                if ($value !== null && $value !== '' && ! is_numeric($value)) {
                    $this->addError($field, "فیلد {$field} باید عددی باشد.");
                }
                break;
            case 'min':
                if ($value !== null && mb_strlen((string) $value) < (int) $param) {
                    $this->addError($field, "فیلد {$field} باید حداقل {$param} کاراکتر باشد.");
                }
                break;
            case 'max':
                if ($value !== null && mb_strlen((string) $value) > (int) $param) {
                    $this->addError($field, "فیلد {$field} باید حداکثر {$param} کاراکتر باشد.");
                }
                break;
            case 'in':
                $allowed = explode(',', (string) $param);
                if ($value !== null && ! in_array($value, $allowed, true)) {
                    $this->addError($field, "مقدار فیلد {$field} نامعتبر است.");
                }
                break;
        }
    }

    protected function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function fails(): bool
    {
        return count($this->errors) > 0;
    }

    public function passes(): bool
    {
        return ! $this->fails();
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
