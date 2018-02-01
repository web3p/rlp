<?php

/**
 * This file is part of rlp package.
 * 
 * (c) Kuan-Cheng,Lai <alk03073135@gmail.com>
 * 
 * @author Peter Lai <alk03073135@gmail.com>
 * @license MIT
 */

namespace RLP;

use InvalidArgumentException;
use RLP\Buffer;

class RLP
{
    /**
     * encode
     * 
     * @param array $inputs array of string
     * @return string
     */
    public function encode($inputs)
    {
        if (is_array($inputs)) {
            $output = new Buffer;
            $result = new Buffer;

            foreach ($inputs as $input) {
                $output->concat($this->encode($input));
            }
            return $result->concat($this->encodeLength($output->length(), 192), $output);
        }
        $output = new Buffer;
        $input = $this->toBuffer($inputs);
        $length = $input->length();

        if ($length === 1 && $input[0] < 128) {
            return $input[0];
        } else {
            return $output->concat($this->encodeLength($length, 128), $input);
        }
    }

    /**
     * encodeLength
     * 
     * @param int $length
     * @param int $offset
     * @return \RLP\Buffer
     */
    protected function encodeLength($length, $offset)
    {
        if (!is_int($length) || !is_int($offset)) {
            throw new InvalidArgumentException('Length and offset must be int when call encodeLength.');
        }
        if ($length < 56) {
            return new Buffer($length + $offset);
        }
        $hexLength = $this->intToHex($length);
        $firstByte = $this->intToHex($offset + 55 + (strlen($hexLength) / 2));
        return new Buffer($firstByte . $hexLength, 'hex');
    }

    /**
     * intToHex
     * 
     * @param int $value
     * @return string
     */
    protected function intToHex($value)
    {
        if (!is_int($value)) {
            throw new InvalidArgumentException('Value must be int when call intToHex.');
        }
        $hex = dechex($value);

        return $this->padToEven($hex);
    }

    /**
     * padToEven
     * 
     * @param string $value
     * @return string
     */
    protected function padToEven($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('Value must be string when call padToEven.');
        }
        if ((strlen($value) % 2) !== 0 ) {
            $value = '0' . $value;
        }
        return $value;
    }

    /**
     * toArray
     * Format input to value, deprecated when we have toBuffer.
     * 
     * @param mixed $input
     * @return array
     */
    // protected function toArray($input)
    // {
    //     if (is_string($input)) {
    //         if (strpos($input, '0x') === 0) {
    //             // hex string
    //             $value = str_replace('0x', '', $input);
    //             return $input;
    //         } else {
    //             return str_split($input, 1);
    //         }
    //     }
    //     throw new InvalidArgumentException('The input type didn\'t support.');
    // }

    /**
     * toBuffer
     * Format input to buffer.
     * 
     * @param mixed $input
     * @return \RLP\Buffer
     */
    protected function toBuffer($input)
    {
        if (is_numeric($input)) {
            return new Buffer($input);
        } elseif (is_string($input)) {
            if (strpos($input, '0x') === 0) {
                // hex string
                $input = str_replace('0x', '', $input);
                return new Buffer($input, 'hex');
            }
            return new Buffer(str_split($input, 1));
        } elseif (is_array($input)) {
            return new Buffer($input);
        } elseif ($input instanceof Buffer) {
            return $input;
        }
        throw new InvalidArgumentException('The input type didn\'t support.');
    }
}