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
        $formattedName = $this->format($name); // Использует StringFormatterTrait
        $formattedDate = $this->formatDate($date); // Использует DateFormatterTrait
        return [$formattedName, $formattedDate];
    }
}
?>
