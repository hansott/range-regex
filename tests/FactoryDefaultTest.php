<?php

namespace HansOtt\RangeRegex;

use PHPUnit_Framework_TestCase;

final class FactoryDefaultTest extends PHPUnit_Framework_TestCase
{
    public function test_it_returns_a_converter()
    {
        $factory = new FactoryDefault();
        $converter = $factory->getConverter();
        $this->assertInstanceOf(Converter::class, $converter);
    }
}
