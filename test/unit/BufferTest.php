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
        $this->assertEquals(11, $buffer->length());

        $buffer = new Buffer('abcdabcdabcdabcd', 'hex');
        $this->assertEquals('abcdabcdabcdabcd', $buffer->toString('hex'));
        $this->assertEquals(8, $buffer->length());

        $buffer = new Buffer('bcdabcdabcdabcd', 'hex');
        $this->assertEquals('0bcdabcdabcdabcd', $buffer->toString('hex'));
        $this->assertEquals(8, $buffer->length());

        $buffer = new Buffer('我是測試');
        $this->assertEquals('我是測試', $buffer->toString('utf8'));
        $this->assertEquals('e68891e698afe6b8ace8a9a6', $buffer->toString('hex'));
        $this->assertEquals(12, $buffer->length());
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
        $this->assertEquals(27, $buffer->length());

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
        $this->assertEquals(1, $buffer->length());

        $buffer = new Buffer(1.56);
        $this->assertEquals('1', $buffer->toString('hex'));
        $this->assertEquals(1, $buffer->length());

        $buffer = new Buffer(100);
        $this->assertEquals('64', $buffer->toString('hex'));
        $this->assertEquals(1, $buffer->length());
    }

    /**
     * testConcate
     * 
     * @return void
     */
    public function testConcat()
    {
        $buffer = new Buffer(['Hello World', 'abcdabcdabcdabcd'], 'ascii');
        $this->assertEquals('Hello Worldabcdabcdabcdabcd', $buffer->toString('ascii'));
        $this->assertEquals(27, $buffer->length());

        $buffer->concat(['Test', 'Yo', 1]);
        $this->assertEquals('48656c6c6f20576f726c646162636461626364616263646162636454657374596f01', $buffer->toString('hex'));
        $this->assertEquals(34, $buffer->length());

        $bufferB = new Buffer(['A lo ha'], 'ascii');
        $buffer->concat($bufferB);
        $this->assertEquals('48656c6c6f20576f726c646162636461626364616263646162636454657374596f0141206c6f206861', $buffer->toString('hex'));
        $this->assertEquals(41, $buffer->length());

        $bufferC = new Buffer(['Goog'], 'ascii');
        $buffer->concat($bufferC, ['yo']);
        $this->assertEquals('48656c6c6f20576f726c646162636461626364616263646162636454657374596f0141206c6f206861476f6f67796f', $buffer->toString('hex'));
        $this->assertEquals(47, $buffer->length());
    }
}