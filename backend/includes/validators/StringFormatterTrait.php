<?php

trait StringFormatterTrait {
    public function format($string) {
        return strtoupper($string); // перевод строки в верхний регистр
    }
}
?>