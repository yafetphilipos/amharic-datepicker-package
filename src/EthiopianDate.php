<?php

namespace Yafet\AmharicDatepicker;

use ArrayAccess;

class EthiopianDate implements ArrayAccess
{
    public const MONTHS_AM = [
        1 => 'መስከረም',
        2 => 'ጥቅምት',
        3 => 'ህዳር',
        4 => 'ታህሳስ',
        5 => 'ጥር',
        6 => 'የካቲት',
        7 => 'መጋቢት',
        8 => 'ሚያዝያ',
        9 => 'ግንቦት',
        10 => 'ሰኔ',
        11 => 'ሐምሌ',
        12 => 'ነሐሴ',
        13 => 'ጳጉሜ',
    ];

    public const MONTHS_AM_SHORT = [
        1 => 'መስከ',
        2 => 'ጥቅም',
        3 => 'ህዳር',
        4 => 'ታህሳ',
        5 => 'ጥር',
        6 => 'የካቲ',
        7 => 'መጋቢ',
        8 => 'ሚያዝ',
        9 => 'ግንቦ',
        10 => 'ሰኔ',
        11 => 'ሐምሌ',
        12 => 'ነሐሴ',
        13 => 'ጳጉሜ',
    ];

    public function __construct(
        public int $year,
        public int $month,
        public int $day
    ) {}

    /**
     * Format the date using a format string.
     * 
     * Supported formats:
     * - d: Day of the month, 2 digits with leading zeros (01 to 30)
     * - j: Day of the month without leading zeros (1 to 30)
     * - m: Numeric representation of a month, with leading zeros (01 to 13)
     * - n: Numeric representation of a month, without leading zeros (1 to 13)
     * - F: Full textual representation of a month in Amharic (መስከረም to ጳጉሜ)
     * - M: Short textual representation of a month in Amharic
     * - Y: A full numeric representation of a year, 4 digits
     */
    public function format(string $format = 'd/m/Y'): string
    {
        $replacements = [
            'd' => str_pad($this->day, 2, '0', STR_PAD_LEFT),
            'j' => $this->day,
            'm' => str_pad($this->month, 2, '0', STR_PAD_LEFT),
            'n' => $this->month,
            'F' => self::MONTHS_AM[$this->month] ?? '',
            'M' => self::MONTHS_AM_SHORT[$this->month] ?? '',
            'Y' => $this->year,
        ];

        $result = '';
        $escaped = false;
        
        for ($i = 0; $i < strlen($format); $i++) {
            $char = $format[$i];
            
            if ($char === '\\' && !$escaped) {
                $escaped = true;
                continue;
            }

            if ($escaped) {
                $result .= $char;
                $escaped = false;
            } else {
                $result .= $replacements[$char] ?? $char;
            }
        }

        return $result;
    }

    public function toArray(): array
    {
        return [
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
        ];
    }

    public function __toString(): string
    {
        return $this->format('d/m/Y');
    }

    // ArrayAccess Implementation for backward compatibility
    public function offsetExists($offset): bool
    {
        return in_array($offset, ['year', 'month', 'day']);
    }

    public function offsetGet($offset): mixed
    {
        return match ($offset) {
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            default => null,
        };
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset === 'year') $this->year = $value;
        if ($offset === 'month') $this->month = $value;
        if ($offset === 'day') $this->day = $value;
    }

    public function offsetUnset($offset): void
    {
        // Not implemented
    }
}
