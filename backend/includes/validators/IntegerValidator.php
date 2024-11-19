<?php

require_once 'ValidatorInterface.php';

class IntegerValidator implements ValidatorInterface {
    protected $errorMessage = '';

    public function validate($data): bool {
        if (!filter_var($data, FILTER_VALIDATE_INT)) {
            $this->errorMessage = 'Должно быть целым числом.';
            return false;
        }
        return true;
    }

    public function getErrorMessage(): string {
        return $this->errorMessage;
    }
}
?>
