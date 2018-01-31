<?php

namespace Test\Unit;

use Test\TestCase;
use RLP\Buffer;
use InvalidArgumentException;

class BufferTest extends TestCase
{
    /**
     * testCreateStringBuffer
     * 
     * @return void
     */
    public function testCreateStringBuffer()
    {
        $buffer = new Buffer('Hello World', 'ascii');
        $this->assertEquals('Hello World', $buffer->toString('ascii'));

        $buffer = new Buffer('abcdabcdabcdabcd', 'hex');
        $this->assertEquals('abcdabcdabcdabcd', $buffer->toString('hex'));

        $buffer = new Buffer('bcdabcdabcdabcd', 'hex');
        $this->assertEquals('bcdabcdabcdabcd', $buffer->toString('hex'));
    }

    /**
     * testCreateArrayBuffer
     * 
     * @return void
     */
    public function testCreateArrayBuffer()
    {
        $buffer = new Buffer(['Hello World', 'abcdabcdabcdabcd'], 'ascii');
        $this->assertEquals('Hello Worldabcdabcdabcdabcd', $buffer->toString('ascii'));

        $buffer = new Buffer(['Hello World', 'abcdabcdabcdabcd'], 'ascii');
        $this->assertEquals('48656c6c6f20576f726c6461626364616263646162636461626364', $buffer->toString('hex'));
    }

    /**
     * testCreateMultidimentionalArrayBuffer
     * 
     * @return void
     */
    public function testCreateMultidimentionalArrayBuffer()
    {
        $this->expectException(InvalidArgumentException::class);

        $buffer = new Buffer(['Hello World', 'abcdabcdabcdabcd', ['Hello World', 'abcdabcdabcdabcd']], 'ascii');
    }

    /**
     * testCreateNumberBuffer
     * 
     * @return void
     */
    public function testCreateNumberBuffer()
    {
        $buffer = new Buffer(1);
        $this->assertEquals('1', $buffer->toString('hex'));

        $buffer = new Buffer(1.56);
        $this->assertEquals('1', $buffer->toString('hex'));

        $buffer = new Buffer(100);
        $this->assertEquals('64', $buffer->toString('hex'));
    }
}