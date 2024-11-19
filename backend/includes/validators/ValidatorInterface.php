<?php

interface ValidatorInterface {

    public function validate($data): bool;

    public function getErrorMessage(): string;
}
?>
