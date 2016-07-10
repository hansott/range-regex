<?php

namespace HansOtt\RangeRegex;

use InvalidArgumentException;

final class Range
{
    private $min;

    private $max;

    /**
     * Range constructor.
     *
     * @param int $min
     * @param int $max
     */
    public function __construct($min, $max)
    {
        $this->assertTheseAreIntegers($min, $max);
        $this->min = $min;
        $this->max = $max;
    }

    public function getMin()
    {
        return $this->min;
    }

    public function getMax()
    {
        return $this->max;
    }

    private function assertTheseAreIntegers($min, $max)
    {
        if (is_int($min) === false) {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected an integer as $min but instead got: %s',
                    is_object($min) ? get_class($min) : gettype($min)
                )
            );
        }

        if (is_int($max) === false) {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected an integer as $max but instead got: %s',
                    is_object($max) ? get_class($max) : gettype($max)
                )
            );
        }
    }
}
