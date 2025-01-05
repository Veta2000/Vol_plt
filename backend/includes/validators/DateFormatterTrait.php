<?php

trait DateFormatterTrait {
    public function format($date) {
        return date('Y-m-d', strtotime($date)); 
    }
}
?>
