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
        $this->assertEquals('cc83646f6783676f6483636174', $encoded->toString('hex'));
        $this->assertEquals(13, $encoded->length());

        $encoded = $rlp->encode(['0xabcd', '0xdeff', '0xaaaa']);
        $this->assertEquals('c982abcd82deff82aaaa', $encoded->toString('hex'));
        $this->assertEquals(10, $encoded->length());
    }
}