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
        if (!$input || $input < 0) {
            return '';
        }
        if (is_float($input)) {
            $input = number_format($input, 0, '', '');
        }
        $intInput = strval($input);
        $output = dechex($intInput);
        $outputLen = mb_strlen($output);
        if ($outputLen > 0 && $outputLen % 2 !== 0) {
            return '0' . $output;
        }
        return $output;
    }
}
