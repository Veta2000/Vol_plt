<?php


require_once 'StringValidator.php';
require_once 'IntegerValidator.php';
require_once 'DateValidator.php';
require_once 'EmailValidator.php';
require_once 'PasswordValidator.php';

class Validator {
    protected $validators = [];
    protected $errors = [];

    public function addValidator(string $field, string $type) {
        $validatorClass = ucfirst($type) . 'Validator';
        if (class_exists($validatorClass)) {
            $this->validators[$field] = new $validatorClass();
        } else {
            throw new Exception("Валидатор для типа данных '$type' не найден.");
        }
    }

    /**
     * @param array $data Массив данных для проверки.
     * @return bool Возвращает true, если все данные валидны, иначе false.
     */
    public function validate(array $data): bool {
        $this->errors = [];
        foreach ($this->validators as $field => $validator) {
            if (isset($data[$field])) {
                if (!$validator->validate($data[$field])) {
                    $this->errors[$field] = $validator->getErrorMessage();
                }
            } else {
                $this->errors[$field] = 'Поле отсутствует.';
            }
        }
        return empty($this->errors);
    }

    public function getErrors(): array {
        return $this->errors;
    }
}
?>