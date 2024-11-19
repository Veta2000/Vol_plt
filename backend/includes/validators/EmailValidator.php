<?php

require_once 'ValidatorInterface.php';

class EmailValidator implements ValidatorInterface {
    protected $errorMessage = '';

    public function validate($data): bool {
        if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
            $this->errorMessage = 'Неверный формат электронной почты.';
            return false;
        }
        return true;
    }

    public function getErrorMessage(): string {
        return $this->errorMessage;
    }
}
?>
