<?php

namespace Test\Unit;

use Test\TestCase;
use RLP\Buffer;

class BufferTest extends TestCase
{
    public function testCreateStringBuffer()
    {
        $buffer = new Buffer('Hello World', 'ascii');
        $this->assertEquals('Hello World', $buffer->toString('ascii'));

        $buffer = new Buffer('abcdabcdabcdabcd', 'hex');
        $this->assertEquals('abcdabcdabcdabcd', $buffer->toString('hex'));

        $buffer = new Buffer('bcdabcdabcdabcd', 'hex');
        $this->assertEquals('bcdabcdabcdabcd', $buffer->toString('hex'));
    }
}