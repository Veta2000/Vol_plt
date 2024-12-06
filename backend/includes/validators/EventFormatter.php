<?php

require_once 'StringFormatterTrait.php';
require_once 'DateFormatterTrait.php';

class EventFormatter {
    use StringFormatterTrait, DateFormatterTrait {
        // Разрешение конфликта
        StringFormatterTrait::format insteadof DateFormatterTrait;
        DateFormatterTrait::format as formatDate;
    }

    public function formatEvent($name, $date) {
        $formattedName = $this->format($name); // StringFormatterTrait
        $formattedDate = $this->formatDate($date); // DateFormatterTrait
        return [$formattedName, $formattedDate];
    }
}
?>
