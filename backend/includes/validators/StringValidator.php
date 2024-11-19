<?php

require_once 'ValidatorInterface.php';

class StringValidator implements ValidatorInterface {
    protected $errorMessage = '';

    public function validate($data): bool {
        if (!is_string($data) || empty(trim($data))) {
            $this->errorMessage = 'Должно быть непустой строкой.';
            return false;
        }
        return true;
    }

    public function getErrorMessage(): string {
        return $this->errorMessage;
    }
}
?>
