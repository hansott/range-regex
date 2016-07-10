<?php

namespace HansOtt\RangeRegex;

use PHPUnit_Framework_TestCase;

final class ConverterDefaultTest extends PHPUnit_Framework_TestCase
{
    public function test_it_converts_a_range_to_a_regex()
    {
        $converter = new ConverterDefault();
        $this->assertEquals('1', $converter->toRegex(new Range(1, 1)));
        $this->assertEquals('[0-1]', $converter->toRegex(new Range(0, 1)));
        $this->assertEquals('-1', $converter->toRegex(new Range(-1, -1)));
        $this->assertEquals('-1|-10', $converter->toRegex(new Range(-1, -10)));
        $this->assertEquals('-1|0', $converter->toRegex(new Range(-1, 0)));
        $this->assertEquals('-1|[0-1]', $converter->toRegex(new Range(-1, 1)));
        $this->assertEquals('-[2-4]', $converter->toRegex(new Range(-4, -2)));
        $this->assertEquals('-[1-3]|[0-1]', $converter->toRegex(new Range(-3, 1)));
        $this->assertEquals('-[1-2]|0', $converter->toRegex(new Range(-2, 0)));
        $this->assertEquals('[0-2]', $converter->toRegex(new Range(0, 2)));
        $this->assertEquals('-1|[0-3]', $converter->toRegex(new Range(-1, 3)));
        $this->assertEquals('6566[6-7]', $converter->toRegex(new Range(65666, 65667)));
        $this->assertEquals('1[2-9]|[2-9][0-9]|[1-9][0-9]{2}|[1-2][0-9]{3}|3[0-3][0-9]{2}|34[0-4][0-9]|345[0-6]', $converter->toRegex(new Range(12, 3456)));
        $this->assertEquals('[1-9]|[1-9][0-9]|[1-9][0-9]{2}|[1-2][0-9]{3}|3[0-3][0-9]{2}|34[0-4][0-9]|345[0-6]', $converter->toRegex(new Range(1, 3456)));
        $this->assertEquals('[1-9]|10', $converter->toRegex(new Range(1, 10)));
        $this->assertEquals('[1-9]|1[0-9]', $converter->toRegex(new Range(1, 19)));
        $this->assertEquals('[1-9]|[1-9][0-9]', $converter->toRegex(new Range(1, 99)));
    }

    public function test_it_should_optimize_regexes()
    {
        $converter = new ConverterDefault();
        $this->assertEquals('-[1-9]|[0-9]', $converter->toRegex(new Range(-9, 9)));
        $this->assertEquals('-[1-9]|-?1[0-9]|[0-9]', $converter->toRegex(new Range(-19, 19)));
        $this->assertEquals('-[1-9]|-?[1-2][0-9]|[0-9]', $converter->toRegex(new Range(-29, 29)));
        $this->assertEquals('-[1-9]|-?[1-9][0-9]|[0-9]', $converter->toRegex(new Range(-99, 99)));
        $this->assertEquals('-[1-9]|-?[1-9][0-9]|-?[1-9][0-9]{2}|[0-9]', $converter->toRegex(new Range(-999, 999)));
        $this->assertEquals('-[1-9]|-?[1-9][0-9]|-?[1-9][0-9]{2}|-?[1-9][0-9]{3}|[0-9]', $converter->toRegex(new Range(-9999, 9999)));
    }

    public function test_it_generates_valid_regexes()
    {
        $this->verifyRange(10031, 20081, 0, 59999);
        $this->verifyRange(10000, 20000, 0, 59999);
        $this->verifyRange(102, 111, 0, 1000);
        $this->verifyRange(102, 110, 0, 1000);
        $this->verifyRange(102, 130, 0, 1000);
        $this->verifyRange(3, 7, 0, 99);
        $this->verifyRange(1, 9, 0, 1000);
        $this->verifyRange(1030, 20101, 0, 99999);
        $this->verifyRange(13, 8632, 0, 10000);
        $this->verifyRange(9, 11, 0, 100);
        $this->verifyRange(19, 21, 0, 100);
        $this->verifyRange(90, 98009, 0, 98999);
        $this->verifyRange(999, 10000, 1, 10000);
    }

    private function verifyRange($min, $max, $from, $to)
    {
        $converter = new ConverterDefault();
        $range = new Range($min, $max);
        $regex = sprintf('/^(%s)$/', $converter->toRegex($range));
        $toTest = range($from, $to);

        foreach ($toTest as $num) {
            $itShouldMatch = $min <= $num && $num <= $max;
            $isMatch = (bool) preg_match($regex, $num);
            $this->assertSame($itShouldMatch, $isMatch);
        }
    }
}
