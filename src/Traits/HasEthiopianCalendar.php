<?php

namespace Yafet\AmharicDatepicker\Traits;

use Yafet\AmharicDatepicker\EthiopianCalendar;
use Yafet\AmharicDatepicker\EthiopianDate;

trait HasEthiopianCalendar
{
    /**
     * Get an EthiopianDate object for a given date attribute.
     * 
     * @param string $field The model attribute name (e.g., 'dob')
     * @param string|null $format Optional format string. If provided, returns a string.
     * @return EthiopianDate|string|null
     */
    public function ethiopian(string $field, ?string $format = null): EthiopianDate|string|null
    {
        $value = $this->{$field};

        if (!$value) {
            return null;
        }

        $ethDate = (new EthiopianCalendar())->fromGregorian($value);

        if ($format) {
            return $ethDate->format($format);
        }

        return $ethDate;
    }
}
