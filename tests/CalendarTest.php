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
    }

    public function test_it_converts_ethiopian_to_gregorian()
    {
        // 2016-08-28 Ethiopian is 2024-05-06 Gregorian
        $gregorian = $this->calendar->toGregorian(2016, 8, 28);

        $this->assertEquals('2024-05-06', $gregorian->format('Y-m-d'));
    }

    public function test_pagume_days()
    {
        // Pagume has 5 or 6 days depending on leap year
        $this->assertEquals(6, $this->calendar->daysInMonth(2011, 13)); // Leap year
        $this->assertEquals(5, $this->calendar->daysInMonth(2012, 13)); // Common year
    }
}
