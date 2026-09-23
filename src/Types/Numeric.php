<?php

/**
 * This file is part of rlp package.
 * 
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 * 
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace Web3p\RLP\Types;

use InvalidArgumentException;

/**
 * It's a numeric type instance for ethereum recursive length encoding.
 * Note: there is only static function in this class.
 * 
 * @author Peter Lai <alk03073135@gmail.com>
 * @link https://www.web3p.xyz
 * @filesource https://github.com/web3p/rlp
 */
class Numeric
{
    /**
     * Return hex encoded of numeric string.
     *
     * @param string $input numeric string
     * @return string encoded hex of input
     */
    static function encode(string $input)
    {
        if ($input === '') {
            return '';
        }
        if (!is_numeric($input)) {
            throw new InvalidArgumentException('Invalid numeric value.');
        }
        // int for integer strings in range, float otherwise
        $number = $input + 0;

        if (is_float($number)) {
            if ($number != floor($number) || $number >= (float) PHP_INT_MAX || $number < 0) {
                throw new InvalidArgumentException('Numeric value must be a non-negative integer not greater than PHP_INT_MAX, use a hex string for larger values.');
            }
            $number = (int) $number;
        }
        if ($number < 0) {
            throw new InvalidArgumentException('Numeric value must be a non-negative integer not greater than PHP_INT_MAX, use a hex string for larger values.');
        }
        if ($number === 0) {
            return '';
        }
        $output = dechex($number);
        $outputLen = mb_strlen($output);
        if ($outputLen > 0 && $outputLen % 2 !== 0) {
            return '0' . $output;
        }
        return $output;
    }
}
