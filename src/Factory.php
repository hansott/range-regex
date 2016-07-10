<?php

namespace HansOtt\RangeRegex;

interface Factory
{
    /**
     * @return Converter
     */
    public function getConverter();
}
