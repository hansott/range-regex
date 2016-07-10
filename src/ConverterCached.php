<?php

namespace HansOtt\RangeRegex;

final class ConverterCached implements Converter
{
    private $converter;

    private $cache = [];

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    private function getKey(Range $range)
    {
        return sprintf('%d:%d', $range->getMin(), $range->getMax());
    }

    public function toRegex(Range $range)
    {
        $key = $this->getKey($range);

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $regex = $this->converter->toRegex($range);
        $this->cache[$key] = $regex;

        return $regex;
    }
}
