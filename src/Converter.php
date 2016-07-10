<?php

namespace HansOtt\RangeRegex;

interface Converter
{
    public function toRegex(Range $range);
}
