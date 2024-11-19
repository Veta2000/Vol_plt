<?php

require_once 'ValidatorInterface.php';

class DateValidator implements ValidatorInterface {
    protected $errorMessage = '';

    public function validate($data): bool {
        $d = DateTime::createFromFormat('Y-m-d', $data);
        if (!($d && $d->format('Y-m-d') === $data)) {
            $this->errorMessage = 'Неверный формат даты. Используйте YYYY-MM-DD.';
            return false;
        }
        return true;
    }

    public function getErrorMessage(): string {
        return $this->errorMessage;
    }
}
?>
