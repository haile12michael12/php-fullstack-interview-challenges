<?php

declare(strict_types=1);

namespace SharedBackend\Http;

use Respect\Validation\Validator as RespectValidator;
use Respect\Validation\Exceptions\ValidationException;

/**
 * Advanced validation system with custom rules and error handling
 */
class Validator
{
    private array $errors = [];
    private array $rules = [];
    private array $messages = [];

    public function validate(array $data, array $rules): array
    {
        $this->errors = [];
        $this->rules = $rules;
        $validated = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            
            try {
                if (is_string($rule)) {
                    $this->validateWithString($field, $value, $rule);
                } elseif (is_array($rule)) {
                    $this->validateWithArray($field, $value, $rule);
                }
                
                $validated[$field] = $value;
            } catch (ValidationException $e) {
                $this->errors[$field] = $this->getErrorMessage($field, $e);
            }
        }

        if (!empty($this->errors)) {
            throw new ValidationException('Validation failed', 422, $this->errors);
        }

        return $validated;
    }

    private function validateWithString(string $field, mixed $value, string $rule): void
    {
        $ruleParts = explode('|', $rule);
        
        foreach ($ruleParts as $rulePart) {
            $this->applyRule($field, $value, trim($rulePart));
        }
    }

    private function validateWithArray(string $field, mixed $value, array $rules): void
    {
        foreach ($rules as $rule) {
            if (is_string($rule)) {
                $this->applyRule($field, $value, $rule);
            } elseif (is_array($rule)) {
                $ruleName = array_key_first($rule);
                $ruleParams = $rule[$ruleName];
                $this->applyRuleWithParams($field, $value, $ruleName, $ruleParams);
            }
        }
    }

    private function applyRule(string $field, mixed $value, string $rule): void
    {
        if (str_contains($rule, ':')) {
            [$ruleName, $params] = explode(':', $rule, 2);
            $this->applyRuleWithParams($field, $value, $ruleName, explode(',', $params));
        } else {
            $this->applyRuleWithParams($field, $value, $rule, []);
        }
    }

    private function applyRuleWithParams(string $field, mixed $value, string $ruleName, array $params): void
    {
        $validator = $this->createValidator($ruleName, $params);
        
        if (!$validator->validate($value)) {
            throw new ValidationException("Field '{$field}' failed validation rule '{$ruleName}'");
        }
    }

    private function createValidator(string $ruleName, array $params = []): RespectValidator
    {
        return match ($ruleName) {
            'required' => RespectValidator::notEmpty(),
            'email' => RespectValidator::email(),
            'url' => RespectValidator::url(),
            'alpha' => RespectValidator::alpha(),
            'numeric' => RespectValidator::numeric(),
            'integer' => RespectValidator::intVal(),
            'float' => RespectValidator::floatVal(),
            'boolean' => RespectValidator::boolVal(),
            'date' => RespectValidator::date(),
            'min' => RespectValidator::min((int)$params[0]),
            'max' => RespectValidator::max((int)$params[0]),
            'between' => RespectValidator::between((int)$params[0], (int)$params[1]),
            'length' => RespectValidator::length((int)$params[0], (int)($params[1] ?? PHP_INT_MAX)),
            'min_length' => RespectValidator::length((int)$params[0]),
            'max_length' => RespectValidator::length(0, (int)$params[0]),
            'in' => RespectValidator::in($params),
            'not_in' => RespectValidator::not(RespectValidator::in($params)),
            'regex' => RespectValidator::regex('/' . $params[0] . '/'),
            'json' => RespectValidator::json(),
            'ip' => RespectValidator::ip(),
            'uuid' => RespectValidator::uuid(),
            'file' => RespectValidator::file(),
            'image' => RespectValidator::image(),
            'mimetype' => RespectValidator::mimetype($params),
            'size' => RespectValidator::size(null, $params[0]),
            'confirmed' => RespectValidator::equals($_POST[$params[0] ?? ''] ?? ''),
            'unique' => $this->createUniqueValidator($params),
            'exists' => $this->createExistsValidator($params),
            default => throw new \InvalidArgumentException("Unknown validation rule: {$ruleName}")
        };
    }

    private function createUniqueValidator(array $params): RespectValidator
    {
        // This would need database connection to implement
        return RespectValidator::alwaysValid();
    }

    private function createExistsValidator(array $params): RespectValidator
    {
        // This would need database connection to implement
        return RespectValidator::alwaysValid();
    }

    private function getErrorMessage(string $field, ValidationException $e): string
    {
        $customMessage = $this->messages[$field] ?? null;
        
        if ($customMessage) {
            return $customMessage;
        }

        return match ($e->getMessage()) {
            default => "The {$field} field is invalid."
        };
    }

    public function setMessages(array $messages): void
    {
        $this->messages = array_merge($this->messages, $messages);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getFirstError(): ?string
    {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
}
