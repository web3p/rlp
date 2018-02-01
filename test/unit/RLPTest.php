<?php

namespace Test\Unit;

use Test\TestCase;

class RLPTest extends TestCase
{
    /**
     * testEncode
     * 
     * @return void
     */
    public function testEncode()
    {
        $rlp = $this->rlp;

        $encoded = $rlp->encode(['dog', 'god', 'cat']);
        $this->assertEquals('0c03646f6703676f6403636174', $encoded->toString('hex'));
        $this->assertEquals(13, $encoded->length());

        $encoded = $rlp->encode(['0xabcd', '0xdeff', '0xaaaa']);
        $this->assertEquals('0902abcd02deff02aaaa', $encoded->toString('hex'));
        $this->assertEquals(10, $encoded->length());
    }
}