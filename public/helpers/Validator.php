<?php
declare(strict_types=1);

class Validator
{
    private array $errors = [];

    public function required(string $field, mixed $value): self
    {
        if ($value === null || $value === '' || (is_array($value) && empty($value))) {
            $this->errors[$field][] = "Field {$field} wajib diisi.";
        }
        return $this;
    }

    public function email(string $field, string $value): self
    {
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = "Field {$field} harus berupa email yang valid.";
        }
        return $this;
    }

    public function minLength(string $field, string $value, int $min): self
    {
        if ($value !== '' && mb_strlen($value) < $min) {
            $this->errors[$field][] = "Field {$field} minimal {$min} karakter.";
        }
        return $this;
    }

    public function maxLength(string $field, string $value, int $max): self
    {
        if ($value !== '' && mb_strlen($value) > $max) {
            $this->errors[$field][] = "Field {$field} maksimal {$max} karakter.";
        }
        return $this;
    }

    public function numeric(string $field, mixed $value): self
    {
        if ($value !== null && $value !== '' && !is_numeric($value)) {
            $this->errors[$field][] = "Field {$field} harus berupa angka.";
        }
        return $this;
    }

    public function min(string $field, float $value, float $min): self
    {
        if ($value < $min) {
            $this->errors[$field][] = "Field {$field} minimal bernilai {$min}.";
        }
        return $this;
    }

    public function max(string $field, float $value, float $max): self
    {
        if ($value > $max) {
            $this->errors[$field][] = "Field {$field} maksimal bernilai {$max}.";
        }
        return $this;
    }

    public function inArray(string $field, mixed $value, array $allowed): self
    {
        if ($value !== null && $value !== '' && !in_array($value, $allowed, true)) {
            $allowedStr = implode(', ', $allowed);
            $this->errors[$field][] = "Field {$field} harus salah satu dari: {$allowedStr}.";
        }
        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFirstError(string $field): ?string
    {
        return $this->errors[$field][0] ?? null;
    }
}
