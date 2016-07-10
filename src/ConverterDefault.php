<?php

namespace HansOtt\RangeRegex;

final class ConverterDefault implements Converter
{
    public function toRegex(Range $range)
    {
        $min = $range->getMin();
        $max = $range->getMax();

        if ($min === $max) {
            return $min;
        }

        if ($min > $max) {
            return sprintf('%d|%d', $min, $max);
        }

        $positives = [];
        $negatives = [];

        if ($min < 0) {
            $newMin = 1;
            if ($max < 0) {
                $newMin = abs($max);
            }

            $newMax = abs($min);
            $negatives = $this->splitToPatterns($newMin, $newMax);
            $min = 0;
        }

        if ($max >= 0) {
            $positives = $this->splitToPatterns($min, $max);
        }

        return $this->siftPatterns($negatives, $positives);
    }

    private function splitToRanges($min, $max)
    {
        $nines = 1;
        $stops = [$max];
        $stop = $this->countNines($min, $nines);

        while ($min <= $stop && $stop <= $max) {
            if (in_array($stop, $stops, true) === false) {
                $stops[] = $stop;
            }

            $nines++;
            $stop = $this->countNines($min, $nines);
        }

        $zeros = 1;
        $stop = $this->countZeros($max + 1, $zeros) - 1;

        while ($min < $stop && $stop <= $max) {
            if (in_array($stop, $stops, true) === false) {
                $stops[] = $stop;
            }

            $zeros += 1;
            $stop = $this->countZeros($max + 1, $zeros) - 1;
        }

        sort($stops);

        return $stops;
    }

    private function splitToPatterns($min, $max)
    {
        $start = $min;
        $subPatterns = [];
        $ranges = $this->splitToRanges($min, $max);
        foreach ($ranges as $index => $range) {
            $subPatterns[$index] = $this->rangeToPattern($start, $range);
            $start = $range + 1;
        }

        return $subPatterns;
    }

    private function siftPatterns(array $negatives, array $positives)
    {
        $onlyNegative = $this->filterPatterns($negatives, $positives, '-');
        $onlyPositives = $this->filterPatterns($positives, $negatives, '');
        $intersected = $this->filterPatterns($negatives, $positives, '-?', true);
        $subPatterns = array_merge($onlyNegative, $intersected, $onlyPositives);

        return implode('|', $subPatterns);
    }

    private function filterPatterns(array $arr, array $comparison, $prefix, $intersection = false)
    {
        $intersected = [];
        $result = [];
        foreach ($arr as $element) {
            if ($intersection === false && in_array($element, $comparison, true) === false) {
                $result[] = $prefix . $element;
            }

            if ($intersection && in_array($element, $comparison, true)) {
                $intersected[] = $prefix . $element;
            }
        }

        return $intersection ? $intersected : $result;
    }

    private function rangeToPattern($start, $stop)
    {
        $pattern = '';
        $digits = 0;
        $pairs = $this->zip($start, $stop);
        foreach ($pairs as $pair) {
            $startDigit = $pair[0];
            $stopDigit = $pair[1];

            if ($startDigit === $stopDigit) {
                $pattern .= $startDigit;
                continue;
            }

            if ($startDigit !== '0' || $stopDigit !== '9') {
                $pattern .= sprintf('[%d-%d]', $startDigit, $stopDigit);
                continue;
            }

            $digits++;
        }

        if ($digits > 0) {
            $pattern .= '[0-9]';
        }

        if ($digits > 1) {
            $pattern .= sprintf('{%d}', $digits);
        }

        return $pattern;
    }

    private function countNines($num, $nines)
    {
        $num = (string) $num;
        $offset = (-1) * $nines;

        return (int) (mb_substr($num, 0, $offset) . str_repeat('9', $nines));
    }

    private function countZeros($integer, $zeros)
    {
        return $integer - ($integer % pow(10, $zeros));
    }

    private function zip($start, $stop)
    {
        $start = (string) $start;
        $stop = (string) $stop;

        $start = str_split($start);
        $stop = str_split($stop);

        $zipped = [];
        foreach ($start as $index => $char) {
            $zipped[] = [$char, $stop[$index]];
        }

        return $zipped;
    }
}
