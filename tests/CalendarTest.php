<?php

namespace Yafet\AmharicDatepicker\Tests;

use PHPUnit\Framework\TestCase;
use Yafet\AmharicDatepicker\EthiopianCalendar;
use DateTime;

class CalendarTest extends TestCase
{
    protected EthiopianCalendar $calendar;

    protected function setUp(): void
    {
        $this->calendar = new EthiopianCalendar();
    }

    public function test_it_correctly_identifies_leap_years()
    {
        $this->assertTrue($this->calendar->isLeapYear(2011)); // 2011 is leap year in Ethiopian
        $this->assertFalse($this->calendar->isLeapYear(2012));
        $this->assertTrue($this->calendar->isLeapYear(2015));
    }

    public function test_it_converts_gregorian_to_ethiopian()
    {
        // 2024-05-06 Gregorian is 2016-08-28 Ethiopian
        $date = new DateTime('2024-05-06');
        $ethiopian = $this->calendar->fromGregorian($date);

        $this->assertEquals(2016, $ethiopian['year']);
        $this->assertEquals(8, $ethiopian['month']);
        $this->assertEquals(28, $ethiopian['day']);

        // Test with string
        $ethiopianStr = $this->calendar->fromGregorian('2024-05-06');
        $this->assertEquals(2016, $ethiopianStr['year']);
    }

    public function test_it_converts_ethiopian_to_gregorian()
    {
        // Standard call
        $gregorian = $this->calendar->toGregorian(2016, 8, 28);
        $this->assertEquals('2024-05-06', $gregorian->format('Y-m-d'));

        // YYYY-MM-DD
        $this->assertEquals('2024-05-06', $this->calendar->toGregorian('2016-08-28')->format('Y-m-d'));
        
        // DD/MM/YYYY
        $this->assertEquals('2024-05-06', $this->calendar->toGregorian('28/08/2016')->format('Y-m-d'));
        
        // DD.MM.YYYY
        $this->assertEquals('2024-05-06', $this->calendar->toGregorian('28.08.2016')->format('Y-m-d'));
        
        // Mixed space separator
        $this->assertEquals('2024-05-06', $this->calendar->toGregorian('2016 08 28')->format('Y-m-d'));
        
        // Single digit month/day (2016-01-02 Ethiopian is 2023-09-13 Gregorian)
        $this->assertEquals('2023-09-13', $this->calendar->toGregorian('2016-1-2')->format('Y-m-d'));
    }

    public function test_it_throws_exception_on_invalid_string()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->calendar->toGregorian('invalid-date');
    }

    public function test_it_throws_exception_on_incomplete_string()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->calendar->toGregorian('2016-08');
    }

    public function test_pagume_days()
    {
        // Pagume has 5 or 6 days depending on leap year
        $this->assertEquals(6, $this->calendar->daysInMonth(2011, 13)); // Leap year
        $this->assertEquals(5, $this->calendar->daysInMonth(2012, 13)); // Common year
    }

    public function test_timezone_handling()
    {
        $calendar = new EthiopianCalendar('America/New_York');
        $gregorian = $calendar->toGregorian('2016-08-28');
        
        $this->assertEquals('America/New_York', $gregorian->getTimezone()->getName());
        
        $calendar->setTimezone('Africa/Addis_Ababa');
        $gregorian2 = $calendar->toGregorian('2016-08-28');
        $this->assertEquals('Africa/Addis_Ababa', $gregorian2->getTimezone()->getName());
    }
}
