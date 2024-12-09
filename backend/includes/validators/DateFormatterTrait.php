<?php

trait DateFormatterTrait {
    public function format($date) {
        return date('d-m-Y', strtotime($date)); 
    }
}
?>
