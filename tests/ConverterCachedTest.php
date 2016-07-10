<?php

namespace HansOtt\RangeRegex;

use PHPUnit_Framework_TestCase;

final class ConverterCachedTest extends PHPUnit_Framework_TestCase
{
    private function mock($className)
    {
        return $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->getMock();
    }

    public function test_it_caches_calculated_regexes()
    {
        $range = new Range(1, 2);

        $mock = $this->mock(Converter::class);
        $mock->expects($this->once())
             ->method('toRegex')
             ->with($this->equalTo($range))
             ->willReturn('calculated-regex');

        $converter = new ConverterCached($mock);
        $regex = $converter->toRegex($range);
        $this->assertEquals('calculated-regex', $regex);
    }
}
