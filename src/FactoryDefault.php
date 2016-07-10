<?php

namespace HansOtt\RangeRegex;

final class FactoryDefault implements Factory
{
    public function getConverter()
    {
        $converter = new ConverterDefault();

        return new ConverterCached($converter);
    }
}
