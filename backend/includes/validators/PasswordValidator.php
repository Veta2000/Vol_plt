<?php

require_once 'ValidatorInterface.php';

class PasswordValidator implements ValidatorInterface {
    protected $errorMessage = '';

    public function validate($data): bool {
        if (strlen($data) < 10 ||
            !preg_match('/[A-Z]/', $data) ||
            !preg_match('/[a-z]/', $data) ||
            !preg_match('/\d/', $data) ||
            !preg_match('/[\W_]/', $data)) {
            $this->errorMessage = 'Пароль должен содержать более 10 символов, включать буквы верхнего и нижнего регистра, цифры и спецсимволы.';
            return false;
        }
        return true;
    }

    public function getErrorMessage(): string {
        return $this->errorMessage;
    }
}
?>
