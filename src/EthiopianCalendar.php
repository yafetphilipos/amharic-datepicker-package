<?php

namespace Yafet\AmharicDatepicker;

use DateTime;

class EthiopianCalendar
{
    protected float $jdEpoch = 1724220.5;
    
    protected array $daysPerMonth = [30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30, 5];

    public function isLeapYear(int $year): bool
    {
        $y = $year + ($year < 0 ? 1 : 0);
        return $y % 4 === 3 || $y % 4 === -1;
    }

    public function daysInMonth(int $year, int $month): int
    {
        return $this->daysPerMonth[$month - 1] + ($month === 13 && $this->isLeapYear($year) ? 1 : 0);
    }

    /**
     * Convert Ethiopian date to Julian Date.
     */
    public function toJD(int $year, int $month, int $day): float
    {
        $y = $year;
        if ($y < 0) {
            $y++;
        }
        return $day + ($month - 1) * 30 + ($y - 1) * 365 + floor($y / 4) + $this->jdEpoch - 1;
    }

    /**
     * Convert Julian Date to Ethiopian date.
     */
    public function fromJD(float $jd): array
    {
        $z = floor($jd + 0.5);
        $c = $z - $this->jdEpoch;
        $year = floor(($c - floor(($c + 366) / 1461)) / 365) + 1;
        if ($year <= 0) {
            $year--;
        }
        
        $startOfYearJD = $this->toJD($year, 1, 1);
        $c = floor($jd + 0.5) - $startOfYearJD;
        $month = floor($c / 30) + 1;
        $day = floor($c - ($month - 1) * 30) + 1;
        
        return [
            'year' => (int)$year,
            'month' => (int)$month,
            'day' => (int)$day,
        ];
    }

    /**
     * Convert Gregorian DateTime to Ethiopian date array.
     */
    public function fromGregorian($date): array
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        
        $jd = $this->gregorianToJD($date->format('Y'), $date->format('n'), $date->format('j'));
        return $this->fromJD($jd);
    }

    /**
     * Convert Ethiopian date to Gregorian DateTime.
     * 
     * Supports:
     * - toGregorian(2016, 8, 28)
     * - toGregorian("2016-08-28")
     * - toGregorian("28/08/2016")
     */
    public function toGregorian($year, $month = null, $day = null): DateTime
    {
        if (is_string($year) && $month === null && $day === null) {
            // Handle string input like "2016-08-28" or "28/08/2016"
            $parts = preg_split('/[\-\/\. ]/', $year);
            
            if (count($parts) === 3) {
                // Determine if it's Y-m-d or d-m-Y
                if (strlen($parts[0]) === 4) {
                    $year = (int)$parts[0];
                    $month = (int)$parts[1];
                    $day = (int)$parts[2];
                } else {
                    $year = (int)$parts[2];
                    $month = (int)$parts[1];
                    $day = (int)$parts[0];
                }
            } else {
                throw new \InvalidArgumentException("Invalid date format string provided.");
            }
        }

        $jd = $this->toJD((int)$year, (int)$month, (int)$day);
        return $this->jdToGregorian($jd);
    }

    protected function gregorianToJD(int $year, int $month, int $day): float
    {
        if ($month < 3) {
            $year--;
            $month += 12;
        }
        $a = floor($year / 100);
        $b = 2 - $a + floor($a / 4);
        return floor(365.25 * ($year + 4716)) + floor(30.6001 * ($month + 1)) + $day + $b - 1524.5;
    }

    protected function jdToGregorian(float $jd): DateTime
    {
        $z = floor($jd + 0.5);
        $a = $z;
        if ($z >= 2299161) {
            $alpha = floor(($z - 1867216.25) / 36524.25);
            $a = $z + 1 + $alpha - floor($alpha / 4);
        }
        $b = $a + 1524;
        $c = floor(($b - 122.1) / 365.25);
        $d = floor(365.25 * $c);
        $e = floor(($b - $d) / 30.6001);
        $day = $b - $d - floor(30.6001 * $e);
        $month = $e < 14 ? $e - 1 : $e - 13;
        $year = $month > 2 ? $c - 4716 : $c - 4715;
        
        $date = new DateTime();
        $date->setDate((int)$year, (int)$month, (int)$day);
        $date->setTime(0, 0, 0);
        return $date;
    }
}
