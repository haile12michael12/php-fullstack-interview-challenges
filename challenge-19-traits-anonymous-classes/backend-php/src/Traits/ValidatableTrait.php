<?php

namespace App\Traits;

trait ValidatableTrait
{
    protected array $errors = [];
    protected array $rules = [];

    public function setValidationRules(array $rules): void
    {
        $this->rules = $rules;
    }

    public function validate(array $data): bool
    {
        $this->errors = [];
        
        foreach ($this->rules as $field => $rules) {
            $value = $data[$field] ?? null;
            
            foreach ($rules as $rule) {
                if (!$this->applyRule($field, $value, $rule)) {
                    break; // Stop on first failure for this field
                }
            }
        }
        
        return empty($this->errors);
    }

    protected function applyRule(string $field, $value, string $rule): bool
    {
        switch ($rule) {
            case 'required':
                if ($value === null || $value === '') {
                    $this->errors[$field][] = "{$field} is required";
                    return false;
                }
                return true;
                
            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "{$field} must be a valid email";
                    return false;
                }
                return true;
                
            case 'numeric':
                if ($value && !is_numeric($value)) {
                    $this->errors[$field][] = "{$field} must be numeric";
                    return false;
                }
                return true;
                
            default:
                // Handle custom rules
                if (method_exists($this, $rule)) {
                    return $this->$rule($field, $value);
                }
                return true;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}